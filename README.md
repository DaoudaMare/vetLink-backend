# 📘 Documentation du Backend Laravel

## 📌 Introduction

Ce projet est une API Laravel pour la gestion d'un système de commandes entre des **producteurs** et des **consommateurs**. L'API permet la gestion des utilisateurs, des produits, des commandes et des paiements.

## 🏗️ Architecture de la Base de Données

### 🗄️ Tables principales :

1. **Utilisateurs (`users`)**

    - Contient les informations des utilisateurs (consommateurs et producteurs).
    - Différenciation par le champ `type_utilisateur`.

2. **Produits (`produits`)**

    - Gérés par les producteurs.
    - Contient les informations sur les produits disponibles.

3. **Commandes (`commandes`)**

    - Passées par les consommateurs.
    - Peuvent contenir plusieurs produits via `commande_produit`.

4. **Table pivot `commande_produit`**

    - Permet d'associer plusieurs produits à une commande avec une quantité.

5. **Paiements (`paiements`)**
    - Associés aux commandes pour enregistrer les transactions.

## 📜 Schéma des Migrations

### `users`

-   `id` (clé primaire)
-   `nom`, `prenom`, `email`, `adresse`
-   `mot_de_passe`
-   `type_utilisateur` (`producteur` ou `consommateur`)
-   `abonnement` (optionnel pour des fonctionnalités avancées)

### `produits`

-   `id` (clé primaire)
-   `nom_produit`, `description`, `prix`, `quantite_disponible`
-   `producteur_id` (clé étrangère vers `users`)

### `commandes`

-   `id` (clé primaire)
-   `date_commande`
-   `utilisateur_id` (clé étrangère vers `users`)
-   `statut` (en attente, validée, livrée...)

### `commande_produit`

-   `commande_id` (clé étrangère vers `commandes`)
-   `produit_id` (clé étrangère vers `produits`)
-   `quantite`

### `paiements`

-   `id` (clé primaire)
-   `montant`, `date_paiement`, `mode_paiement`
-   `commande_id` (clé étrangère vers `commandes`)

## 📌 Endpoints de l'API

### 1️⃣ Utilisateurs

-   `POST /api/register` → Inscription
-   `POST /api/login` → Connexion
-   `GET /api/profile` → Profil utilisateur
-   `PUT /api/users/{id}` → Mettre à jour un utilisateur
-   `DELETE /api/users/{id}` → Supprimer un utilisateur

### 2️⃣ Produits

-   `GET /api/produits` → Liste des produits
-   `GET /api/produits/{id}` → Détails d'un produit
-   `POST /api/produits` → Ajouter un produit (producteur uniquement)
-   `PUT /api/produits/{id}` → Mettre à jour un produit
-   `DELETE /api/produits/{id}` → Supprimer un produit

### 3️⃣ Commandes

-   `GET /api/commandes` → Liste des commandes
-   `GET /api/commandes/{id}` → Détails d'une commande
-   `POST /api/commandes` → Passer une commande
-   `PUT /api/commandes/{id}` → Mettre à jour une commande
-   `DELETE /api/commandes/{id}` → Supprimer une commande

### 4️⃣ Paiements

-   `GET /api/paiements` → Liste des paiements
-   `GET /api/paiements/{id}` → Voir un paiement
-   `POST /api/paiements` → Effectuer un paiement

## 📌 Bonnes Pratiques

-   **Utilisation du pattern Repository-Service** pour une meilleure séparation des responsabilités.
-   **Validation des données avec FormRequest** (`store`, `update`...).
-   **Authentification sécurisée avec Laravel Sanctum**.
-   **Utilisation des Policies et Gates** pour la gestion des autorisations.
-   **Optimisation des performances avec Eloquent et les relations bien définies**.
-   **Mise en place des tests unitaires et fonctionnels avec PHPUnit**.
-   **Gestion des logs et monitoring avec Laravel Telescope**.
-   **Utilisation des queues Laravel pour le traitement des tâches asynchrones**.

## 🚀 Installation et Configuration

```bash
composer install
php artisan migrate
php artisan serve
```
