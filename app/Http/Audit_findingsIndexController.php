<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\AuditFindings;
use App\Models\AuditFindingsRevision;
use App\Models\AuditFindingAttachment;
use App\Models\AuditPlanning;
use App\Models\Working_paperIndex;
use App\Services\Audit_findingsIndexService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Audit_findingsIndexController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, $data = [])
    {
        // Matches the Working Paper module's pattern: the Add/Edit form is
        // now a full page (?form=create|edit) instead of a modal.
        if (in_array($request->input('form'), ['create', 'edit'])) {
            return $this->renderForm($request, $data);
        }

        // Intermediate step: pick an Audit Plan, then see the findings
        // already recorded inside that plan's Working Papers, and turn
        // one into a formal Finding — see renderPlanFindings() below.
        if ($request->input('form') === 'select') {
            return $this->renderPlanFindings($request, $data);
        }

        $service = new Audit_findingsIndexService();
        $result  = $service->getData($request);
        $rows    = $result['rows'];

        // ── Master data ──────────────────────────────────────────────
        // FIX (per request): only finalized Audit Plans are relevant for
        // inputting Findings, so the Plan filter here is scoped the same
        // way as the Add Finding form's own Plan dropdown. Whatever
        // plan_id is currently active in the filter stays in the list
        // even if it isn't finalized, so switching to it via a direct
        // link never leaves the filter showing a value with no matching
        // option (which would otherwise silently reset to "All Plans").
        $activeFilterPlanId = $request->input('plan_id');
        $plans = AuditPlanning::whereNull('deleted_at')
            ->where(function ($q) use ($activeFilterPlanId) {
                $q->where('is_final', 1);
                if ($activeFilterPlanId) {
                    $q->orWhere('plan_id', $activeFilterPlanId);
                }
            })
            ->orderByDesc('plan_start')
            ->get();

        $riskLevels = DB::table('tbl_select_list')->where('type', 'RISK_LEVEL')->orderBy('sl_id')->get(['sl_id', 'name']);
        // `findingsclas` is the real column on tbl_ho_audit_findings (see
        // AuditFindings::$fillable — there's no 'status' column at all).
        $findingsclas    = DB::table('tbl_select_list')->where('type', 'FINDING_CLASSIFICATION')->orderBy('sl_id')->get(['sl_id', 'name']);
        $riskLevelMap    = $riskLevels->pluck('name', 'sl_id');
        $findingsclasMap = $findingsclas->pluck('name', 'sl_id');

        $auditAreas = AuditFindings::whereNull('deleted_at')->whereNotNull('audit_area')->distinct()->pluck('audit_area')
            ->merge(Working_paperIndex::whereNull('deleted_at')->whereNotNull('audit_area')->distinct()->pluck('audit_area'))
            ->unique()->sort()->values();
        $auditors = DB::table('tbl_users')->where('is_active', 1)->orderBy('name')->get(['id', 'name']);


        // Findings already recorded in this plan's Working Papers, ready
        // to be pulled into a full Finding (Detail + Recommendation) —
        // only meaningful once a specific plan is selected in the filter,
        // since a WP finding is inherently scoped to one plan. Reuses the
        // exact same builder as the standalone "select" screen.
        $wpFindingItems = collect();
        $filterPlanId = $request->input('plan_id');
        if ($filterPlanId) {
            $wpFindingItems = $this->buildWpFindingItems($filterPlanId);

            // FIX: the Risk Level / Classification / Search filters in
            // the toolbar previously had nothing to act on, since the
            // main findings table they used to filter was removed.
            // Apply them here instead, against this table.
            if ($riskFilter = $request->input('risk_level')) {
                $wpFindingItems = $wpFindingItems
                    ->filter(fn ($i) => (string) $i['risk_level'] === (string) $riskFilter)
                    ->values();
            }
            if ($classFilter = $request->input('findingsclas')) {
                // Classification only exists once a finding has actually
                // been formalized (it's a field on the real AuditFindings
                // record, not something the Working Paper's raw
                // repeater entry carries) — so filtering by Classification
                // naturally leaves only "Already Input" rows that match;
                // Pending rows have no classification yet and drop out.
                $wpFindingItems = $wpFindingItems
                    ->filter(fn ($i) => $i['finding'] && (string) $i['finding']->findingsclas === (string) $classFilter)
                    ->values();
            }
            if ($searchTerm = $request->input('search')) {
                $needle = strtolower($searchTerm);
                $wpFindingItems = $wpFindingItems
                    ->filter(function ($i) use ($needle) {
                        return str_contains(strtolower($i['title']), $needle)
                            || str_contains(strtolower((string) $i['audit_area']), $needle)
                            || str_contains(strtolower((string) $i['wp_no']), $needle);
                    })
                    ->values();
            }
        }

        // ── Stat cards (reflect the currently filtered result set) ────
        // FIX: cast risk_level to int on both sides before grouping/
        // looking up — if the column ever comes back as a numeric
        // string with whitespace, or a different type than sl_id from
        // this separate tbl_select_list query, a plain groupBy() could
        // silently fail to match even though the value is genuinely
        // set. This only guards against a type mismatch; if a
        // finding's risk_level is truly null/unset, it will still
        // correctly show up in Total but not in any risk bucket —
        // that's a data issue on that record (see the store() guard
        // below, which now blocks a null/blank risk_level from ever
        // being saved in the first place), not a counting bug.
        $riskCounts = $rows->groupBy(fn ($r) => (int) $r->risk_level)->map->count();
        $total      = $rows->count();

        // FIX: matched by name with a plain === before, so a
        // tbl_select_list value like "high", " High", or "High Risk"
        // (any casing/whitespace/wording drift from the literal string
        // "High") silently made $highId null — and every bucket below
        // it just as silently showed 0 forever, indistinguishable from
        // "no high-risk findings exist". Matching case-insensitively/
        // trimmed first, with an exact-match fallback, fixes the
        // common drift; if a name genuinely doesn't exist at all we log
        // it instead of failing silently, so this doesn't happen again
        // without anyone noticing.
        $findByNameLoose = function ($collection, string $target) {
            $needle = strtolower(trim($target));
            return $collection->first(fn ($row) => strtolower(trim($row->name)) === $needle)
                ?? $collection->firstWhere('name', $target);
        };

        $highRow   = $findByNameLoose($riskLevels, 'High');
        $medRow    = $findByNameLoose($riskLevels, 'Medium');
        $lowRow    = $findByNameLoose($riskLevels, 'Low');
        $closedRow = $findByNameLoose($findingsclas, 'Closed');

        foreach (['High' => $highRow, 'Medium' => $medRow, 'Low' => $lowRow, 'Closed' => $closedRow] as $label => $row) {
            if (!$row) {
                Log::warning("audit_findings stat card: no tbl_select_list entry matching \"{$label}\" was found — that card will always show 0.", [
                    'expected' => $label,
                    'available_risk_levels' => $riskLevels->pluck('name'),
                    'available_classifications' => $findingsclas->pluck('name'),
                ]);
            }
        }

        $highId   = $highRow->sl_id ?? null;
        $medId    = $medRow->sl_id ?? null;
        $lowId    = $lowRow->sl_id ?? null;
        $closedId = $closedRow->sl_id ?? null;

        $highCount   = $highId ? ($riskCounts[(int) $highId] ?? 0) : 0;
        $medCount    = $medId ? ($riskCounts[(int) $medId] ?? 0) : 0;
        $lowCount    = $lowId ? ($riskCounts[(int) $lowId] ?? 0) : 0;
        $closedCount = $closedId ? $rows->where('findingsclas', $closedId)->count() : 0;
        $pct = fn($n) => $total > 0 ? round(($n / $total) * 100, 2) : 0;

        // Diagnostic surfaced to the view: findings counted in Total but
        // missing/unmatched risk_level, so a genuine data gap (as opposed
        // to a naming mismatch, which the warning above already catches)
        // is visible on the page itself rather than only in logs.
        $unclassifiedRiskCount = $rows->filter(function ($r) use ($highId, $medId, $lowId) {
            $rl = $r->risk_level !== null ? (int) $r->risk_level : null;
            return $rl === null || !in_array($rl, array_filter([$highId, $medId, $lowId]), true);
        })->count();


        // ── Attachments per finding ─────────────────────────────────
        $attachmentsByFinding = collect();
        try {
            $attachmentsByFinding = AuditFindingAttachment::whereIn('finding_id', $rows->pluck('finding_id'))
                ->get()->groupBy('finding_id');
        } catch (\Throwable $e) {
            // attachment table not present yet
        }

        $userNames = DB::table('tbl_users')->pluck('name', 'id');

        $revisionsByFinding = collect();
        try {
            $revisionsByFinding = AuditFindingsRevision::whereIn('finding_id', $rows->pluck('finding_id'))
                ->orderByDesc('performed_at')
                ->get()
                ->groupBy('finding_id');
        } catch (\Throwable $e) {}

        $rowsJson = $rows->keyBy('finding_id')->map(function ($r) use ($userNames, $riskLevelMap, $findingsclasMap, $attachmentsByFinding, $revisionsByFinding) {
            $arr = $r->toArray();
            $arr['risk_level_label']  = $riskLevelMap[$r->risk_level] ?? null;
            $arr['findingsclas_label'] = $findingsclasMap[$r->findingsclas] ?? null;
            $arr['created_by_name']   = $userNames[$r->created_by] ?? null;
            $arr['modified_by_name']  = $userNames[$r->modified_by] ?? null;
            $arr['finalized_by_name'] = $userNames[$r->finalized_by] ?? null;
            $arr['attachments']       = ($attachmentsByFinding[$r->finding_id] ?? collect())->values();
            $arr['revisions'] = ($revisionsByFinding[$r->finding_id] ?? collect())->map(fn($rev) => [
                'action'            => $rev->action,
                'changes'           => $rev->changes,
                'performed_by'      => $rev->performed_by,
                'performed_by_name' => $userNames[$rev->performed_by] ?? null,
                'performed_at'      => optional($rev->performed_at)->format('Y-m-d H:i'),
            ])->values();
            return $arr;
        })->toArray();

        return view('audit_findings.index', array_merge($data, [
            'title'          => $data['title'] ?? 'Audit Findings & Recommendations',
            'plans'          => $plans,
            'auditAreas'     => $auditAreas,
            'riskLevels'     => $riskLevels,
            'riskLevelMap'   => $riskLevelMap,
            'findingsclasMap' => $findingsclasMap,
            'findingsclas'   => $findingsclas,
            'rows'           => $rows,
            'rowsJson'       => $rowsJson,
            'total'          => $total,
            'highCount'      => $highCount,
            'medCount'       => $medCount,
            'lowCount'       => $lowCount,
            'closedCount'    => $closedCount,
            'highPct'        => $pct($highCount),
            'medPct'         => $pct($medCount),
            'lowPct'         => $pct($lowCount),
            'closedPct'      => $pct($closedCount),
            'unclassifiedRiskCount' => $unclassifiedRiskCount,
            'error'          => $result['error'],
            'message'        => $result['message'],
            'canCreate'      => Auth::check() ? Auth::user()->can('create', AuditFindings::class) : false,
            'wpFindingItems' => $wpFindingItems,
            'filterPlanId'   => $filterPlanId,
        ]));
    }

    /**
     * Renders the full-page "Add Finding" / "Edit Finding" screen — moved
     * out of the modal in index.blade.php to match the Working Paper
     * module's pattern.
     */
    /**
     * Intermediate step between "pick a Plan" and the Add Finding form.
     *
     * Shows a list of Audit Plans, and for whichever one is selected,
     * every finding already recorded inside that plan's Working Papers
     * (the same `findings` JSON repeater the WP picker on the Add
     * Finding form reads from — see onWpChange() in create.blade.php).
     * Each one is flagged as already formalized into a real
     * AuditFindings record, or still pending, so the same WP finding
     * is never silently offered twice. Clicking "Input Finding" on a
     * pending row jumps straight to the create form with that working
     * paper, title, and risk level pre-selected.
     */
    private function renderPlanFindings(Request $request, $data = [])
    {
        $plans = AuditPlanning::whereNull('deleted_at')->orderByDesc('plan_start')->get();
        $planId = $request->input('plan_id') ?: optional($plans->first())->plan_id;
        $plan   = $plans->firstWhere('plan_id', $planId);

        if (!$plan) {
            return redirect(url(request()->path()))->with('error', 'Select an audit plan first.');
        }

        return view('audit_findings.select', array_merge($data, [
            'title'  => 'Select Finding to Input',
            'plans'  => $plans,
            'plan'   => $plan,
            'planId' => $planId,
            'items'  => $this->buildWpFindingItems($planId),
        ]));
    }

    /**
     * Every finding already recorded inside a plan's Working Papers (the
     * `findings` JSON repeater — same data the WP picker on the Add
     * Finding form itself reads from), flagged as already formalized
     * into a real AuditFindings record or still pending. Shared by both
     * the standalone "select" screen and the inline section on the
     * index page itself.
     *
     * Matched to an existing AuditFindings record on wp_id + trimmed/
     * lowercased title — there's no shared foreign key for an individual
     * repeater row, only for the working paper as a whole, so title
     * matching is the best signal available.
     */
    private function buildWpFindingItems($planId)
    {
        $workingPapers = Working_paperIndex::whereNull('deleted_at')
            ->where('plan_id', $planId)
            ->where('test_result', 'Yes')
            ->orderBy('working_paper_no')
            ->get(['wp_id', 'working_paper_no', 'audit_area', 'findings']);

        $existingFindings = AuditFindings::whereNull('deleted_at')
            ->where('plan_id', $planId)
            ->whereNotNull('wp_id')
            ->get();
        $existingByWp = $existingFindings->groupBy('wp_id');

        $riskLevels   = DB::table('tbl_select_list')->where('type', 'RISK_LEVEL')->orderBy('sl_id')->get(['sl_id', 'name']);
        $riskLevelMap = $riskLevels->pluck('name', 'sl_id');

        $items = collect();
        foreach ($workingPapers as $wp) {
            // Same defensive normalization as onWpChange()'s getWpFindings()
            // on the client side: handles a bare-string legacy row, or
            // `findings` arriving as a JSON string rather than an
            // already-decoded array.
            $raw = $wp->findings ?? [];
            if (is_string($raw)) {
                $decoded = json_decode($raw, true);
                $raw = is_array($decoded) ? $decoded : [];
            }

            $findings = collect($raw)->map(function ($f) {
                if (is_string($f)) {
                    return ['title' => $f, 'risk_level' => null];
                }
                $arr = is_array($f) ? $f : (array) $f;
                return ['title' => $arr['title'] ?? '', 'risk_level' => $arr['risk_level'] ?? null];
            })->filter(fn ($f) => trim($f['title']) !== '');

            $already = $existingByWp[$wp->wp_id] ?? collect();

            foreach ($findings as $f) {
                $matched = $already->first(
                    fn ($ef) => strtolower(trim($ef->finding_title)) === strtolower(trim($f['title']))
                );

                $items->push([
                    'wp_id'            => $wp->wp_id,
                    'wp_no'            => $wp->working_paper_no,
                    'audit_area'       => $wp->audit_area,
                    'title'            => $f['title'],
                    'risk_level'       => $f['risk_level'],
                    'risk_level_label' => $riskLevelMap[$f['risk_level']] ?? null,
                    'already_input'    => (bool) $matched,
                    'finding'          => $matched,
                ]);
            }
        }

        return $items;
    }

    private function renderForm(Request $request, $data = [])
    {
        // FIX (per request): the Audit Plan dropdown on this form should
        // only offer finalized plans. A lightweight peek at finding_id
        // here (before the full $finding lookup below) lets an existing
        // finding's own plan stay in the list even if that plan somehow
        // isn't finalized — otherwise editing a finding under a
        // non-finalized plan would silently lose its own plan from the
        // dropdown and break plan resolution below.
        $requestedFindingId = ($request->input('form') === 'edit') ? $request->input('finding_id') : null;
        $currentFindingPlanId = $requestedFindingId ? optional(AuditFindings::find($requestedFindingId))->plan_id : null;

        $plans = AuditPlanning::whereNull('deleted_at')
            ->where(function ($q) use ($currentFindingPlanId) {
                // AuditPlanning.is_final is an integer column (not a
                // boolean cast), so compare against 1 explicitly.
                $q->where('is_final', 1);
                if ($currentFindingPlanId) {
                    $q->orWhere('plan_id', $currentFindingPlanId);
                }
            })
            ->orderByDesc('plan_start')
            ->get();

        $planId = $request->input('plan_id') ?: optional($plans->first())->plan_id;
        $plan   = $plans->firstWhere('plan_id', $planId);

        $finding     = null;
        $attachments = collect();
        $canEdit     = true;

        if ($request->input('form') === 'edit' && $request->filled('finding_id')) {
            $finding = AuditFindings::find($request->input('finding_id'));
            if ($finding) {
                // FIX (same IDOR class as the Working Paper module): confirm
                // the finding actually belongs to the plan_id in the URL
                // before letting anyone open it for editing.
                if ((int) $finding->plan_id !== (int) $planId) {
                    abort(404);
                }
                $canEdit = Auth::check() ? Auth::user()->can('update', $finding) : false;
                try {
                    $attachments = AuditFindingAttachment::where('finding_id', $finding->finding_id)->get();
                } catch (\Throwable $e) {
                    // attachments table not present
                }
            } else {
                abort(404);
            }
        } else {
            $canEdit = Auth::check() ? Auth::user()->can('create', AuditFindings::class) : false;
        }

        // Only working papers with an actual exception recorded (Exception
        // Note = "Yes" on the working paper) are relevant to link a
        // Finding to — a WP with no exception has nothing to "find". The
        // currently-linked wp_id is always included too, even if its
        // test_result has since changed, so opening an existing finding
        // for edit never silently drops its own linked working paper from
        // the list. findings/test_result are sent along so the create
        // form can auto-fill Finding Title / Risk Level from whatever the
        // working paper itself already recorded.
        $workingPapers = Working_paperIndex::whereNull('deleted_at')
            ->where(function ($q) use ($finding) {
                $q->where('test_result', 'Yes');
                if ($finding && $finding->wp_id) {
                    $q->orWhere('wp_id', $finding->wp_id);
                }
            })
            ->orderBy('working_paper_no')
            ->get(['wp_id', 'plan_id', 'working_paper_no', 'audit_area', 'test_result', 'findings', 'checklist_item']);

        // FIX (de-dup safeguard): some working papers' `findings` JSON
        // column have accumulated duplicate entries (root cause still
        // being tracked down — see the double-encoding note on
        // Working_paperIndexController::store(), which manually
        // json_encode()'d a value that may also be running through the
        // model's own array/json cast, encoding it twice on every save).
        // Collapse to unique titles (case-insensitive, trimmed) here so
        // the Add Finding screen's "which finding?" picker never shows
        // the same finding more than once per working paper, regardless
        // of what's actually sitting in the database right now.
        $workingPapers->each(function ($wp) {
            $seen   = [];
            $unique = [];
            foreach ((array) ($wp->findings ?? []) as $f) {
                $title = is_array($f) ? ($f['title'] ?? '') : (is_object($f) ? ($f->title ?? '') : (string) $f);
                $key = strtolower(trim($title));
                if ($key === '' || isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $unique[]   = $f;
            }
            $wp->findings = $unique;
        });

        $riskLevels   = DB::table('tbl_select_list')->where('type', 'RISK_LEVEL')->orderBy('sl_id')->get(['sl_id', 'name']);
        $findingsclas = DB::table('tbl_select_list')->where('type', 'FINDING_CLASSIFICATION')->orderBy('sl_id')->get(['sl_id', 'name']);

        $auditAreas = AuditFindings::whereNull('deleted_at')->whereNotNull('audit_area')->distinct()->pluck('audit_area')
            ->merge(Working_paperIndex::whereNull('deleted_at')->whereNotNull('audit_area')->distinct()->pluck('audit_area'))
            ->unique()->sort()->values();

        $auditors = DB::table('tbl_users')->where('is_active', 1)->orderBy('name')->get(['id', 'name']);


        $plansJson = $plans->keyBy('plan_id')->map(function ($p) {
            return [
                'plan_code'  => $p->plan_code,
                'plan_name'  => $p->plan_name,
                'plan_start' => !empty($p->plan_start) ? \Carbon\Carbon::parse($p->plan_start)->format('d M Y') : '',
                'plan_end'   => !empty($p->plan_end) ? \Carbon\Carbon::parse($p->plan_end)->format('d M Y') : '',
            ];
        })->toArray();

        $canFinalize = Auth::check() ? Auth::user()->can('finalize', $finding ?? new AuditFindings()) : false;

        // Two-stage finalize permission flags — same idea as $canFinalize
        // above, split by stage. On create (no $finding yet), a real
        // AuditFindings instance can't carry a meaningful created_by /
        // is_final_auditor yet, so "can finalize as auditor" collapses
        // to "can create at all" (matches the historical $canFinalize
        // behavior on create, where anyone allowed to create the record
        // could also immediately self-certify it). The reviewer stage is
        // never offered on create — stage 1 must exist first.
        $canFinalizeAsAuditor = Auth::check()
            ? ($finding
                ? Auth::user()->can('finalizeAsAuditor', $finding)
                : Auth::user()->can('create', AuditFindings::class))
            : false;
        $canFinalizeAsReviewer = ($finding && Auth::check())
            ? Auth::user()->can('finalizeAsReviewer', $finding)
            : false;

        // Assigned-reviewer picker for the "Reviewed By" field. Pulled
        // from the same tbl_users table $userNames uses elsewhere in
        // this controller. NOTE: this intentionally isn't filtered down
        // to reviewer-capable roles only (e.g. AuditFindingsPolicy's own
        // ELEVATED_ROLES) — if the app has a users-by-role query
        // available (the same source Working_paperIndexController uses
        // for its own reviewer picker, if it has one), swap it in here
        // so the dropdown only lists people who can actually act as
        // reviewer.
        $reviewers = DB::table('tbl_users')->orderBy('name')->get(['id', 'name']);

        // Prefill from the "select a WP finding to input" step
        // (renderPlanFindings() above) — only applies when creating a
        // brand-new finding; an existing finding's own saved values
        // always win, same as every other field on this form.
        $prefillWpId    = null;
        $prefillTitle   = null;
        $prefillRisk    = null;
        if (!$finding && $request->filled('wp_id')) {
            $prefillWpId  = $request->input('wp_id');
            $prefillTitle = $request->input('prefill_title');
            $prefillRisk  = $request->input('prefill_risk');
        }

        // "Next Finding" sidebar card — same idea as Working Paper's
        // "Next Working Paper" card: after inputting one WP-recorded
        // finding, offer a one-click way to jump straight to the next
        // one still pending in this plan, instead of going back to the
        // index and picking again. Skips whichever item the current
        // form itself is already working on (matched by wp_id + title),
        // so it never points at itself.
        $nextPendingFinding = null;
        if ($planId) {
            $currentWpId  = $prefillWpId ?? ($finding->wp_id ?? null);
            $currentTitle = $prefillTitle ?? ($finding->finding_title ?? null);

            $pending = $this->buildWpFindingItems($planId)
                ->filter(fn ($i) => !$i['already_input'])
                ->values();

            $nextPendingFinding = $pending->first(function ($i) use ($currentWpId, $currentTitle) {
                return !(
                    $currentWpId !== null
                    && (string) $i['wp_id'] === (string) $currentWpId
                    && strtolower(trim($i['title'])) === strtolower(trim((string) $currentTitle))
                );
            });
        }

        // Recent Activity sidebar — every save already gets logged to
        // AuditFindingsRevision via logRevision() (create/update/finalize/
        // delete/attachment_delete), but nothing was ever querying it back
        // out for display. Pull the last 5, newest first, and turn the raw
        // action + performed_by into a readable line.
        $recentActivity = collect();
        if ($finding) {
            $actionLabels = [
                'create'            => 'Finding created',
                'update'            => 'Finding updated',
                'delete'            => 'Finding deleted',
                'attachment_delete' => 'Attachment removed',
                'finalize_auditor'  => 'Finalized as Auditor',
                'finalize_reviewer' => 'Finalized as Reviewer',
                'finalize'          => 'Finalized',
            ];
            $revisions = AuditFindingsRevision::where('finding_id', $finding->finding_id)
                ->orderByDesc('performed_at')
                ->limit(5)
                ->get();
            $revUserIds = $revisions->pluck('performed_by')->filter()->unique();
            $revUserNames = $revUserIds->isEmpty()
                ? collect()
                : DB::table('tbl_users')->whereIn('id', $revUserIds)->pluck('name', 'id');
            $recentActivity = $revisions->map(function ($rev) use ($actionLabels, $revUserNames) {
                $label = $actionLabels[$rev->action] ?? ucfirst(str_replace('_', ' ', $rev->action));
                $who   = $revUserNames->get($rev->performed_by) ?? 'Unknown user';
                return [
                    'description' => "{$label} by {$who}",
                    'created_at'  => $rev->performed_at
                        ? \Carbon\Carbon::parse($rev->performed_at)->format('d M Y, g:i A')
                        : '',
                ];
            });
        }

        // Repeat Finding — findings from earlier plans in the same audit
        // area, so the "Repeat Finding" tick box can offer a real prior
        // finding to link back to. Scoped here to just "other plans" +
        // "not itself"; the same-audit-area scoping is applied client-side
        // instead (see previousFindingsAll / filterRepeatFindingOptions()
        // in the view).
        //
        // FIX: this used to also filter by `audit_area` server-side via
        // ->when($finding && $finding->audit_area, ...). That condition is
        // never true when creating a brand-new finding ($finding is null),
        // which is the main case for ticking "Repeat Finding" — and
        // audit_area isn't even known server-side at that point: it's a
        // readonly input the page only fills in client-side once a
        // Working Paper is picked (see f_audit_area / onWpChange() in the
        // view). So the area filter silently never ran on create, and on
        // edit it could just as easily zero out the list for a legitimate
        // area that simply has no other matches recorded server-side yet.
        // Sending the full cross-plan candidate list (still capped, still
        // excluding self/current plan) and filtering it in JS whenever the
        // area field actually gets a value fixes both: the list is
        // properly area-scoped on create, and it's not silently empty on
        // edit for reasons the user can't see.
        $previousFindings = collect();
        if ($planId) {
            $previousFindings = AuditFindings::whereNull('deleted_at')
                ->where('plan_id', '!=', $planId)
                ->when($finding, fn ($q) => $q->where('finding_id', '!=', $finding->finding_id))
                ->orderByDesc('finding_date')
                ->limit(200)
                ->get(['finding_id', 'finding_no', 'finding_title', 'audit_area', 'finding_date', 'plan_id']);
        }

        return view('audit_findings.create', array_merge($data, [
            'title'          => $finding ? 'Edit Finding' : 'Add Finding',
            'mode'           => $finding ? 'edit' : 'create',
            'plans'          => $plans,
            'plansJson'      => $plansJson,
            'plan'           => $plan,
            'planId'         => $planId,
            'finding'        => $finding,
            'attachments'    => $attachments,
            'workingPapersJson' => $workingPapers->groupBy('plan_id'),
            'riskLevels'     => $riskLevels,
            'findingsclas'   => $findingsclas,
            'auditAreas'     => $auditAreas,
            'canEdit'        => $canEdit,
            'canFinalize'    => $canFinalize,
            'canFinalizeAsAuditor'  => $canFinalizeAsAuditor,
            'canFinalizeAsReviewer' => $canFinalizeAsReviewer,
            'reviewers'      => $reviewers,
            'prefillWpId'    => $prefillWpId,
            'prefillTitle'   => $prefillTitle,
            'prefillRisk'    => $prefillRisk,
            'nextPendingFinding' => $nextPendingFinding,
            'auditors'       => $auditors,
            'recentActivity' => $recentActivity,
            'previousFindings' => $previousFindings,
        ]));
    }

    public function store(Request $request, $data = [])
    {
        $id     = $request->input('finding_id');
        $planId = $request->input('plan_id');
        $now    = now()->format('Y-m-d H:i:s');

        // FIX (same IDOR class as the Working Paper module): every
        // mutating action below must confirm the finding actually belongs
        // to the plan_id in the request before acting on it.
        $existing = $id ? AuditFindings::find($id) : null;
        if ($id && (!$existing || (int) $existing->plan_id !== (int) $planId)) {
            abort(404);
        }

        // Once a finding is finalized, block delete / save / finalize /
        // attachment changes on it entirely.
        if ($id && $existing && $existing->isFinal() && in_array($request->input('action'), ['delete', 'save', 'finalize', 'finalize_auditor', 'finalize_reviewer', 'delete_attachment'])) {
            return redirect()->back()->with(
                'error',
                'This finding was finalized on ' . optional($existing->finalized_date)->format('d M Y')
                    . ' and can no longer be edited, deleted, or have its attachments changed.'
            );
        }

        if ($request->input('action') === 'delete') {
            $this->authorize('delete', $existing);
            AuditFindings::where('finding_id', $id)->update(['deleted_at' => $now, 'deleted_by' => Auth::id()]);
            $this->logRevision($id, $planId, 'delete', [], ['deleted_by' => Auth::id(), 'deleted_at' => $now]);
            return redirect(url(request()->path()) . '?plan_id=' . $planId)->with('success', 'Finding deleted.');
        }

        if ($request->input('action') === 'delete_attachment') {
            $this->authorize('manageAttachments', $existing);
            $warning = null;
            try {
                // FIX (same IDOR class as the Working Paper module): confirm
                // the attachment actually belongs to the finding_id in the
                // request before deleting it.
                $att = AuditFindingAttachment::where('att_id', $request->input('att_id'))
                    ->where('finding_id', $id)
                    ->first();
                if ($att) {
                    $att->delete();
                    $this->logRevision($id, $planId, 'attachment_delete', [], ['file_name' => $att->file_name]);
                } else {
                    $warning = 'Attachment not found or does not belong to this finding.';
                }
            } catch (\Throwable $e) {
                $warning = 'Could not remove that attachment — please try again.';
            }
            return redirect(url(request()->path()) . '?plan_id=' . $planId . '&form=edit&finding_id=' . $id)
                ->with($warning ? 'error' : 'success', $warning ?: 'Attachment removed.');
        }

        $saveData = ['plan_id' => $planId];

        $formFields = [
            'wp_id', 'audit_area', 'risk_level', 'findingsclas', 'finding_title',
            'finding_condition', 'criteria', 'cause', 'impact', 'recommendation',
            'responsible_person', 'target_date', 'finding_date', 'reviewed_by',
            // Allow the form to submit an explicit "Input By" value
            'created_by',
            // Repeat Finding — tick box + which prior finding it repeats
            'is_repeat_finding', 'repeat_of_finding_id',
        ];
        foreach ($formFields as $f) {
            if ($request->has($f)) {
                $value = $request->input($f);
                $saveData[$f] = ($value === '') ? null : $value;
            }
        }

        // The tick box only sends "1" when checked (unchecked checkboxes
        // don't submit at all), so normalize to a real boolean either way
        // rather than leaving it unset on an unchecked save.
        $saveData['is_repeat_finding'] = $request->boolean('is_repeat_finding');
        if (!$saveData['is_repeat_finding']) {
            // Ticked off (or never ticked) -> always clear the link too,
            // so a stale repeat_of_finding_id can't linger once the user
            // unchecks the box.
            $saveData['repeat_of_finding_id'] = null;
        } elseif (!empty($saveData['repeat_of_finding_id'])) {
            // Ticked on with a selection -> confirm that finding actually
            // exists before saving the reference.
            $repeatExists = AuditFindings::whereNull('deleted_at')
                ->where('finding_id', $saveData['repeat_of_finding_id'])
                ->exists();
            if (!$repeatExists) {
                return redirect()->back()->withInput()->with('error', 'Selected repeat finding was not found.');
            }
        }

        // If created_by was provided, validate that the selected user exists.
        if (array_key_exists('created_by', $saveData) && $saveData['created_by'] !== null) {
            $createdByVal = $saveData['created_by'];
            $existsUser = DB::table('tbl_users')->where('id', $createdByVal)->exists();
            if (! $existsUser) {
                return redirect()->back()->withInput()->with('error', 'Selected "Input By" user was not found.');
            }
            // cast to int for consistency
            $saveData['created_by'] = (int) $createdByVal;
        }

        // FIX (root cause of the "0 High/Medium Risk" stat-card bug): the
        // create/edit form marks risk_level, findingsclas, finding_date,
        // finding_title, finding_condition, and recommendation as
        // `required` — but that was only ever a browser-side hint.
        // Nothing server-side enforced it, so a bypassed or malformed
        // submission could still save a finding with, say, risk_level
        // genuinely null. That's exactly what silently zeroed out the
        // High/Medium/Low buckets on the index page even though Total
        // Findings still counted the row — the buckets simply had
        // nothing to match. Enforced here on every save (not only
        // finalize) so a finding can never again reach the database
        // missing one of these.
        $alwaysRequiredFields = [
            'plan_id'           => 'Audit Plan',
            'risk_level'        => 'Risk Level',
            'findingsclas'      => 'Classification',
            'finding_date'      => 'Finding Date',
            'finding_title'     => 'Finding Title',
            'finding_condition' => 'Finding / Condition',
            'recommendation'    => 'Recommendation',
        ];
        $missingRequired = [];
        foreach ($alwaysRequiredFields as $field => $label) {
            $value = array_key_exists($field, $saveData) ? $saveData[$field] : ($existing->{$field} ?? null);
            if (trim((string) $value) === '') {
                $missingRequired[] = $label;
            }
        }
        if ($missingRequired) {
            return redirect()->back()->withInput()->with(
                'error',
                'Cannot save — please complete the following required field(s): ' . implode(', ', $missingRequired) . '.'
            );
        }

        // FIX: audit_area is optional in the UI (no `required` attribute)
        // but tbl_ho_audit_findings.audit_area is a NOT NULL column
        // (SQLSTATE[23502] if it's ever actually null). Rather than let
        // that surface as a 500 error, fall back to a placeholder — but
        // only when creating, or when the field was actually submitted
        // empty, so an update where audit_area simply isn't in the
        // request doesn't clobber an existing value.
        if ((!$id || $request->has('audit_area')) && empty($saveData['audit_area'] ?? null)) {
            $saveData['audit_area'] = 'Unspecified';
        }

        // Finding No. — editable; blank auto-generates "F-<year>-001" style,
        // scoped per plan (uniqueness is per plan_id, same convention as
        // Working Paper numbers).
        $requested = trim((string) $request->input('finding_no'));
        $finalNo   = $requested !== '' ? $requested : $this->nextFindingNo($planId);
        if ($this->findingNoTakenInPlan($planId, $finalNo, $id)) {
            $finalNo = $this->nextFindingNo($planId);
        }
        $saveData['finding_no'] = $finalNo;

        // Two-stage finalize — mirrors Working_paperIndexController::store().
        // 'finalize' (no suffix) is the legacy single-stage action, kept
        // working for any caller still using it, but the create/edit
        // form itself now only ever sends 'finalize_auditor' or
        // 'finalize_reviewer'.
        $action                = $request->input('action');
        $isFinalizingAuditor   = $action === 'finalize_auditor';
        $isFinalizingReviewer  = $action === 'finalize_reviewer';
        $isFinalizingLegacy    = $action === 'finalize';
        $isFinalizing          = $isFinalizingAuditor || $isFinalizingReviewer || $isFinalizingLegacy;

        if ($isFinalizingAuditor) {
            $saveData['is_final_auditor']       = true;
            $saveData['finalized_by_auditor']   = Auth::id();
            $saveData['finalized_date_auditor'] = $now;
        } elseif ($isFinalizingReviewer) {
            $saveData['is_final_reviewer']       = true;
            $saveData['finalized_by_reviewer']   = Auth::id();
            $saveData['finalized_date_reviewer'] = $now;
            // Reviewer sign-off is the last stage — also set the legacy/
            // overall lock fields so isFinal() (and anything still
            // reading is_final directly, e.g. the "Finalized" banner)
            // reflects "both stages complete", same relationship
            // Working_paperIndex.is_final has to its own two stages.
            $saveData['is_final']       = true;
            $saveData['finalized_by']   = Auth::id();
            $saveData['finalized_date'] = $now;
        } elseif ($isFinalizingLegacy) {
            $saveData['is_final']       = true;
            $saveData['finalized_by']   = Auth::id();
            $saveData['finalized_date'] = $now;
        }

        if ($id) {
            $ability = $isFinalizingAuditor ? 'finalizeAsAuditor'
                : ($isFinalizingReviewer ? 'finalizeAsReviewer'
                : ($isFinalizingLegacy ? 'finalize' : 'update'));
            $this->authorize($ability, $existing);
        } else {
            $this->authorize('create', AuditFindings::class);
        }

        // Finalizing (as Auditor, or the legacy single-stage finalize)
        // requires the finding to actually be complete — same field set
        // the "Completion Progress" sidebar on the form already checks
        // (General Information + Finding Details + Recommendation;
        // Attachments stays optional). Checked against $saveData/
        // $existing rather than the raw request, so audit_area's
        // NOT-NULL fallback and finding_no's auto-generated value (both
        // applied above) count as filled, exactly like the sidebar's own
        // !empty($finding->field) checks do post-save.
        if ($isFinalizingAuditor || $isFinalizingLegacy) {
            $completionFields = [
                'plan_id'           => 'Audit Plan',
                'audit_area'        => 'Audit Area / Process',
                'finding_no'        => 'Finding No.',
                'risk_level'        => 'Risk Level',
                'findingsclas'      => 'Classification',
                'finding_date'      => 'Finding Date',
                'finding_title'     => 'Finding Title',
                'finding_condition' => 'Finding / Condition',
                'criteria'          => 'Criteria',
                'cause'             => 'Cause',
                'impact'            => 'Impact',
                'recommendation'    => 'Recommendation',
            ];

            $missingLabels = [];
            foreach ($completionFields as $field => $label) {
                $value = array_key_exists($field, $saveData) ? $saveData[$field] : ($existing->{$field} ?? null);
                if (trim((string) $value) === '') {
                    $missingLabels[] = $label;
                }
            }

            if ($missingLabels) {
                return redirect()->back()->withInput()->with(
                    'error',
                    'Cannot finalize — please complete the following field(s) first: ' . implode(', ', $missingLabels) . '.'
                );
            }
        }

        $finalizeActionLabel = $isFinalizingAuditor ? 'finalize_auditor'
            : ($isFinalizingReviewer ? 'finalize_reviewer'
            : ($isFinalizingLegacy ? 'finalize' : null));

        $finalizeMsg = $isFinalizingAuditor
            ? 'Finding finalized as Auditor. Awaiting reviewer sign-off.'
            : ($isFinalizingReviewer
                ? 'Finding finalized by Reviewer. It can no longer be edited.'
                : 'Finding finalized. It can no longer be edited.');

        if ($id) {
            $saveData['modified_by']   = Auth::id();
            $saveData['modified_date'] = $now;
            $before = $existing->toArray();
            AuditFindings::where('finding_id', $id)->update($saveData);
            $this->logRevision($id, $planId, $finalizeActionLabel ?? 'update', $this->diff($before, $saveData), $saveData);
            $msg = $isFinalizing ? $finalizeMsg : 'Finding updated successfully.';
        } else {
            // If created_by was not provided in the form, default to the current user
            if (!array_key_exists('created_by', $saveData) || empty($saveData['created_by'])) {
                $saveData['created_by'] = Auth::id();
            }
            $saveData['created_date'] = $now;
            $finding = AuditFindings::create($saveData);
            $id      = $finding->finding_id;
            $this->logRevision($id, $planId, $finalizeActionLabel ?? 'create', [], $saveData);
            $msg     = $isFinalizing ? ('Finding created and finalized' . ($isFinalizingAuditor ? ' as Auditor. Awaiting reviewer sign-off.' : '.')) : 'Finding created successfully.';
        }

        if ($request->hasFile('attachments')) {
            try {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('findings', 'public');
                    AuditFindingAttachment::create([
                        'finding_id' => $id,
                        'file_name'  => $file->getClientOriginalName(),
                        'file_path'  => $path,
                        'file_size'  => $file->getSize(),
                        'add_date'   => $now,
                        'add_by'     => Auth::id(),
                    ]);
                }
            } catch (\Throwable $e) {
                // attachments table/storage not configured yet — skip silently
            }
        }

        return redirect(url(request()->path()) . '?plan_id=' . $planId)->with('success', $msg);
    }

    /**
     * Append-only audit trail entry — who changed what, and when. Never
     * update/delete rows in this table; write a new entry to undo.
     */
    private function logRevision($findingId, $planId, string $action, array $changes = [], array $snapshot = []): void
    {
        try {
            AuditFindingsRevision::create([
                'finding_id'   => $findingId,
                'plan_id'      => $planId,
                'action'       => $action,
                'changes'      => $changes ?: null,
                'snapshot'     => $snapshot ?: null,
                'performed_by' => Auth::id(),
                'performed_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('audit_findings revision log failed', [
                'finding_id' => $findingId, 'action' => $action, 'error' => $e->getMessage(),
            ]);
        }
    }

    private function diff(array $old, array $new): array
    {
        $changes = [];
        foreach ($new as $key => $newVal) {
            $oldVal = $old[$key] ?? null;
            if ($oldVal != $newVal) {
                if (is_null($oldVal) && $newVal === '') continue;
                $changes[$key] = ['old' => $oldVal, 'new' => $newVal];
            }
        }
        return $changes;
    }

    private function nextFindingNo($planId): string
    {
        $year = now()->format('Y');
        $max  = 0;
        AuditFindings::where('plan_id', $planId)
            ->where('finding_no', 'like', "F-{$year}-%")
            ->pluck('finding_no')
            ->each(function ($no) use (&$max, $year) {
                if (preg_match('/^F-' . $year . '-(\d+)$/', $no, $m)) {
                    $max = max($max, (int) $m[1]);
                }
            });

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $candidate = 'F-' . $year . '-' . str_pad($max + $attempt, 3, '0', STR_PAD_LEFT);
            if (!$this->findingNoTakenInPlan($planId, $candidate)) {
                return $candidate;
            }
        }
        return 'F-' . $year . '-' . str_pad($max + 1, 3, '0', STR_PAD_LEFT) . '-' . substr(uniqid(), -4);
    }

    private function findingNoTakenInPlan($planId, $code, $excludeId = null): bool
    {
        return AuditFindings::where('plan_id', $planId)
            ->where('finding_no', $code)
            ->when($excludeId, fn($q) => $q->where('finding_id', '!=', $excludeId))
            ->exists();
    }
}