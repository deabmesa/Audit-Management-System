<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Exception;

class MenuController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $menus = Menu::with('children.children.children.children')
            ->whereNull('parent_id')
            ->orderBy('order_no')
            ->get();

        return view('menus.index', compact('menus'));
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        $menus = Menu::with('childrenRecursive')
            ->whereNull('parent_id')
            ->orderBy('order_no')
            ->get();

        $editMenu = Menu::find($request->edit_id);

        return view('menus.create', compact('menus', 'editMenu'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        try {

            // ✅ Validate
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $module = strtolower(trim($request->module ?? ''));
            $page   = strtolower(trim($request->page ?? ''));

            $route = null;
            $controller = null;
            $service = null;

            // =========================
            // MODULE + PAGE LOGIC
            // =========================
            if (!empty($module) && !empty($page)) {

                $route = "{$module}/{$page}";

                // prevent duplicate route
                if (Menu::where('route', $route)->exists()) {
                    return back()->withErrors(['route' => 'Route already exists']);
                }

                $controllerName = ucfirst($module) . ucfirst($page) . 'Controller';
                $serviceName    = ucfirst($module) . ucfirst($page) . 'Service';

                $controller = $controllerName . '@index';
                $service    = $serviceName . '@getData';

                // Generate files safely
                $this->generateController($controllerName, $module, $page);
                $this->generateService($serviceName);
                $this->generateView($module, $page, $request->name);
            }

            // =========================
            // SAVE MENU (IMPORTANT FIX AREA)
            // =========================
            $menu = Menu::create([
                'name'       => $request->name,
                'route'      => $route,
                'controller' => $controller,
                'service'    => $service,
                'icon'       => $request->icon,
                'permission' => $request->permission ?: null,
                'parent_id'  => $request->parent_id,
                'order_no'   => $request->order_no,
            ]);

            return back()->with('success', 'Menu created successfully!');

        } catch (Exception $e) {

            // log real error
            Log::error('Menu store failed: ' . $e->getMessage());

            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        try {

            $menu = Menu::findOrFail($id);

            $menu->update([
                'name'       => $request->name,
                'icon'       => $request->icon,
                'permission' => $request->permission ?: null,
                'parent_id'  => $request->parent_id,
                'order_no'   => $request->order_no,
            ]);

            return redirect()->route('menus.index')->with('success', 'Menu updated!');

        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            Menu::findOrFail($id)->delete();
            return back()->with('success', 'Menu deleted!');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROLLER GENERATOR
    |--------------------------------------------------------------------------
    */
    private function generateController($name, $module, $page)
    {
        $path = app_path("Http/Controllers/{$name}.php");

        if (File::exists($path)) return;

        $content = <<<PHP
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class {$name} extends Controller
{
    public function index(Request \$request, \$data = [])
    {
        return view('{$module}.{$page}', \$data);
    }
}
PHP;

        File::put($path, $content);
    }

    /*
    |--------------------------------------------------------------------------
    | SERVICE GENERATOR
    |--------------------------------------------------------------------------
    */
    private function generateService($name)
    {
        $dir = app_path('Services');

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $path = "{$dir}/{$name}.php";

        if (File::exists($path)) return;

        $content = <<<PHP
<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class {$name}
{
    public function getData(\$request)
    {
        return [
            'data' => DB::select("SELECT 1 as sample")
        ];
    }
}
PHP;

        File::put($path, $content);
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW GENERATOR
    |--------------------------------------------------------------------------
    */
    private function generateView($module, $page, $title)
    {
        $dir = resource_path("views/{$module}");

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $path = "{$dir}/{$page}.blade.php";

        if (File::exists($path)) return;

        $content = <<<BLADE
@extends('layouts.app')

@section('content')

<h4>{$title}</h4>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        @foreach(\$data ?? [] as \$row)
        <tr>
            <td>{{ json_encode(\$row) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
BLADE;

        File::put($path, $content);
    }
}