<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class DynamicController extends Controller
{
    public function handle($module, $page, Request $request)
    {
        // ================= FIND MENU =================
        $route = "$module/$page";
        $menu = Menu::where('route', $route)->first();

        if (!$menu) {
            return response("? Menu not found for route: {$route}", 404);
        }

        // ================= CONTROLLER =================
        if (empty($menu->controller)) {
            return response("? Controller missing for menu: {$menu->name}", 500);
        }

        if (!str_contains($menu->controller, '@')) {
            return response("? Invalid controller format: {$menu->controller}<br>Expected: Controller@method", 500);
        }

        [$controllerName, $method] = explode('@', $menu->controller);

        $controllerClass = "App\\Http\\Controllers\\{$controllerName}";

        if (!class_exists($controllerClass)) {
            return response("? Controller class not found: {$controllerClass}", 500);
        }

        if (!method_exists($controllerClass, $method)) {
            return response("? Method '{$method}' not found in {$controllerClass}", 500);
        }

        // ================= SERVICE =================
        $data = [];

        if (!empty($menu->service)) {

            if (!str_contains($menu->service, '@')) {
                return response("? Invalid service format: {$menu->service}<br>Expected: Service@method", 500);
            }

            [$serviceName, $serviceMethod] = explode('@', $menu->service);

            $serviceClass = "App\\Services\\{$serviceName}";

            if (!class_exists($serviceClass)) {
                return response("? Service class not found: {$serviceClass}", 500);
            }

            if (!method_exists($serviceClass, $serviceMethod)) {
                return response("? Method '{$serviceMethod}' not found in {$serviceClass}", 500);
            }

            try {
                $service = app($serviceClass);
                $data = $service->$serviceMethod($request);
            } catch (\Throwable $e) {
                return response("? Service execution error:<br>".$e->getMessage(), 500);
            }
        }

        // ================= EXECUTE CONTROLLER =================
        try {
            return app($controllerClass)->$method($request, $data);
        } catch (\Throwable $e) {
            return response("? Controller execution error:<br>".$e->getMessage(), 500);
        }
    }
}