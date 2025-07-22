<x-app-layout>
    <div class="container mx-auto py-6 px-4">
        <!-- Header avec nom du projet -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">📅 Calendrier – {{ $project->name }}</h2>
            <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-800">
                ← Retour au projet
            </a>
        </div>

        <!-- Statistiques rapides -->
        @php
            $totalTasks = $tasksWithDeadline->count();
            $overdueTasks = $tasksWithDeadline->filter(function($task) {
                return $task->deadline->isPast() && !$task->completed_at;
            })->count();
            $thisWeekTasks = $tasksWithDeadline->filter(function($task) {
                return $task->deadline->isCurrentWeek();
            })->count();
            $nextWeekTasks = $tasksWithDeadline->filter(function($task) {
                return $task->deadline->between(
                    \Carbon\Carbon::now()->addWeek()->startOfWeek(),
                    \Carbon\Carbon::now()->addWeek()->endOfWeek()
                );
            })->count();
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total des tâches -->
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600 mb-1">{{ $totalTasks }}</div>
                <div class="text-sm text-gray-600">Tâches planifiées</div>
            </div>
            
            <!-- Tâches en retard -->
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-red-600 mb-1">{{ $overdueTasks }}</div>
                <div class="text-sm text-gray-600">En retard</div>
            </div>
            
            <!-- Cette semaine -->
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-orange-600 mb-1">{{ $thisWeekTasks }}</div>
                <div class="text-sm text-gray-600">Cette semaine</div>
            </div>
            
            <!-- Semaine prochaine -->
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600 mb-1">{{ $nextWeekTasks }}</div>
                <div class="text-sm text-gray-600">Semaine prochaine</div>
            </div>
        </div>

        <!-- Légende des priorités -->
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Légende des priorités :</h3>
            <div class="flex gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-red-100 border border-red-300"></div>
                    <span class="text-sm text-gray-600">Haute priorité</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-orange-100 border border-orange-300"></div>
                    <span class="text-sm text-gray-600">Priorité moyenne</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-green-100 border border-green-300"></div>
                    <span class="text-sm text-gray-600">Basse priorité</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded bg-gray-100 border border-gray-300"></div>
                    <span class="text-sm text-gray-600">Sans priorité</span>
                </div>
            </div>
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
                    <button class="view-btn" data-view="day">Jour</button>
                </div>
            </div>
        </div>

        <!-- Container du calendrier -->
        <div class="calendar-container">
            <div class="bg-white rounded-2xl overflow-hidden">
                <div id="calendar-container">
                    <!-- JavaScript générera le calendrier ici -->
                </div>
            </div>
        </div>
    </div>

    <!-- Données pour JavaScript -->
    <script>
        const tasksData = @json($tasksWithDeadline);
        const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        const dayNames = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
        
        // Variables globales pour la navigation
        let currentDate = new Date();
        let currentView = 'month';

        console.log('Tâches avec échéances:', tasksData);
        console.log('Nombre de tâches:', tasksData.length);
        
        // Debug : afficher les dates des tâches
        tasksData.forEach(task => {
            console.log('Tâche:', task.title, 'Deadline:', task.deadline);
        });
    </script>

    <!-- Styles CSS pour le calendrier -->
    <style>
        .view-btn {
            @apply px-4 py-2 bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 rounded-lg hover:from-gray-100 hover:to-gray-200 transition-all duration-200 shadow-sm hover:shadow-md;
        }
        .view-btn.active {
            @apply bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:from-blue-600 hover:to-blue-700 shadow-lg;
        }
        
        .calendar-grid {
            display: grid;
            gap: 8px;
            padding: 8px;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 12px;
        }
        
        .calendar-month {
            grid-template-columns: repeat(7, 1fr);
        }
        
        .calendar-week {
            grid-template-columns: repeat(7, 1fr);
        }
        
        .calendar-day {
            grid-template-columns: 1fr;
            min-height: 400px;
        }
        
        .calendar-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            @apply text-white font-semibold text-center py-3 text-sm rounded-lg shadow-md;
        }
        
        .calendar-cell {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            @apply p-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100;
            min-height: 140px;
            position: relative;
            transform: translateY(0);
        }
        
        .calendar-cell:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .calendar-cell.today {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            @apply border-2 border-blue-400 shadow-xl;
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.3), 0 4px 6px -2px rgba(59, 130, 246, 0.1);
        }
        
        .calendar-cell.other-month {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            @apply text-gray-400 opacity-60;
        }
        
        .date-number {
            @apply font-bold text-base mb-3;
            color: #374151;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        
        .calendar-cell.today .date-number {
            @apply text-blue-700 font-extrabold;
        }
        
        .task-item {
            @apply text-xs px-3 py-2 rounded-lg mb-2 cursor-pointer truncate block transition-all duration-200 shadow-sm hover:shadow-md;
            transform: translateX(0);
        }
        
        .task-item:hover {
            transform: translateX(2px) scale(1.02);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .task-priority-3 { 
            background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
            @apply text-red-800 border-l-4 border-red-500 shadow-red-100;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
        }
        
        .task-priority-2 { 
            background: linear-gradient(135deg, #fffbeb 0%, #fed7aa 100%);
            @apply text-orange-800 border-l-4 border-orange-500 shadow-orange-100;
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.2);
        }
        
        .task-priority-1 { 
            background: linear-gradient(135deg, #f0fdf4 0%, #bbf7d0 100%);
            @apply text-green-800 border-l-4 border-green-500 shadow-green-100;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.2);
        }
        
        .task-priority-null { 
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            @apply text-gray-700 border-l-4 border-gray-400 shadow-gray-100;
            box-shadow: 0 2px 8px rgba(107, 114, 128, 0.15);
        }
        
        .task-overdue {
            background: linear-gradient(135deg, #fef2f2 0%, #dc2626 20%, #b91c1c 100%) !important;
            @apply text-white border-l-4 border-red-700;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        
        /* Vue jour spécifique */
        .day-view .calendar-cell {
            min-height: 500px;
            background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
        }
        
        .day-view .task-item {
            @apply text-sm p-4 mb-3 shadow-lg;
        }
        
        .task-detail {
            @apply text-xs text-gray-600 mt-2 opacity-80;
        }
        
        /* Effet de survol global */
        .calendar-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1px;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Weekend styling */
        .weekend {
            background: linear-gradient(135deg, #fef7ff 0%, #f3e8ff 100%) !important;
        }
        
        .weekend .date-number {
            @apply text-purple-700;
        }
        
        /* Animation d'entrée */
        .calendar-cell {
            animation: fadeInUp 0.3s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Amélioration des boutons de navigation */
        #prevButton, #nextButton {
            background: linear-gradient(135deg, #f3f4f6 0%, #d1d5db 100%);
            @apply shadow-lg hover:shadow-xl transition-all duration-200 border border-gray-200;
        }
        
        #prevButton:hover, #nextButton:hover {
            background: linear-gradient(135deg, #e5e7eb 0%, #9ca3af 100%);
            transform: translateY(-1px);
        }
    </style>

    <!-- JavaScript pour le calendrier -->
    <script>
        function updatePeriodLabel() {
            const periodElement = document.getElementById('currentPeriod');
            
            if (currentView === 'month') {
                periodElement.textContent = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
            } else if (currentView === 'week') {
                const startOfWeek = new Date(currentDate);
                startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());
                const endOfWeek = new Date(startOfWeek);
                endOfWeek.setDate(startOfWeek.getDate() + 6);
                periodElement.textContent = `Semaine du ${startOfWeek.getDate()}/${startOfWeek.getMonth() + 1} au ${endOfWeek.getDate()}/${endOfWeek.getMonth() + 1}`;
            } else if (currentView === 'day') {
                periodElement.textContent = `${dayNames[currentDate.getDay()]} ${currentDate.getDate()} ${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
            }
        }

        function getTasksForDate(date) {
            const dateStr = date.toISOString().split('T')[0];
            console.log('Recherche tâches pour la date:', dateStr);
            
            const matchingTasks = tasksData.filter(task => {
                if (!task.deadline) {
                    console.log('Tâche sans deadline:', task.title);
                    return false;
                }
                
                // Gérer différents formats de date
                let taskDateStr;
                if (typeof task.deadline === 'string') {
                    // Format Laravel timestamp: "2025-07-22 14:30:00" ou "2025-07-22"
                    taskDateStr = task.deadline.split(' ')[0];
                } else if (task.deadline instanceof Date) {
                    taskDateStr = task.deadline.toISOString().split('T')[0];
                } else {
                    console.log('Format de date non reconnu:', task.deadline, typeof task.deadline);
                    return false;
                }
                
                const isMatch = taskDateStr === dateStr;
                if (isMatch) {
                    console.log('✅ Tâche trouvée:', task.title, 'pour', dateStr, '(deadline:', task.deadline, ')');
                }
                
                return isMatch;
            });
            
            console.log(`Nombre de tâches trouvées pour ${dateStr}:`, matchingTasks.length);
            return matchingTasks;
        }

        function createTaskElement(task) {
            const taskElement = document.createElement('div');
            const priorityClass = task.priority ? `task-priority-${task.priority.level}` : 'task-priority-null';
            
            taskElement.className = `task-item ${priorityClass}`;
            
            // Vérifier si la tâche est en retard
            const today = new Date();
            const taskDate = new Date(task.deadline);
            if (taskDate < today && !task.completed_at) {
                taskElement.classList.add('task-overdue');
            }
            
            taskElement.textContent = task.title;
            
            // Ajouter tooltip avec plus d'infos
            let tooltipText = `Titre: ${task.title}`;
            if (task.description) tooltipText += `\nDescription: ${task.description}`;
            if (task.assignees && task.assignees.length > 0) {
                tooltipText += `\nAssigné à: ${task.assignees.map(a => a.name).join(', ')}`;
            }
            if (task.priority) tooltipText += `\nPriorité: ${task.priority.label}`;
            
            taskElement.title = tooltipText;
            
            // Ajouter un événement click pour plus de détails
            taskElement.addEventListener('click', () => {
                alert(tooltipText);
            });
            
            return taskElement;
        }

        function updateCalendar() {
            const container = document.getElementById('calendar-container');
            container.innerHTML = '';
            
            if (currentView === 'month') {
                generateMonthView(container);
            } else if (currentView === 'week') {
                generateWeekView(container);
            } else if (currentView === 'day') {
                generateDayView(container);
            }
            
            updatePeriodLabel();
        }

        function generateMonthView(container) {
            // En-têtes des jours
            const headerGrid = document.createElement('div');
            headerGrid.className = 'calendar-grid calendar-month';
            
            dayNames.forEach(day => {
                const header = document.createElement('div');
                header.className = 'calendar-header';
                header.textContent = day;
                headerGrid.appendChild(header);
            });
            
            container.appendChild(headerGrid);
            
            // Grille du calendrier
            const grid = document.createElement('div');
            grid.className = 'calendar-grid calendar-month';
            
            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            for (let i = 0; i < 42; i++) { // 6 semaines * 7 jours
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);
                
                const cell = document.createElement('div');
                cell.className = 'calendar-cell';
                
                // Marquer les jours d'autres mois
                if (date.getMonth() !== currentDate.getMonth()) {
                    cell.classList.add('other-month');
                }
                
                // Marquer aujourd'hui
                if (date.toDateString() === today.toDateString()) {
                    cell.classList.add('today');
                }
                
                // Marquer les week-ends
                if (date.getDay() === 0 || date.getDay() === 6) {
                    cell.classList.add('weekend');
                }
                
                // Numéro du jour
                const dateNumber = document.createElement('div');
                dateNumber.className = 'date-number';
                dateNumber.textContent = date.getDate();
                cell.appendChild(dateNumber);
                
                // Ajouter les tâches pour cette date
                const dayTasks = getTasksForDate(date);
                dayTasks.forEach(task => {
                    cell.appendChild(createTaskElement(task));
                });
                
                grid.appendChild(cell);
            }
            
            container.appendChild(grid);
        }

        function generateWeekView(container) {
            // En-têtes des jours
            const headerGrid = document.createElement('div');
            headerGrid.className = 'calendar-grid calendar-week';
            
            const startOfWeek = new Date(currentDate);
            startOfWeek.setDate(currentDate.getDate() - currentDate.getDay());
            
            for (let i = 0; i < 7; i++) {
                const date = new Date(startOfWeek);
                date.setDate(startOfWeek.getDate() + i);
                
                const header = document.createElement('div');
                header.className = 'calendar-header';
                header.textContent = `${dayNames[i]} ${date.getDate()}/${date.getMonth() + 1}`;
                headerGrid.appendChild(header);
            }
            
            container.appendChild(headerGrid);
            
            // Grille de la semaine
            const grid = document.createElement('div');
            grid.className = 'calendar-grid calendar-week';
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            for (let i = 0; i < 7; i++) {
                const date = new Date(startOfWeek);
                date.setDate(startOfWeek.getDate() + i);
                
                const cell = document.createElement('div');
                cell.className = 'calendar-cell';
                
                if (date.toDateString() === today.toDateString()) {
                    cell.classList.add('today');
                }
                
                // Marquer les week-ends
                if (date.getDay() === 0 || date.getDay() === 6) {
                    cell.classList.add('weekend');
                }
                
                // Ajouter les tâches pour cette date
                const dayTasks = getTasksForDate(date);
                dayTasks.forEach(task => {
                    cell.appendChild(createTaskElement(task));
                });
                
                grid.appendChild(cell);
            }
            
            container.appendChild(grid);
        }

        function generateDayView(container) {
            container.classList.add('day-view');
            
            const header = document.createElement('div');
            header.className = 'calendar-header text-lg py-4';
            header.textContent = `${dayNames[currentDate.getDay()]} ${currentDate.getDate()} ${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
            container.appendChild(header);
            
            const grid = document.createElement('div');
            grid.className = 'calendar-grid calendar-day';
            
            const cell = document.createElement('div');
            cell.className = 'calendar-cell';
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (currentDate.toDateString() === today.toDateString()) {
                cell.classList.add('today');
            }
            
            // Ajouter les tâches pour cette date
            const dayTasks = getTasksForDate(currentDate);
            
            if (dayTasks.length === 0) {
                const noTasks = document.createElement('div');
                noTasks.className = 'text-gray-500 text-center py-8';
                noTasks.textContent = 'Aucune tâche prévue pour cette date';
                cell.appendChild(noTasks);
            } else {
                const tasksList = document.createElement('div');
                tasksList.className = 'space-y-2';
                
                dayTasks.forEach(task => {
                    const taskElement = createTaskElement(task);
                    
                    // Ajouter plus de détails en vue jour
                    if (task.assignees && task.assignees.length > 0) {
                        const assignees = document.createElement('div');
                        assignees.className = 'task-detail';
                        assignees.textContent = `👤 ${task.assignees.map(a => a.name).join(', ')}`;
                        taskElement.appendChild(assignees);
                    }
                    
                    if (task.description) {
                        const description = document.createElement('div');
                        description.className = 'task-detail';
                        description.textContent = task.description.substring(0, 100) + '...';
                        taskElement.appendChild(description);
                    }
                    
                    tasksList.appendChild(taskElement);
                });
                
                cell.appendChild(tasksList);
            }
            
            grid.appendChild(cell);
            container.appendChild(grid);
        }

        // Navigation
        function navigatePrevious() {
            if (currentView === 'month') {
                currentDate.setMonth(currentDate.getMonth() - 1);
            } else if (currentView === 'week') {
                currentDate.setDate(currentDate.getDate() - 7);
            } else if (currentView === 'day') {
                currentDate.setDate(currentDate.getDate() - 1);
            }
            updateCalendar();
        }

        function navigateNext() {
            if (currentView === 'month') {
                currentDate.setMonth(currentDate.getMonth() + 1);
            } else if (currentView === 'week') {
                currentDate.setDate(currentDate.getDate() + 7);
            } else if (currentView === 'day') {
                currentDate.setDate(currentDate.getDate() + 1);
            }
            updateCalendar();
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Calendrier initialisé avec', tasksData.length, 'tâches');
            
            // Event listeners pour les boutons de vue
            document.querySelectorAll('.view-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentView = this.dataset.view;
                    updateCalendar();
                });
            });
            
            // Event listeners pour la navigation
            document.getElementById('prevButton').addEventListener('click', navigatePrevious);
            document.getElementById('nextButton').addEventListener('click', navigateNext);
            
            // Génération initiale du calendrier
            updateCalendar();
        });
    </script>
</x-app-layout>
