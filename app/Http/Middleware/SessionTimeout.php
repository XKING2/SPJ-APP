<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $timeout = 60 * 60 * 8; // 8 jam dalam detik
        $lastActivity = session('last_activity_time');

        if ($lastActivity && (time() - $lastActivity) > $timeout) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['session' => 'Sesi Anda telah berakhir. Silakan login kembali.']);
        }

        session(['last_activity_time' => time()]);
        return $next($request);
    }
}
