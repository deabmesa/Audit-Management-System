<?php

namespace App\Http\Controllers;

use App\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(private readonly MenuService $menuService)
    {
    }

    public function index()
    {
        return view('menus.index', ['menus' => $this->menuService->tree()]);
    }

    public function store(Request $request)
    {
        $request->validate(['tree' => ['required', 'array']]);
        $this->menuService->saveTree($request->tree);
        return back()->with('success', 'Menu tree saved.');
    }
}
