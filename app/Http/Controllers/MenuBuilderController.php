<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuBuilderController extends Controller
{
    public function index(): View
    {
        $menuItems = MenuItem::orderBy('sort_order')->orderBy('id')->get();
        $roles = [User::ROLE_ADMIN, User::ROLE_AUDITOR, User::ROLE_REVIEWER];

        return view('admin.menu-builder', compact('menuItems', 'roles'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validatedData($request);

        $menuItem = MenuItem::create([
            ...$data,
            'sort_order' => (int) MenuItem::max('sort_order') + 1,
            'is_active' => $request->boolean('is_active', true),
            'roles' => $this->normalizedRoles($data['roles'] ?? null),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Menu item created.',
                'item' => $menuItem,
            ]);
        }

        return back()->with('success', 'Menu item created.');
    }

    public function update(Request $request, MenuItem $menuItem): JsonResponse|RedirectResponse
    {
        $data = $this->validatedData($request);

        $menuItem->update([
            ...$data,
            'is_active' => $request->boolean('is_active', false),
            'roles' => $this->normalizedRoles($data['roles'] ?? null),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Menu item updated.',
                'item' => $menuItem->fresh(),
            ]);
        }

        return back()->with('success', 'Menu item updated.');
    }

    public function destroy(Request $request, MenuItem $menuItem): JsonResponse|RedirectResponse
    {
        $menuItem->delete();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Menu item deleted.']);
        }

        return back()->with('success', 'Menu item deleted.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $ids = $request->validate([
            'ordered_ids' => ['required', 'array'],
            'ordered_ids.*' => ['integer', 'exists:menu_items,id'],
        ])['ordered_ids'];

        foreach ($ids as $index => $id) {
            MenuItem::whereKey($id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['message' => 'Menu order updated.']);
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'route_name' => ['nullable', 'string', 'max:120'],
            'url' => ['nullable', 'string', 'max:255'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['in:Admin,Auditor,Reviewer'],
        ]);
    }

    private function normalizedRoles(?array $roles): ?array
    {
        return empty($roles) ? null : array_values(array_unique($roles));
    }
}
