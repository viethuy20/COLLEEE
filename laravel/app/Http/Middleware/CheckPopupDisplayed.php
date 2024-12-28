<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPopupDisplayed
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::user()->id;
            $dontShowTodayKey = 'dont_show_today_' . $userId;
            $dontShowTodayCookie = $request->cookie($dontShowTodayKey);
            if (!$dontShowTodayCookie) {
                if (!session()->get('popup_displayed', false)) {
                    session(['show_popup' => true]);
                    session(['popup_displayed' => true]);

                } else {
                    session(['show_popup' => false]);

                }
            } else {
                session(['show_popup' => false]);
            }
        }

        return $next($request);
    }
}
