<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;

class DynamicController extends Controller
{
    /**
     * ✅ FIX: Handle dynamic menu requests with proper validation and error handling
     */
    public function handle($module, $page, Request $request)
    {
        try {
            // ================= FIND MENU =================
            $route = "{$module}/{$page}";
            $menu = Menu::where('route', $route)->first();

            if (!$menu) {
                Log::warning("Menu not found for route: {$route}");
                return response()->view('errors.404', ['message' => "Menu not found: {$route}"], 404);
            }

            // ================= PERMISSION CHECK =================
            if ($menu->permission && !auth()->user()->hasPermission($menu->permission)) {
                Log::warning("Permission denied for user " . auth()->id() . " accessing {$route}");
                abort(403, 'Unauthorized access to this menu.');
            }

            // ================= CONTROLLER =================
            if (empty($menu->controller)) {
                Log::error("Controller missing for menu: {$menu->name}");
                return response()->view('errors.500', ['message' => "Controller configuration error"], 500);
            }

            if (!str_contains($menu->controller, '@')) {
                Log::error("Invalid controller format: {$menu->controller}");
                return response()->view('errors.500', ['message' => "Invalid controller format"], 500);
            }

            [$controllerName, $method] = explode('@', $menu->controller);

            $controllerClass = "App\\Http\\Controllers\\{$controllerName}";

            if (!class_exists($controllerClass)) {
                Log::error("Controller class not found: {$controllerClass}");
                return response()->view('errors.500', ['message' => "Controller not found"], 500);
            }

            if (!method_exists($controllerClass, $method)) {
                Log::error("Method '{$method}' not found in {$controllerClass}");
                return response()->view('errors.500', ['message' => "Method not found in controller"], 500);
            }

            // ================= SERVICE =================
            $data = [];

            if (!empty($menu->service)) {

                if (!str_contains($menu->service, '@')) {
                    Log::error("Invalid service format: {$menu->service}");
                    return response()->view('errors.500', ['message' => "Invalid service format"], 500);
                }

                [$serviceName, $serviceMethod] = explode('@', $menu->service);

                $serviceClass = "App\\Services\\{$serviceName}";

                if (!class_exists($serviceClass)) {
                    Log::error("Service class not found: {$serviceClass}");
                    return response()->view('errors.500', ['message' => "Service not found"], 500);
                }

                if (!method_exists($serviceClass, $serviceMethod)) {
                    Log::error("Method '{$serviceMethod}' not found in {$serviceClass}");
                    return response()->view('errors.500', ['message' => "Service method not found"], 500);
                }

                try {
                    $service = app($serviceClass);
                    $data = $service->$serviceMethod($request);
                } catch (\Throwable $e) {
                    Log::error("Service execution error: " . $e->getMessage(), [
                        'service' => $serviceClass,
                        'method' => $serviceMethod,
                        'trace' => $e->getTraceAsString(),
                    ]);
                    return response()->view('errors.500', ['message' => "Service execution error"], 500);
                }
            }

            // ================= EXECUTE CONTROLLER =================
            try {
                Log::info("Executing dynamic route: {$route}", [
                    'controller' => $controllerClass,
                    'method' => $method,
                    'user_id' => auth()->id(),
                ]);

                return app($controllerClass)->$method($request, $data);

            } catch (\Throwable $e) {
                Log::error("Controller execution error: " . $e->getMessage(), [
                    'controller' => $controllerClass,
                    'method' => $method,
                    'trace' => $e->getTraceAsString(),
                ]);
                return response()->view('errors.500', ['message' => "Controller execution error"], 500);
            }

        } catch (\Exception $e) {
            Log::error("Unexpected error in DynamicController: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->view('errors.500', ['message' => "An unexpected error occurred"], 500);
        }
    }
}
