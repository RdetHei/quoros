<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isCurrentlyBanned()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = 'Your account has been suspended.';
            if ($user->ban_reason) {
                $message .= ' Reason: '.$user->ban_reason;
            }
            if ($user->banned_until && $user->banned_until->isFuture()) {
                $message .= ' Valid until '.$user->banned_until->format('d M Y H:i');
            }

            return redirect()->route('login')->with('error', $message);
        }

        return $next($request);
    }
}
