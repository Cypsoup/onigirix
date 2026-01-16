# 🍙 OnigiriX

Cette application web permet aux étudiants de commander leurs onigiris en ligne, de suivre l'état de la file d'attente en temps réel et de recevoir une notification dès que leur commande est prête. Elle inclut également une interface administrateur complète pour la gestion du stand.

## 🚀 Fonctionnalités

### 📱 Côté Utilisateur (Client)

* **Menu Digital :** Consultation de la carte des onigiris avec allergènes et disponibilités.
* **Commande en ligne :** Panier d'achat et validation de commande.
* **Live Queue :** Visualisation de sa position dans la file d'attente et estimation du temps d'attente (ETD).
* **Notifications :** Alerte (Web Push ou SMS/Email) quand la commande passe au statut "Prête".

### 🛠️ Côté Administrateur (Staff Onigiri)

* **Tableau de bord des commandes :** Gestion des statuts en un clic (*En attente* ⮕ *En préparation* ⮕ *Prête* ⮕ *Récupérée*).
* **Gestion des Stocks :** Mise à jour automatique des stocks. Si un ingrédient manque, l'onigiri passe automatiquement en "Rupture de stock" sur le site.
* **Statistiques :** Suivi des ventes en temps réel pour optimiser la production.

---

## 🛠️ Stack Technique (Suggérée)

Pour gérer le **temps réel** (file d'attente) et les **notifications**, voici la stack recommandée :

* **Frontend :** React.js ou Next.js (pour une interface fluide) + Tailwind CSS.
* **Backend / Base de données :** * **Firebase** ou **Supabase** (Idéal pour le temps réel sans gérer de serveur complexe).
* **Notifications :** Web Push API ou intégration Discord/Telegram.

---

## 📂 Structure du Projet

```text
.
├── src/
│   ├── components/      # Composants réutilisables (OnigiriCard, QueueStatus, etc.)
│   ├── pages/
│   │   ├── client/      # Interface de commande et suivi
│   │   └── admin/       # Dashboard de gestion des stocks et commandes
│   ├── hooks/           # Logique de récupération des données en temps réel
│   └── services/        # Configuration Firebase/Supabase
├── public/              # Images des onigiris et icônes
└── README.md

```

---


## 📈 Roadmap / Prochaines étapes

* [ ] Maquettage de l'interface de commande (UI/UX).
* [ ] Mise en place de la base de données pour les stocks.
* [ ] Développement du système de calcul du temps d'attente (basé sur le nombre de commandes en cours).
* [ ] Ajout d'un système de paiement (Lydia, Stripe ou jetons asso).

---

## ⛩️ Contact

* **Équipe Tech Onigiri :** [Votre Nom]
* **Association :** [Lien réseaux sociaux]

---

*Bon appétit ! Itadakimasu !* 🥢

