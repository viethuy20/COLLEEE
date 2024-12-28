<?php

namespace App\Http\Middleware;

use Closure;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // APIキーを取得する
        $api_key = $request->header('X-API-KEY');

        // APIキーが正しいかどうかを検証する
        if ($api_key !== config('app.apiKey')) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        return $next($request);
    }
}
