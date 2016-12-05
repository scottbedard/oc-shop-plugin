<?php namespace Bedard\Shop\Classes;

use Closure;
use Bedard\Shop\Models\ApiSettings;

class ApiMiddleware
{
    /**
     * Abort all requests when the HTTP API is not enabled.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return \Closure
     */
    public function handle($request, Closure $next)
    {
        if (! ApiSettings::isEnabled()) {
            abort(403, 'Forbidden');

            return;
        }

        return $next($request);
    }
}
