<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // Affiche la page d'inscription
    public function create(): View
    {
        return view('auth.register');
    }

    // Traite le formulaire d'inscription quand l'utilisateur clique sur "S'inscrire"
    public function store(Request $request): RedirectResponse
    {
        $role = $request->role;

        $request->validate([
            'email'      => ['required', 'email', 'unique:users'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
            'role'       => ['required', 'in:acheteur,vendeur_amateur,vendeur_pro'],
            'last_name'  => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'phone'      => ['required', 'string'],
        ]);

        // Pour les particuliers → passer par la caméra d'abord
        if ($role === 'vendeur_amateur') {
            // Stocker les données en session
            session(['pending_registration' => [
                'name'         => $request->first_name . ' ' . $request->last_name,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'role'         => $role,
                'mvola_number' => $request->mvola_number,
                'company_name' => null,
            ]]);
            return redirect()->route('register.camera');
        }

        // Pour acheteurs et entreprises → créer directement
        $user = User::create([
            'name'         => $request->first_name . ' ' . $request->last_name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => $role,
            'mvola_number' => $request->mvola_number,
            'company_name' => $request->company_name,
            'status'       => 'en_attente',
        ]);

        event(new Registered($user));

        return redirect()->route('auth.pending')
            ->with('pending_name', $user->name)
            ->with('pending_email', $user->email);
    }
}