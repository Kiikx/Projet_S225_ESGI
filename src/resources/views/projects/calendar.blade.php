<x-app-layout>
    <div class="container mx-auto py-6 px-4">
        <!-- Header avec nom du projet -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">📅 Calendrier – {{ $project->name }}</h2>
            <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800">
                ← Retour au projet
            </a>
        </div>

        <!-- Sélecteur de vues -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex items-center justify-between">
                <!-- Navigation temporelle -->
                <div class="flex items-center space-x-2">
                    <button id="prevButton" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">
                        ← Précédent
                    </button>
                    <button id="nextButton" class="bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded">
                        Suivant →
                    </button>
                    <span id="currentPeriod" class="font-semibold text-lg mx-4">
                        <!-- JavaScript remplira ça -->
                    </span>
                </div>

                <!-- Sélecteur de vue -->
                <div class="flex space-x-2">
                    <button class="view-btn active" data-view="month">Mois</button>
                    <button class="view-btn" data-view="week">Semaine</button>
                    <button class="view-btn" data-view="3days">3 Jours</button>
                    <button class="view-btn" data-view="day">Jour</button>
                </div>
            </div>
        </div>

        <!-- Container du calendrier -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div id="calendar-container">
                <!-- JavaScript générera le calendrier ici -->
            </div>
        </div>
    </div>

    <!-- Données pour JavaScript -->
    <script>
        // Version simplifiée temporaire
        const tasksData = [];
        
        // Variables globales pour la navigation
        let currentDate = new Date();
        let currentView = 'month';

        console.log('Calendrier prêt - pas de tâches pour l\'instant');
    </script>

    <!-- Styles CSS pour le calendrier -->
    <style>
        .view-btn {
            @apply px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors;
        }
        .view-btn.active {
            @apply bg-blue-500 text-white hover:bg-blue-600;
        }
        
        .calendar-grid {
            display: grid;
            gap: 1px;
            background-color: #e5e7eb;
        }
        
        .calendar-month {
            grid-template-columns: repeat(7, 1fr);
        }
        
        .calendar-week {
            grid-template-columns: repeat(7, 1fr);
        }
        
        .calendar-3days {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .calendar-day {
            grid-template-columns: 1fr;
        }
        
        .calendar-cell {
            @apply bg-white p-2 min-h-24;
        }
        
        .task-item {
            @apply text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded mb-1 cursor-pointer hover:bg-blue-200;
        }
        
        .task-priority-high { @apply bg-red-100 text-red-800; }
        .task-priority-medium { @apply bg-orange-100 text-orange-800; }
        .task-priority-low { @apply bg-green-100 text-green-800; }
    </style>

    <!-- JavaScript pour le calendrier (prochaine étape) -->
    <script>
        // TODO: Implémenter la logique du calendrier
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Calendar ready!');
            
            // Event listeners pour les boutons
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    // Changer la vue active
                    document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentView = this.dataset.view;
                    
                    // TODO: Regénérer le calendrier
                    console.log('Vue changée vers:', currentView);
                });
            });
            
            // TODO: Ajouter la logique de navigation précédent/suivant
        });
    </script>
</x-app-layout>
