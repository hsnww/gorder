<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->query('lang', session('lang', config('app.locale')));
        session(['lang' => $locale]);
        App::setLocale($locale);

        return $next($request);
    }
}
