<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** The admin panel speaks the language each team member chose (Arabic by default). */
class SetAdminLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        app()->setLocale($request->user()?->locale ?? $request->session()->get('admin_locale', 'ar'));

        return $next($request);
    }
}
