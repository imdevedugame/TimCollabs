<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil locale dari session, atau gunakan default dari config/app.php
        $locale = session('locale', config('app.locale'));
        Log::info('Locale yang digunakan: ' . $locale);
        App::setLocale($locale);
        return $next($request);
    }
}
