<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Audit;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function storeForAudit(Request $request, Audit $audit)
    {
        $this->save($request, $audit);
        return back()->with('success', 'Attachment uploaded for audit.');
    }

    public function storeForIssue(Request $request, Issue $issue)
    {
        $this->save($request, $issue);
        return back()->with('success', 'Attachment uploaded for issue.');
    }

    public function download(Attachment $attachment)
    {
        Gate::authorize('view', $attachment->attachable);
        return Storage::disk('public')->download($attachment->path, $attachment->original_name);
    }

    private function save(Request $request, $attachable): void
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xlsx,csv,png,jpg,jpeg|max:5120',
        ]);

        $file = $validated['file'];
        $path = $file->store('attachments', 'public');

        $attachable->attachments()->create([
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
        ]);
    }
}
