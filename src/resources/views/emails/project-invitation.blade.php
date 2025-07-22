<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation au projet {{ $project->name }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 32px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 32px;
        }
        .project-info {
            background: #f8f9ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .project-name {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
            margin: 0 0 8px 0;
        }
        .project-description {
            color: #6b7280;
            margin: 0;
        }
        .actions {
            text-align: center;
            margin: 32px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 32px;
            margin: 0 8px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #10b981;
            color: white;
        }
        .btn-primary:hover {
            background: #059669;
        }
        .btn-secondary {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #d1d5db;
        }
        .btn-secondary:hover {
            background: #e5e7eb;
        }
        .footer {
            background: #f9fafb;
            padding: 24px 32px;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .expiry-notice {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 6px;
            padding: 16px;
            margin: 20px 0;
            text-align: center;
        }
        .expiry-notice strong {
            color: #d97706;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Invitation à rejoindre un projet</h1>
        </div>
        
        <div class="content">
            <p>Bonjour,</p>
            
            <p><strong>{{ $inviter->name }}</strong> vous invite à rejoindre le projet sur Kanboard :</p>
            
            <div class="project-info">
                <div class="project-name">📋 {{ $project->name }}</div>
                @if($project->description)
                    <div class="project-description">{{ $project->description }}</div>
                @endif
            </div>
            
            <p>Kanboard est une plateforme de gestion de projets en équipe avec des tableaux Kanban, permettant d'organiser et suivre efficacement vos tâches collaboratives.</p>
            
            <div class="expiry-notice">
                <strong>⏰ Cette invitation expire le {{ $invitation->expires_at->format('d/m/Y à H:i') }}</strong>
            </div>
            
            <div class="actions">
                <a href="{{ $acceptUrl }}" class="btn btn-primary">✅ Accepter l'invitation</a>
                <a href="{{ $declineUrl }}" class="btn btn-secondary">❌ Décliner</a>
            </div>
            
            <p style="font-size: 14px; color: #6b7280;">
                Si vous n'avez pas encore de compte, vous pourrez en créer un lors de l'acceptation de l'invitation.
            </p>
        </div>
        
        <div class="footer">
            <p>
                <strong>Kanboard</strong> - Votre plateforme de gestion de projets<br>
                Cette invitation a été envoyée par {{ $inviter->name }} ({{ $inviter->email }}).
            </p>
            <p style="margin-top: 16px; font-size: 12px;">
                Si vous ne souhaitez pas recevoir ce type d'email, vous pouvez ignorer ce message.
            </p>
        </div>
    </div>
</body>
</html>
