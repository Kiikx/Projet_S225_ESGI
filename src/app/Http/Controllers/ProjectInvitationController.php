<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjectInvitationMail;

class ProjectInvitationController extends Controller
{
    /**
     * Envoyer une invitation par email
     */
    public function store(Request $request, Project $project)
    {
        // Vérifier que l'utilisateur est le propriétaire du projet
        if ($project->owner_id !== Auth::id()) {
            return back()->withErrors('Seul le propriétaire peut envoyer des invitations.');
        }

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $email = strtolower($request->email);

        // Vérifier que l'utilisateur n'est pas déjà membre
        $existingUser = User::where('email', $email)->first();
        if ($existingUser && $project->members->contains($existingUser->id)) {
            return back()->withErrors(['email' => 'Cet utilisateur est déjà membre du projet.']);
        }

        // Vérifier qu'il n'y a pas déjà une invitation en cours
        $existingInvitation = $project->invitations()
            ->where('email', $email)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            return back()->withErrors(['email' => 'Une invitation est déjà en cours pour cette adresse email.']);
        }

        // Créer l'invitation
        $invitation = $project->invitations()->create([
            'inviter_id' => Auth::id(),
            'email' => $email,
        ]);

        // Envoyer l'email d'invitation
        try {
            Mail::to($email)->send(new ProjectInvitationMail($invitation));
            return back()->with('success', 'Invitation envoyée avec succès à ' . $email);
        } catch (\Exception $e) {
            // Supprimer l'invitation si l'email n'a pas pu être envoyé
            $invitation->delete();
            return back()->withErrors('Erreur lors de l\'envoi de l\'email. Veuillez réessayer.');
        }
    }

    /**
     * Accepter une invitation (page d'acceptation)
     */
    public function show($token)
    {
        $invitation = ProjectInvitation::where('token', $token)->first();

        if (!$invitation) {
            abort(404, 'Invitation introuvable.');
        }

        if (!$invitation->isValid()) {
            $message = $invitation->isExpired() ? 'Cette invitation a expiré.' : 'Cette invitation n\'est plus valide.';
            return view('invitations.expired', compact('message'));
        }

        return view('invitations.show', compact('invitation'));
    }

    /**
     * Accepter une invitation
     */
    public function accept(Request $request, $token)
    {
        $invitation = ProjectInvitation::where('token', $token)->first();

        if (!$invitation || !$invitation->isValid()) {
            return redirect()->route('login')->withErrors('Invitation invalide ou expirée.');
        }

        // Si l'utilisateur n'est pas connecté et n'existe pas, le rediriger vers l'inscription
        if (!Auth::check()) {
            $existingUser = User::where('email', $invitation->email)->first();
            if (!$existingUser) {
                // Stocker le token et l'email dans la session pour l'utiliser après l'inscription
                session([
                    'invitation_token' => $token,
                    'invitation_email' => $invitation->email
                ]);
                return redirect()->route('register')->with('invitation_email', $invitation->email);
            } else {
                // Utilisateur existe, le rediriger vers la connexion
                session(['invitation_token' => $token]);
                return redirect()->route('login')->with('message', 'Connectez-vous pour rejoindre le projet.');
            }
        }

        // Utilisateur connecté, vérifier que c'est la bonne adresse email
        // Note: Cette validation n'est nécessaire que pour les utilisateurs existants qui se connectent
        // Les nouveaux utilisateurs sont validés dans RegisteredUserController avant création du compte
        if (Auth::user()->email !== $invitation->email) {
            Auth::logout();
            // Remettre le token en session pour qu'il soit traité après la connexion
            session(['invitation_token' => $token]);
            return redirect()->route('login')->withErrors('Cette invitation est destinée à une autre adresse email.');
        }

        // Ajouter l'utilisateur au projet
        $project = $invitation->project;
        if (!$project->members->contains(Auth::id())) {
            $project->members()->attach(Auth::id());
        }

        // Marquer l'invitation comme acceptée
        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Vous avez rejoint le projet "' . $project->name . '" avec succès !');
    }

    /**
     * Refuser une invitation
     */
    public function decline($token)
    {
        $invitation = ProjectInvitation::where('token', $token)->first();

        if (!$invitation) {
            abort(404, 'Invitation introuvable.');
        }

        $invitation->update(['status' => 'declined']);

        return view('invitations.declined');
    }
}
