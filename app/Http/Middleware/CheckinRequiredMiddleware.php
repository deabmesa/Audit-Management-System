<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckinRequiredMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('checked_in')) {
            return redirect()->route('checkin.form')->withErrors([
                'checkin' => 'You must check-in before accessing system modules.',
            ]);
        }

        return $next($request);
    }
}
