<?php

use Illuminate\Support\Facades\Route;
use App\Models\Menu;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuditEngagementController;
use App\Http\Controllers\AuditFieldworkController;
use App\Http\Controllers\AuditFindingController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DynamicController; // ✅ IMPORTANT


/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check()
        ? redirect('/dashboard')
        : redirect('/login');
});


/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE
    |--------------------------------------------------------------------------
    */
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');


    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */
    Route::resource('users', UserController::class)->except('show');
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('user-activity-logs', [UserController::class, 'logs'])->name('users.logs');


    /*
    |--------------------------------------------------------------------------
    | MENUS (FOR ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::resource('menus', MenuController::class);


    /*
    |--------------------------------------------------------------------------
    | ROLE MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::get('/roles', function () {

        if (auth()->user()->role !== 'Admin') {
            abort(403);
        }

        return app(RoleController::class)->index(request());

    })->name('roles.index');

    Route::post('/roles/update-role', function (\Illuminate\Http\Request $request) {

        if (auth()->user()->role !== 'Admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return app(RoleController::class)->updateRole($request);

    })->name('roles.updateRole');


    /*
    |--------------------------------------------------------------------------
    | AUDIT MODULE
    |--------------------------------------------------------------------------
    */
    Route::resource('audits', AuditEngagementController::class)
        ->only(['index', 'create', 'store', 'show']);

    Route::post('audits/{audit}/fieldwork', [AuditFieldworkController::class, 'store'])->name('fieldwork.store');
    Route::post('audits/{audit}/findings', [AuditFindingController::class, 'store'])->name('findings.store');
    Route::post('findings/{finding}/followups', [FollowUpController::class, 'store'])->name('followups.store');


    /*
    |--------------------------------------------------------------------------
    | 🔥 DYNAMIC MENU ENGINE (VERY IMPORTANT)
    |--------------------------------------------------------------------------
    | This replaces your old /menu/{route} and view-only logic
    | ✅ FIXED: Added proper route constraints to prevent invalid controller names
    */
    Route::get('/{module}/{page}', [DynamicController::class, 'handle'])
        ->where([
            'module' => '[A-Za-z]+',     // ✅ FIXED: Only letters (no numbers, dashes)
            'page'   => '[A-Za-z]+'      // ✅ FIXED: Only letters (no numbers, dashes)
        ])->name('dynamic.handle');

});


/*
|--------------------------------------------------------------------------
| EXTRA PERMISSION ROUTES (OPTIONAL)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'permission:manage_roles'])->group(function () {
    Route::resource('roles', RoleController::class);
});

Route::middleware(['auth', 'permission:manage_users'])->group(function () {
    Route::resource('users', UserController::class);
});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
