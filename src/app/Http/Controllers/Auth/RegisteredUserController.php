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
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Vérifier si l'utilisateur vient d'une invitation et valider l'email AVANT la création du compte
        if ($request->session()->has('invitation_token')) {
            $token = $request->session()->get('invitation_token');
            
            // Récupérer l'invitation depuis la base de données avec le token
            $invitation = \App\Models\ProjectInvitation::where('token', $token)->first();
            
            if ($invitation && $invitation->isValid()) {
                $invitationEmail = $invitation->email;
                
                if (strtolower($request->email) !== strtolower($invitationEmail)) {
                    return back()->withInput()->withErrors([
                        'email' => 'Cette invitation est destinée à l\'adresse email : ' . $invitationEmail . '. Vous devez utiliser cette adresse pour créer votre compte.'
                    ]);
                }
            }
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Vérifier s'il y a un token d'invitation en session
        if ($request->session()->has('invitation_token')) {
            $token = $request->session()->get('invitation_token');
            $request->session()->forget('invitation_token');
            $request->session()->forget('invitation_email'); // Nettoyer aussi l'email d'invitation
            
            // Rediriger vers l'acceptation automatique de l'invitation
            return redirect()->route('invitations.accept.get', $token);
        }

        return redirect(route('dashboard', absolute: false));
    }
}
