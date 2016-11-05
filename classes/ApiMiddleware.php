<?php namespace Bedard\Shop\Classes;

use Bedard\Shop\Models\ApiSettings;
use Closure;

class ApiMiddleware
{
    public function handle($request, Closure $next)
    {
        if (! ApiSettings::isEnabled()) {
            abort(403, 'Forbidden');
            return;
        }

        return $next($request);
    }
}
