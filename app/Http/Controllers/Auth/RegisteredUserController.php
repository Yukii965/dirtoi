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
        // Validation de tous les champs du formulaire
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            // Le rôle doit être exactement l'un de ces 3 choix
            // (admin ne peut pas s'inscrire depuis le formulaire)
            'role'     => ['required', 'in:acheteur,vendeur_amateur,vendeur_pro'],

            // Le numéro MVola est obligatoire seulement pour les vendeurs
            'mvola_number' => [
                $request->role !== 'acheteur' ? 'required' : 'nullable',
                'string',
                'max:20'
            ],

            // Le nom d'entreprise est obligatoire seulement pour vendeur_pro
            'company_name' => [
                $request->role === 'vendeur_pro' ? 'required' : 'nullable',
                'string',
                'max:255'
            ],
        ]);

        // Création de l'utilisateur dans la base de données
        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
            'mvola_number' => $request->mvola_number,
            'company_name' => $request->company_name,
            // Le compte est en attente de validation admin avant de pouvoir se connecter
            'status' => 'en_attente',
        ]);

        event(new Registered($user));

        // Connecte l'utilisateur automatiquement après inscription
        Auth::login($user);

        // Ne pas connecter l'utilisateur — son compte est en attente
        // On stocke juste son nom et email en session pour la page d'attente
        return redirect()->route('auth.pending')
            ->with('pending_name', $user->name)
            ->with('pending_email', $user->email);
            }
}