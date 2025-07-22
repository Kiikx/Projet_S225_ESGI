Kanboard ­— Ta nouvelle application de gestion
de projet préférée
Préface
Le projet annuel est un projet qui chaque année réuni l'ensemble des compétences acquises
autour d'un projet afin de donner aux étudiants l'opportunité de créer un projet complet et
professionnalisant.
Introduction
Vous êtes développeurs dans une équipe en charge du lancement prochain d'un service
intitulé Kanboard, qui est l’association des mots Kanban (organisation de tâches) et Board
(tableau).
Vous avez pour mission de lancer le produit dans quelques mois, et on vous demande un
certain nombre de fonctionnalités à présenter le jour de la démonstration pour convaincre
les investisseurs de miser sur votre projet.
Fonctionnalités (15 points)
Visibilité (1 point)
Votre service doit être déployé sur internet sous la forme d'une application Web accessible
via un nom de domaine sécurisé et qui comporte un minimum de contenu et balises
sémantiques permettant sa découverte sur les moteurs de recherche.
La direction vous autorise à changer le nom du projet, si cela vous permet de vous
démarquer de la concurrence plus facilement.
Métier (5 points)
L'application doit pouvoir permettre aux utilisateurs de gérer un ou plusieurs projet.
Authentification (1 point)
Les utilisateurs doivent avant tout s'inscrire sur la plateforme en se créant un compte qui
sera validé par un email de confirmation. La direction autorise l'utilisation exceptionnelle
d'outils externes comme Resend pour l'envoi de mail.
Les utilisateurs doivent pouvoir se connecter à l'application via leur adresse email et mot de
passe. Ils peuvent également demander à changer leur mot de passe en cas d'oubli, un
email leur sera alors envoyé afin de confirmer leur action. Ce dernier expirera au bout de 5
minutes sans action de la part de l'utilisateur.Projet (2 point)
Chaque utilisateur doit pouvoir créer un projet.
Un projet est constitué de membres, qui peuvent ajouter des tâches sous la forme de carte
en mode Kanban. Le créateur du groupe est le seul à pouvoir inviter et retirer des personnes
du groupe. Pour inviter un membre, il suffira de renseigner son adresse email.
Le projet est constitué de tâches. Chaque tâche peut être associée à une ou plusieurs
personne en charge de cette dernière. La tâche doit également identifier le créateur à
l'origine de cette tâche.
Chaque tâche dispose d'un titre, d'une description (optionnelle), d'une catégorie (optionnelle)
qui correspond à la colonne sur laquelle elle se situe (marketing, développement,
communication, ...), d'une priorité (optionnelle) qui correspond à l'importance de cette tâche
(basse, moyenne, élevée), d'une date de création ainsi que d'une date de complétion
(terminaison) et d'une date limite (optionnelle) après laquelle elle est considéré comme étant
en retard.
Vues (2 points)
Les cartes doivent pouvoir être affichée de trois façon différentes : vue Kanban (par défaut),
vue de liste et vue de calendrier.
La vue Kanban organise les tâches en carte.
Dans la vue Kanban, il doit être possible de déplacer les tâches à l'aide d'un glisser-déposer
dans différentes colonnes. Les colonnes par défaut créés pour chaque nouveau projet sont
les suivantes : à faire, en cours, fait, annulé. Il ne peut pas y avoir moins d'une colonne, et
l'une de ces colonne doit pouvoir être considérée comme terminale, c'est-à-dire qu'elle
termine les tâches qui sont déplacées à l'intérieur.
Une vue en forme de liste des tâches doit pouvoir permettre de facilement retrouver les
tâches facilitant la recherche et le filtrage de tâche sur ses caractéristiques (titre, description,
catégorie, ...).
La vue de calendrier doit reprendre les même données, mais doit afficher les tâches sur un
calendrier en fonction de leur date limite de fin. La vue calendrier doit prévoir des affichages
sur un jour, trois jours, une semaine et un mois.
Intégration Web (1 point)
Le site internet doit avoir l'apparence d'une application mobile, avec la possibilité d'avoir un
site responsive et qui s'adapte en fonction du thème de l'appareil de l'utilisateur (clair ou
sombre).
Langage et Frameworks (2 point)L'application doit être conçue à l'aide des langages suivants :
HTML
CSS
JavaScript
Laravel (PHP)
SQL
Stockage des données (2 point)
L'application doit utiliser une base de données SQL, dont le modèle de données doit être
réfléchi et conçu pour pouvoir être présenté le jour de la démonstration, tout en préparant
les justifications nécessaires quant à sa réalisation et les choix qui ont été pris pour la
conception de ce dernier.
Historisation des modifications (2 point)
Le projet doit être historisé en utilisant Git.
Chaque membre du groupe doit montrer une participation équitable afin de convaincre les
investisseurs du sérieux de tous les membres du groupe de travail.
Les messages de commit doivent être clairs, concis et doivent faire référence à des unités
de modifications, les messages de commits associés à un trop grand nombre de fichiers ou
de modification d'un coup sont prohibés.
Conteneurisation (2 point)
Le projet doit être conteneurisé, sans l'utilisation de services externe tel que Firebase ou
Vercel, et doit pouvoir être reproductible sur n'importe quel ordinateur, avec les instructions
de travail claires concernant son installation, paramétrage et démarrage.
Bonus (5 points)
Mode hors-ligne (2 points)
L'application doit pouvoir être utilisée en mode hors-ligne. Lorsque c'est le cas, l'utilisateur
doit être prévenu avec une indication claire et visuelle que le site est en mode hors-
connexion. Une fois en mode hors-ligne, toutes les modifications deviennent temporaires
mais sont sauvegardées en attendant d'avoir de nouveau de la connectivité. Dès la reprise
de la connexion, toutes les opérations doivent pouvoir être appliquées et sauvegardée en
base de données.
Temps réel (1 point)Les utilisateurs doivent pouvoir avoir les modifications en temps réel, avec le nom de la
personne qui a fait les modification sous la forme de notification lorsque cela arrive sur
l'application. La direction vous autorise à utiliser un système comme Laravel Pusher, Echo
ou Mercure.
Rapports et statistiques (1 point)
Il vous est demandé d'apporter une page supplémentaire de statistiques sur la gestion d'un
projet en particulier. Comme par exemple, le nombre moyen de tâches accomplies par
chacun des membres d'un projet, le temps moyen de complétion, la répartition des tâches
par catégories, et d'autres que vous proposerez.
iCal et synchronisation (1 point)
L'application doit pouvoir proposer un point de sortie HTTP permettant de récupérer la liste
des tâches, avec leur description et autres informations importantes sous le format iCal,
permettant ainsi de pouvoir importer le calendrier d'un projet vers n'importe quel calendrier
externe (Google Calendar, Microsoft Calendar, Gnome Calendar, ...).
Attentes et contraintes
Le projet doit inclure un dossier .git contenant l'historique des contributions de chacun
Le projet doit inclure tous les fichiers nécessaires et présentés lors de la soutenance
Le projet doit être téléversé sur MyGES sous la forme d'une archive au format ZIP, les
lien vers les dépôts Git sont interdits
Tous le code doit pouvoir être expliqué par tous les membres du groupe
Le projet doit respecter les bonnes pratiques de code (nommage des symboles,
commentaires si nécessaire, documentation, ...)
La soutenance doit être préparée et la tenue doit être professionnelle (pas de jogging,
chemise minimum)
Calendrier
SéanceObjectif
1Lancement du projet annuel
2Validation des groupes
3Suivi de projet
4Suivi de projet
5Démonstration avant soutenance finale