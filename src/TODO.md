# 📋 TODO - Finalisation du projet Kanboard

## ✅ **CE QUI EST DÉJÀ FAIT**

### Fonctionnalités principales
- ✅ **Authentification complète** (inscription, login, reset password)
- ✅ **Projets** : création, gestion des membres, propriétaire
- ✅ **Tâches** : création, assignation multiple (corrigé !)
- ✅ **Kanban** : drag & drop, colonnes personnalisées
- ✅ **Vue liste** : affichage et filtrage des tâches
- ✅ **Statuts** : À faire, En cours, Fait, Annulé (tous présents maintenant)
- ✅ **Catégories globales** : système de labels partagés (corrigé !)
- ✅ **Interface admin** : gestion des labels, rôles utilisateurs
- ✅ **Système de rôles** : admin/utilisateur avec badges
- ✅ **Suppression de projets** : avec confirmations de sécurité
- ✅ **Responsive** : interface qui s'adapte mobile/desktop

### Corrections majeures effectuées
- ✅ **Assignations multiples** : table pivot task_user créée et migrée
- ✅ **Catégories globales** : plus liées aux projets spécifiques
- ✅ **Statut "Annulé"** : ajouté partout où il manquait
- ✅ **Protection propriétaire** : ne peut pas se retirer de son projet

---

## 🚧 **CE QUI RESTE À FAIRE**

### 1. **Système de priorités** (OBLIGATOIRE)
- [ ] **Interface** : ajouter sélection priorité dans création/édition tâches
- [ ] **Affichage** : montrer priorité dans liste et Kanban (badges colorés)
- [ ] **Données** : le modèle Priority existe déjà, juste l'intégrer

### 2. **Dates des tâches** (OBLIGATOIRE)
- [ ] **Date de complétion** : automatique quand tâche → statut terminal
- [ ] **Date limite** : champ optionnel + affichage retard
- [ ] **Interface** : ajouter ces champs dans formulaires
- [ ] **Migration** : ajouter colonnes `completed_at`, `deadline`

### 3. **Vue calendrier** (OBLIGATOIRE)
- [ ] **Page calendrier** : affichage mensuel/hebdomadaire/journalier
- [ ] **Tâches sur dates** : basé sur date limite
- [ ] **Navigation** : jour/3 jours/semaine/mois
- [ ] **Intégration** : liens vers détails tâches

### 4. **Invitation par email** (OBLIGATOIRE)
- [ ] **Formulaire** : changer sélection → saisie email
- [ ] **Validation** : vérifier que l'email existe dans users
- [ ] **Logique** : trouver user par email puis l'ajouter

### 5. **Détails techniques**
- [ ] **Délai reset password** : changer de 60min → 5min
- [ ] **Tests** : vérifier que tout fonctionne
- [ ] **Documentation** : finaliser README
- [ ] **Déploiement** : préparer pour présentation

---

## 📅 **PLANNING JOUR J+1**

### Matin (3-4h)
1. **Priorités** : interface + affichage (1h)
2. **Dates** : migration + formulaires (1h) 
3. **Invitation email** : corriger le système (1h)

### Après-midi (4-5h)
4. **Vue calendrier** : création complète (3-4h)
5. **Tests finaux** : vérification fonctionnalités (1h)

---

## 💡 **NOTES IMPORTANTES**

- **Email dev** : Mailtrap configuré pour les tests
- **Base solide** : les fondations sont TRÈS bien, il ne reste que les détails
- **Temps estimé** : 7-8h pour tout finaliser

---

## 🎯 **PRIORITÉ ABSOLUE** (ordre d'importance)

1. **Priorités des tâches** (simple à faire)
2. **Dates de complétion/limite** (logique importante)
3. **Invitation par email** (conformité sujet)
4. **Vue calendrier** (plus complexe mais demandée)

Le projet est déjà très complet et fonctionnel sinon
