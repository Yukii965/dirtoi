<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->status === 'en_attente') {
            // Déconnecte l'utilisateur et affiche un message
            Auth::logout();
            return redirect()->route('login')
                ->with('error', '⏳ Votre compte est en attente de validation par notre équipe. Vous recevrez une confirmation sous 24h.');
        }

        if (Auth::check() && Auth::user()->status === 'suspendu') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', '🚫 Votre compte a été suspendu. Contactez support@gasymarket.mg');
        }

        return $next($request);
    }
}