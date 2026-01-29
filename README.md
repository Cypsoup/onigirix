# 🍙 OnigiriX

Cette application web permet aux étudiants de commander leurs onigiris en ligne, de suivre l'état de la file d'attente en temps réel et de recevoir une notification dès que leur commande est prête. Elle inclut également une interface administrateur complète pour la gestion du stand.

## Fonctionnalités

### Côté Utilisateur

* **Menu Digital :** Consultation de la carte des onigiris avec allergènes et disponibilités.
* **Commande en ligne :** Panier d'achat et validation de commande.
* **Live Queue :** Visualisation de sa position dans la file d'attente et estimation du temps d'attente (ETD).
* **Notifications :** Alerte (Web Push ou SMS/Email) quand la commande passe au statut "Prête".

### Côté Admin

* **Tableau de bord des commandes :** Gestion des statuts en un clic (*En attente* ⮕ *En préparation* ⮕ *Prête* ⮕ *Récupérée*).
* **Gestion des Stocks :** Mise à jour automatique des stocks. Si un ingrédient manque, l'onigiri passe automatiquement en "Rupture de stock" sur le site.
* **Statistiques :** Suivi des ventes en temps réel pour optimiser la production.

---

## Stack Technique

Pour gérer le **temps réel** (file d'attente) et les **notifications**, voici la stack recommandée :

* **Frontend :** React.js ou Next.js (pour une interface fluide) + Tailwind CSS.
* **Backend / Base de données :** * **Firebase** ou **Supabase** (Idéal pour le temps réel sans gérer de serveur complexe).
* **Notifications :** Web Push API ou intégration Discord/Telegram.

---


## Structure de la Base de Données

Nous utilisons une base de données relationnelle pour gérer les utilisateurs, les stocks et les commandes de manière flexible.

### Schéma des Tables

#### 1. Table `users`
Gère les comptes des étudiants et les accès admin.
- `id` (UUID/INT, PK) : Identifiant unique.
- `trigramme` (VARCHAR(3), UNIQUE) : Identifiant école (ex: ABC).
- `nom` (VARCHAR(255)) : Nom complet de l'étudiant.
- `email` (VARCHAR(255), UNIQUE) : Email de l'école.
- `password` (TEXT) : Hash du mot de passe.
- `role` (ENUM) : 'user' ou 'admin'.

#### 2. Table `events`
Gère les sessions de vente (ex: "Vente du midi").
- `id` (INT, PK) : Identifiant unique.
- `nom` (VARCHAR(255)) : Nom de l'évènement.
- `is_open` (BOOLEAN) : Permet d'ouvrir/fermer la prise de commande.
- `date_event` (TIMESTAMP) : Date de la vente.

#### 3. Table `recipes` (Le Menu)
Gère les types d'onigiris disponibles.
- `id` (INT, PK) : Identifiant unique.
- `nom` (VARCHAR(50)) : Ex: Thon, Boeuf, Aubergine...
- `description` (TEXT) : Ingrédients et allergènes.
- `prix` (DECIMAL) : Prix unitaire.
- `stock` (INT) : Quantité restante pour l'évènement en cours.

#### 4. Table `orders` (L'Enveloppe)
Contient les informations globales d'une commande.
- `id` (INT, PK) : Numéro de commande unique.
- `user_id` (FK -> users.id) : Référence à l'étudiant qui commande.
- `event_id` (FK -> events.id) : Référence à l'évènement lié.
- `statut` (ENUM) : 'attente', 'prepa', 'pret', 'archive', 'annule'.
- `montant_total` (DECIMAL) : Prix total payé.
- `created_at` (TIMESTAMP) : Heure de la commande.


#### 5. Table `order_items` (Le Contenu)
C'est ici que sont stockés les détails (plusieurs onigiris pour une même commande).
- `id` (INT, PK) : Identifiant unique.
- `order_id` (FK -> orders.id) : Lien vers la commande globale.
- `recipe_id` (FK -> recipes.id) : Lien vers le type d'onigiri.
- `quantite` (INT) : Nombre d'unités de ce type (ex: 2).

---

### Exemple de fonctionnement
Si **Jean** commande **2 Thon** et **1 Boeuf** :

1. Une ligne est créée dans `orders` (ID: 42, User: ABC, Total: 7.50€).
2. Deux lignes sont créées dans `order_items` :
   - Ligne 1 : Order_ID: 42, Recipe: Thon, Quantité: 2.
   - Ligne 2 : Order_ID: 42, Recipe: Boeuf, Quantité: 1.

Cela permet à l'admin de voir instantanément le détail de la commande #42 et de calculer les stats globales de production.



