# 📱 API Documentation - VetLink Backend

Bienvenue dans la documentation de l'API VetLink Backend. Ce document décrit tous les endpoints disponibles, leurs fonctionnalités, les paramètres attendus, les réponses possibles et les exigences d'authentification.

---

## 🔐 Authentification & Gestion des Utilisateurs

### 1. Inscription d'un nouvel utilisateur
Permet à un nouvel utilisateur de s'inscrire en tant que client ou producteur.

*   **Endpoint:** `POST /api/register`
*   **Authentification:** Aucune
*   **Headers:** `Content-Type: application/json`
*   **Corps de la requête:**
    ```json
    // Exemple pour un client
    {
        "firstName": "Alice",
        "lastName": "Durand",
        "email": "alice.durand@example.com",
        "tel1": "0612345678",
        "address": "123 Rue des Lilas, 75001 Paris",
        "user_type_id": 4, // ID pour le rôle 'Client' (voir GET /api/user-types)
        "password": "SecurePassword123!",
        "password_confirmation": "SecurePassword123!"
    }

    // Exemple pour un producteur
    {
        "firstName": "Bernard",
        "lastName": "Lefevre",
        "email": "bernard.lefevre@ferme.com",
        "tel1": "0798765432",
        "address": "456 Route de la Campagne, 44000 Nantes",
        "user_type_id": 3, // ID pour le rôle 'Producteur' (voir GET /api/user-types)
        "password": "StrongPassword456$",
        "password_confirmation": "StrongPassword456$",
        "organization_name": "Ferme Bio du Soleil",
        "organization_type_id": 1, // ID pour le type d'organisation 'Ferme' (voir GET /api/organization-types)
        "business_sector_id": 1, // ID pour le secteur d'activité 'Agriculture' (voir GET /api/business-sectors)
        "organization_address": "456 Route de la Campagne, 44000 Nantes",
        "organization_tel1": "0123456789", // Téléphone principal de l'organisation
        "organization_tel2": "0987654321" // Deuxième téléphone de l'organisation (optionnel)
    }
    ```
*   **Réponse (Succès - 201 Created):**
    ```json
    {
        "message": "Utilisateur créé avec succès",
        "user": {
            "id": 1,
            "firstName": "Alice",
            "lastName": "Durand",
            "email": "alice.durand@example.com",
            "tel1": "0612345678",
            "address": "123 Rue des Lilas, 75001 Paris",
            "user_type_id": 4,
            "created_at": "2025-07-30T10:00:00.000000Z",
            "updated_at": "2025-07-30T10:00:00.000000Z"
        }
    }
    ```
*   **Notes:**
    *   Le `user_type_id` est crucial pour déterminer le rôle de l'utilisateur. Utilisez les endpoints `GET /api/user-types`, `GET /api/organization-types`, et `GET /api/business-sectors` pour obtenir les IDs valides.
    *   Les champs `organization_name`, `organization_type_id`, `business_sector_id`, et `organization_address` sont **obligatoires** si `user_type_id` est celui d'un producteur.

### 2. Connexion d'un utilisateur
Permet à un utilisateur de se connecter et d'obtenir un token d'authentification.

*   **Endpoint:** `POST /api/login`
*   **Authentification:** Aucune
*   **Headers:** `Content-Type: application/json`
*   **Corps de la requête:**
    ```json
    {
        "email": "john.doe@example.com",
        "password": "password123"
    }
    ```
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Authentification réussie",
        "token": "YOUR_SANCTUM_TOKEN",
        "user": {
            "id": 1,
            "firstName": "John",
            "lastName": "Doe",
            "email": "john.doe@example.com",
            // ... autres détails de l'utilisateur (le mot de passe est masqué)
        }
    }
    ```
*   **Réponse (Échec - 401 Unauthorized):**
    ```json
    {
        "message": "Identifiants invalides"
    }
    ```
*   **Notes:** Le token retourné doit être utilisé dans l'en-tête `Authorization: Bearer {token}` pour les requêtes authentifiées.

### 3. Déconnexion de l'utilisateur
Invalide le token d'authentification de l'utilisateur connecté.

*   **Endpoint:** `POST /api/v1/logout`
*   **Authentification:** Requise (Bearer Token)
*   **Headers:** `Content-Type: application/json` (peut être vide)
*   **Corps de la requête:** Aucun corps de requête n'est nécessaire pour cet endpoint.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Déconnexion réussie"
    }
    ```
*   **Réponse (Échec - 401 Unauthorized):**
    ```json
    {
        "message": "Aucun utilisateur authentifié"
    }
    ```

### 4. Liste de tous les utilisateurs
Récupère une liste paginée de tous les utilisateurs du système.

*   **Endpoint:** `GET /api/v1/users`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Seuls les administrateurs peuvent accéder à cet endpoint.
*   **Paramètres de requête (optionnels):**
    *   `per_page` (integer): Nombre d'utilisateurs par page (par défaut: 15).
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Listes des utilisateur recupéré avec succès",
        "users": {
            "current_page": 1,
            "data": [
                {
                    "id": 1,
                    "firstName": "Admin",
                    "lastName": "System",
                    "email": "admin@vetlink.com",
                    // ... autres détails de l'utilisateur
                }
            ],
            "first_page_url": "...",
            "from": 1,
            "last_page": 1,
            "last_page_url": "...",
            "links": [...],
            "next_page_url": null,
            "path": "...",
            "per_page": 15,
            "prev_page_url": null,
            "to": 1,
            "total": 1
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 5. Afficher un utilisateur spécifique
Récupère les détails d'un utilisateur par son ID.

*   **Endpoint:** `GET /api/v1/users/{id}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté peut voir son propre profil. Les administrateurs peuvent voir n'importe quel profil. Les modérateurs peuvent voir n'importe quel profil.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "id": 1,
        "firstName": "John",
        "lastName": "Doe",
        "email": "john.doe@example.com",
        // ... autres détails de l'utilisateur (le mot de passe est masqué)
    }
    ```
*   **Réponse (Échec - 404 Not Found):**
    ```json
    {
        "message": "Utilisateur non trouvé"
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 6. Mettre à jour un utilisateur
Met à jour les informations d'un utilisateur spécifique.

*   **Endpoint:** `PUT /api/v1/users/{id}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté peut mettre à jour son propre profil. Les administrateurs peuvent mettre à jour n'importe quel profil.
*   **Headers:** `Content-Type: application/json`
*   **Corps de la requête:** (Les champs sont optionnels, seuls ceux à modifier sont envoyés)
    ```json
    {
        "firstName": "Jean",
        "tel1": "0123456789"
    }
    ```
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Utilisateur mis à jour",
        "user": {
            "id": 1,
            "firstName": "Jean",
            // ... autres détails de l'utilisateur mis à jour
        }
    }
    ```
*   **Réponse (Échec - 404 Not Found):**
    ```json
    {
        "message": "Utilisateur non trouvé"
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 7. Supprimer un utilisateur
Supprime un utilisateur spécifique du système.

*   **Endpoint:** `DELETE /api/v1/users/{id}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Seuls les administrateurs peuvent supprimer des utilisateurs.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Utilisateur supprimé avec succès"
    }
    ```
*   **Réponse (Échec - 404 Not Found):**
    ```json
    {
        "message": "Utilisateur non trouvé"
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 8. Mettre à jour la photo de profil
Permet à l'utilisateur connecté de télécharger et de mettre à jour sa photo de profil.

*   **Endpoint:** `POST /api/v1/profile-photo`
*   **Authentification:** Requise (Bearer Token)
*   **Headers:** `Content-Type: multipart/form-data`
*   **Corps de la requête:**
    ```
    // Exemple d'envoi d'une image via un formulaire multipart
    photo: [fichier image]
    ```
*   **Validation:**
    *   `photo`: requis, image, mimes: jpeg, png, jpg, webp, max: 2048KB, dimensions: min_width=100, min_height=100, max_width=2000, max_height=2000.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "photo_url": "http://your-app.com/storage/profile-photos/user-1-1678886400.jpg",
        "message": "Photo de profil mise à jour avec succès"
    }
    ```
*   **Réponse (Échec - 500 Internal Server Error):**
    ```json
    {
        "error": "Échec de la mise à jour de la photo"
    }
    ```

### 9. Obtenir la progression du profil
Récupère la progression du profil d'un utilisateur spécifique.

*   **Endpoint:** `GET /api/v1/profile-progress/{user_id}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté peut voir sa propre progression. Les administrateurs peuvent voir n'importe quelle progression.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        // Structure de la progression du profil
    }
    ```
*   **Réponse (Échec - 404 Not Found):**
    ```json
    {
        "message": "Utilisateur non trouvé"
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 10. Mettre à jour/Recalculer la progression du profil
Déclenche le recalcul et la mise à jour de la progression du profil d'un utilisateur.

*   **Endpoint:** `PUT /api/v1/profile-progress/{id}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté peut déclencher le recalcul pour son propre profil. Les administrateurs peuvent déclencher le recalcul pour n'importe quel profil.
*   **Headers:** `Content-Type: application/json` (le corps de la requête peut être vide ou contenir des données non utilisées directement par cette méthode)
*   **Corps de la requête:** Aucun corps de requête n'est nécessaire pour cette opération, car elle déclenche un recalcul basé sur les données existantes de l'utilisateur.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Progression du profil mise à jour avec succès",
        "progress": {
            // Nouvelle structure de la progression du profil
        }
    }
    ```
*   **Réponse (Échec - 404 Not Found):**
    ```json
    {
        "message": "Utilisateur non trouvé"
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Notes:** Cette méthode recalcule la progression du profil. La mise à jour des données du profil de l'utilisateur doit être effectuée via l'endpoint `PUT /api/v1/users/{id}`.

---

## 🛒 APIs Publiques (Non Authentifiées)

### 1. Liste des produits
Récupère une liste paginée de tous les produits disponibles.

*   **Endpoint:** `GET /api/products`
*   **Authentification:** Aucune
*   **Paramètres de requête (optionnels):**
    *   `page` (integer): Numéro de la page à récupérer (par défaut: 1).
    *   `categorie_id` (integer): Filtrer par ID de catégorie.
    *   `producer_id` (integer): Filtrer par ID de producteur.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Liste des produits récupérée avec succès",
        "data": [
            {
                "id": 1,
                "name": "Lait Bio Frais",
                "description": "...",
                "price": 2.50,
                "measure": "L",
                "isbio": true,
                "categorie": { "id": 1, "name": "Lait" },
                "producer": { "id": 1, "firstName": "Jean", "lastName": "Dupont" },
                "images": [ { "id": 1, "path": "http://your-app.com/storage/produits/image1.jpg" } ]
            }
        ],
        "meta": {
            "current_page": 1,
            "from": 1,
            "last_page": 1,
            "path": "...",
            "per_page": 15,
            "to": 1,
            "total": 1
        }
    }
    ```

### 2. Détails d'un produit
Récupère les détails d'un produit spécifique par son ID.

*   **Endpoint:** `GET /api/products/{id}`
*   **Authentification:** Aucune
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Détails du produit récupérés avec succès",
        "data": {
            "id": 1,
            "name": "Lait Bio Frais",
            "description": "...",
            "price": 2.50,
            "measure": "L",
            "isbio": true,
            "categorie": { "id": 1, "name": "Lait" },
            "producer": { "id": 1, "firstName": "Jean", "lastName": "Dupont" },
            "images": [ { "id": 1, "path": "http://your-app.com/storage/produits/image1.jpg" } ]
        }
    }
    ```
*   **Réponse (Échec - 404 Not Found):**
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

### 3. Catégories disponibles
Récupère la liste de toutes les catégories de produits.

*   **Endpoint:** `GET /api/categories`
*   **Authentification:** Aucune
*   **Réponse (Succès - 200 OK):**
    ```json
    [
        {
            "id": 1,
            "name": "Lait",
            "created_at": "2025-06-13T00:00:00.000000Z",
            "updated_at": "2025-06-13T00:00:00.000000Z"
        },
        // ... autres catégories
    ]
    ```

### 4. Types d'utilisateurs disponibles
Récupère la liste des types d'utilisateurs (par exemple, Client, Producteur) qui peuvent s'inscrire.

*   **Endpoint:** `GET /api/user-types`
*   **Authentification:** Aucune
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Types d'utilisateurs récupérés avec succès",
        "data": [
            {
                "id": 3,
                "title": "Producteur",
                "created_at": "...",
                "updated_at": "..."
            },
            {
                "id": 4,
                "title": "Client",
                "created_at": "...",
                "updated_at": "..."
            }
        ]
    }
    ```

### 5. Types d'organisations disponibles
Récupère la liste des types d'organisations (par exemple, Ferme, Coopérative).

*   **Endpoint:** `GET /api/organization-types`
*   **Authentification:** Aucune
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "data": [
            {
                "id": 1,
                "name": "Ferme",
                "created_at": "...",
                "updated_at": "..."
            },
            // ... autres types d'organisations
        ]
    }
    ```

### 6. Secteurs d'activité disponibles
Récupère la liste des secteurs d'activité (par exemple, Agriculture, Élevage).

*   **Endpoint:** `GET /api/business-sectors`
*   **Authentification:** Aucune
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "data": [
            {
                "id": 1,
                "name": "Agriculture",
                "created_at": "...",
                "updated_at": "..."
            },
            // ... autres secteurs d'activité
        ]
    }
    ```

### 7. Statuts disponibles
Récupère la liste de tous les statuts possibles (par exemple, En attente, En cours, Validé).

*   **Endpoint:** `GET /api/statuses`
*   **Authentification:** Aucune
*   **Réponse (Succès - 200 OK):**
    ```json
    [
        {
            "id": 1,
            "name": "En attente",
            "created_at": "...",
            "updated_at": "..."
        },
        {
            "id": 2,
            "name": "En cours",
            "created_at": "...",
            "updated_at": "..."
        },
        // ... et ainsi de suite pour tous les autres statuts.
    ]
    ```

---

## 👨‍🌾 APIs Producteurs (Authentifiées)

### 1. Profil du producteur connecté
Récupère les détails du profil du producteur actuellement authentifié.

*   **Endpoint:** `GET /api/v1/producer/profile`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un producteur.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Profil producteur récupéré avec succès",
        "data": {
            "id": 1,
            "full_name": "Jean Dupont",
            "email": "jean.dupont@fermebio.fr",
            "tel1": "0234567891",
            "tel2": "0876543219",
            "address": "...",
            "user_type": "Producteur",
            "organization": "Ma Ferme Bio",
            "created_at": "..."
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 2. Mes produits
Récupère une liste paginée de tous les produits créés par le producteur connecté.

*   **Endpoint:** `GET /api/v1/producer/products`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un producteur.
*   **Paramètres de requête (optionnels):**
    *   `page` (integer): Numéro de la page à récupérer (par défaut: 1).
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Produits du producteur récupérés avec succès",
        "data": [
            {
                "id": 1,
                "name": "Pommes Bio",
                // ... détails du produit
            }
        ],
        "meta": {
            "current_page": 1,
            // ... métadonnées de pagination
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 3. Créer un produit
Permet au producteur connecté de créer un nouveau produit.

*   **Endpoint:** `POST /api/v1/producer/products`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un producteur.
*   **Headers:** `Content-Type: multipart/form-data`
*   **Corps de la requête:**
    ```
    // Exemple d'envoi d'un produit avec une image
    name: "Carottes Bio",
    description: "Carottes fraîches de saison, cultivées biologiquement.",
    categorie_id: 2, // ID de la catégorie 'Légumes'
    quantity: 50.0,
    price: 1.80,
    measure: "kg",
    isbio: true,
    images[]: [fichier image de carottes.jpg]

    // Exemple d'envoi d'un produit sans image
    name: "Lait de Chèvre",
    description: "Lait frais de chèvre, pasteurisé.",
    categorie_id: 1, // ID de la catégorie 'Lait'
    quantity: 10.0,
    price: 3.20,
    measure: "L",
    isbio: false
    ```
*   **Validation:**
    *   `name`: requis, string, max: 255.
    *   `description`: nullable, string.
    *   `categorie_id`: requis, exists:categories,id.
    *   `quantity`: requis, numeric, min: 0.
    *   `price`: requis, numeric, min: 0.
    *   `measure`: requis, in: kg,g,L,unité.
    *   `isbio`: boolean.
    *   `images.*`: nullable, image, mimes: jpeg, png, jpg, max: 2048KB.
*   **Réponse (Succès - 201 Created):**
    ```json
    {
        "message": "Produit créé avec succès",
        "data": {
            "id": 1,
            "name": "Carottes Bio",
            "description": "Carottes fraîches de saison, cultivées biologiquement.",
            "categorie_id": 2,
            "producer_id": 1, // ID du producteur connecté
            "quantity": 50.0,
            "price": 1.80,
            "measure": "kg",
            "isbio": true,
            "created_at": "2025-07-30T10:00:00.000000Z",
            "updated_at": "2025-07-30T10:00:00.000000Z",
            "categorie": { "id": 2, "name": "Légumes" },
            "images": [ { "id": 1, "path": "http://your-app.com/storage/produits/carottes.jpg" } ]
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 422 Unprocessable Entity):**
    ```json
    {
        "message": "The given data was invalid.",
        "errors": {
            "name": ["The name field is required."]
        }
    }
    ```

### 4. Mettre à jour un produit
Permet au producteur connecté de mettre à jour un de ses produits.

*   **Endpoint:** `PUT /api/v1/producer/products/{id}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un producteur et propriétaire du produit. Les administrateurs peuvent mettre à jour n'importe quel produit.
*   **Headers:** `Content-Type: application/json`
*   **Corps de la requête:** (Les champs sont optionnels, seuls ceux à modifier sont envoyés)
    ```json
    {
        "name": "Pommes Bio Premium",
        "price": 3000,
        "quantity": 75.0
        
    }
    ```
*   **Validation:**
    *   `name`: sometimes, string, max: 255.
    *   `description`: nullable, string.
    *   `categorie_id`: sometimes, exists:categories,id.
    *   `quantity`: sometimes, numeric, min: 0.
    *   `price`: sometimes, numeric, min: 0.
    *   `measure`: sometimes, in: kg,g,L,unité.
    *   `isbio`: boolean.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Produit mis à jour avec succès",
        "data": {
            "id": 1,
            "name": "Pommes Bio Premium",
            // ... détails du produit mis à jour
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):**
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

### 5. Ajouter des images à un produit
Permet au producteur connecté d'ajouter une ou plusieurs images à un produit existant.

*   **Endpoint:** `POST /api/v1/producer/products/{id}/images`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un producteur et propriétaire du produit.
*   **Headers:** `Content-Type: multipart/form-data`
*   **Corps de la requête:**
    ```
    images[]: [fichier image1.jpg]
    images[]: [fichier image2.png]
    ```
*   **Validation:**
    *   `images`: requis, tableau.
    *   `images.*`: requis, image, mimes: jpeg, png, jpg, max: 2048KB.
*   **Réponse (Succès - 201 Created):**
    ```json
    {
        "message": "Images ajoutées avec succès",
        "data": [
            {
                "id": 2,
                "name": "image1.jpg",
                "path": "http://your-app.com/storage/produits/image1.jpg"
            }
        ]
    }
    ```

### 6. Supprimer une image d'un produit
Permet au producteur connecté de supprimer une image spécifique d'un de ses produits.

*   **Endpoint:** `DELETE /api/v1/producer/products/{id}/images/{image_id}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un producteur et propriétaire du produit.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Image supprimée avec succès"
    }
    ```
*   **Notes:** Cette action supprime également le fichier image du stockage.

### 7. Mes commandes reçues
Permet au producteur connecté de supprimer un de ses produits.

*   **Endpoint:** `DELETE /api/v1/producer/products/{id}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un producteur et propriétaire du produit. Les administrateurs peuvent supprimer n'importe quel produit.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Produit supprimé avec succès"
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):**
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```
*   **Notes:** Cette action supprime également toutes les images associées au produit du stockage.

### 6. Mes commandes reçues
Récupère une liste paginée de toutes les commandes passées pour les produits du producteur connecté.

*   **Endpoint:** `GET /api/v1/producer/orders`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un producteur.
*   **Paramètres de requête (optionnels):**
    *   `page` (integer): Numéro de la page à récupérer (par défaut: 1).
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Commandes récupérées avec succès",
        "data": [
            {
                "id": 1,
                "num": "CMD-ABCDEFGH",
                "total_price": 25.00,
                "status": 1, // ID du statut (ex: 1 pour 'En cours')
                "customer": { "id": 2, "firstName": "Marie" },
                "produit": { "id": 1, "name": "Lait Bio Frais" }
                // ... autres détails de la commande
            }
        ],
        "meta": {
            "current_page": 1,
            // ... métadonnées de pagination
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 7. Statistiques du producteur
Récupère des statistiques clés pour le producteur connecté (nombre de produits, commandes, revenus, etc.).

*   **Endpoint:** `GET /api/v1/producer/statistics`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un producteur.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Statistiques récupérées avec succès",
        "data": {
            "total_products": 10,
            "total_orders": 50,
            "total_revenue": 1250.75,
            "pending_orders": 5
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 8. Détails d'une commande spécifique (pour producteur)
Récupère les détails d'une commande spécifique.

*   **Endpoint:** `GET /api/v1/producer/orders/{order}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un producteur et la commande doit contenir un de ses produits. Les administrateurs peuvent voir n'importe quelle commande.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Détails de la commande récupérés avec succès",
        "data": {
            "id": 1,
            "num": "CMD-ABCDEFGH",
            // ... détails complets de la commande
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):**
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

### 9. Mettre à jour le statut d'une commande (pour producteur)
Permet au producteur de mettre à jour le statut d'une commande de son produit.

*   **Endpoint:** `PUT /api/v1/producer/orders/{order}/status`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un producteur et la commande doit contenir un de ses produits. Les administrateurs peuvent mettre à jour n'importe quelle commande.
*   **Headers:** `Content-Type: application/json`
*   **Corps de la requête:**
    ```json
    {
        "status": 2 // ID du nouveau statut (ex: 2 pour 'En cours', voir GET /api/statuses)
    }
    ```
*   **Validation:**
    *   `status`: requis, integer, doit être un ID valide de la table `statuses`.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Statut de la commande mis à jour avec succès",
        "data": {
            "id": 1,
            "status": 2,
            // ... détails de la commande mise à jour
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):**
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):**
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

---

## 🛍️ APIs Clients (Authentifiées)

### 1. Profil du client connecté
Récupère les détails du profil du client actuellement authentifié.

*   **Endpoint:** `GET /api/v1/customer/profile`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un client.
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Profil client récupéré avec succès",
        "data": {
            "id": 1,
            "full_name": "Marie Martin",
            "email": "marie.martin@example.com",
            // ... autres détails du client
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 2. Rechercher et filtrer les produits
Recherche et filtre les produits disponibles.

*   **Endpoint:** `GET /api/v1/customer/search-products`
*   **Authentification:** Requise (Bearer Token)
*   **Paramètres de requête (optionnels):}
    *   `search` (string): Recherche par nom ou description du produit.
    *   `categorie_id` (integer): Filtrer par ID de catégorie.
    *   `min_price` (numeric): Prix minimum.
    *   `max_price` (numeric): Prix maximum.
    *   `isbio` (boolean): Filtrer par produits bio (true/false).
    *   `sort_by` (string): Champ pour le tri (ex: `price`, `name`, `created_at`).
    *   `sort_order` (string): Ordre de tri (`asc` ou `desc`).
    *   `page` (integer): Numéro de la page à récupérer (par défaut: 1).
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Produits récupérés avec succès",
        "data": [
            {
                "id": 1,
                "name": "Pommes Bio",
                // ... détails du produit
            }
        ],
        "meta": {
            "current_page": 1,
            // ... métadonnées de pagination
        }
    }
    ```

### 3. Détails d'un produit (pour client)
Récupère les détails d'un produit spécifique par son ID.

*   **Endpoint:** `GET /api/v1/customer/products/{product}
*   **Authentification:** Requise (Bearer Token)
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Détails du produit récupérés avec succès",
        "data": {
            "id": 1,
            "name": "Lait Bio Frais",
            // ... détails du produit
        }
    }
    ```
*   **Réponse (Échec - 404 Not Found):}
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

### 4. Passer une commande
Permet au client connecté de passer une nouvelle commande pour un ou plusieurs produits.

*   **Endpoint:** `POST /api/v1/customer/orders`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un client.
*   **Headers:** `Content-Type: application/json`
*   **Corps de la requête:**
    ```json
    {
        "products": [
            {
                "product_id": 1,
                "quantity": 2
            },
            {
                "product_id": 5,
                "quantity": 1
            }
        ]
    }
    ```
*   **Réponse (Succès - 201 Created):**
    ```json
    {
        "message": "Commande créée avec succès",
        "data": {
            "id": 1,
            "num": "CMD-XYZ12345",
            "total_price": 52.50,
            "status": 1,
            "delivery_status": 1,
            "payment": 0,
            "customer_id": 1,
            "created_at": "2025-07-31T12:00:00.000000Z",
            "updated_at": "2025-07-31T12:00:00.000000Z",
            "products": [
                {
                    "id": 1,
                    "name": "Lait Bio Frais",
                    "pivot": {
                        "quantity": 2
                    }
                },
                {
                    "id": 5,
                    "name": "Oeufs Plein Air",
                    "pivot": {
                        "quantity": 1
                    }
                }
            ]
        }
    }
    ```
*   **Notes:**
    *   Cette opération est atomique (transactionnelle).

### 5. Historique des commandes
Récupère une liste paginée de toutes les commandes passées par le client connecté.

*   **Endpoint:** `GET /api/v1/customer/orders`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un client.
*   **Paramètres de requête (optionnels):}
    *   `page` (integer): Numéro de la page à récupérer (par défaut: 1).
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Historique des commandes récupéré avec succès",
        "data": [
            {
                "id": 1,
                "num": "CMD-ABCDEFGH",
                // ... détails de la commande
            }
        ],
        "meta": {
            "current_page": 1,
            // ... métadonnées de pagination
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 6. Détails d'une commande spécifique (pour client)
Récupère les détails d'une commande spécifique du client connecté.

*   **Endpoint:** `GET /api/v1/customer/orders/{order}
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un client et propriétaire de la commande. Les administrateurs peuvent voir n'importe quelle commande.
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Détails de la commande récupérés avec succès",
        "data": {
            "id": 1,
            "num": "CMD-ABCDEFGH",
            // ... détails complets de la commande
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):}
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

### 7. Annuler une commande
Permet au client connecté d'annuler une de ses commandes.

*   **Endpoint:** `PUT /api/v1/customer/orders/{order}/cancel`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un client et propriétaire de la commande.
*   **Headers:** `Content-Type: application/json` (le corps de la requête peut être vide)
*   **Corps de la requête:** Aucun corps de requête n'est nécessaire pour cette opération, l'annulation est déclenchée par l'appel à l'endpoint.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Commande annulée avec succès"
    }
    ```
*   **Notes:** La logique d'annulation (vérification du statut, remise en stock, notifications) est implémentée.

### 8. Produits recommandés
Récupère une liste de produits recommandés pour le client connecté.

*   **Endpoint:** `GET /api/v1/customer/recommended-products`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un client.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Produits recommandés récupérés avec succès",
        "data": [
            {
                "id": 1,
                "name": "Produit Recommandé 1",
                // ... détails du produit
            }
        ]
    }
    ```
*   **Notes:** La logique de recommandation est basée sur les produits les plus populaires (les plus commandés).

### 9. Commandes du jour
Récupère les commandes passées par le client connecté aujourd'hui.

*   **Endpoint:** `GET /api/v1/customer/orders/today`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un client.
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Commandes du jour récupérées avec succès",
        "count": 2,
        "data": [
            {
                "id": 1,
                "num": "CMD-TODAY1",
                // ... détails de la commande
            }
        ]
    }
    ```
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

### 10. Commandes en cours
Récupère les commandes du client connecté qui ne sont pas encore terminées ou annulées.

*   **Endpoint:** `GET /api/v1/customer/orders/current`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un client.
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Commandes en cours récupérées avec succès",
        "count": 3,
        "data": [
            {
                "id": 1,
                "num": "CMD-CURRENT1",
                // ... détails de la commande
            }
        ]
    }
    ```
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```

---

## 💬 APIs Chat (Authentifiées)

### 1. Liste des conversations
Récupère une liste paginée des conversations de l'utilisateur connecté.

*   **Endpoint:** `GET /api/chat/conversations`
*   **Authentification:** Requise (Bearer Token)
*   **Paramètres de requête (optionnels):}
    *   `filter` (string): `unread` pour filtrer les conversations avec des messages non lus.
    *   `with_role` (string): `Producteur` pour filtrer les conversations avec des producteurs.
    *   `per_page` (integer): Nombre de conversations par page (par défaut: 15).
*   **Réponse (Succès - 200 OK):}
    ```json
    [
        {
            "id": 1,
            "product_id": null,
            "order_id": null,
            "created_at": "...",
            "updated_at": "...",
            "users": [
                { "id": 1, "firstName": "John", "lastName": "Doe", "user_type": { "title": "Client" } },
                { "id": 2, "firstName": "Jane", "lastName": "Smith", "user_type": { "title": "Producteur" } }
            ]
        }
    ]
    ```

### 2. Messages d'une conversation
Récupère une liste paginée des messages d'une conversation spécifique.

*   **Endpoint:** `GET /api/chat/conversations/{conversation}/messages`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un participant de la conversation.
*   **Paramètres de requête (optionnels):}
    *   `per_page` (integer): Nombre de messages par page (par défaut: 15).
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Messages récupérés avec succès",
        "data": [
            {
                "id": 1,
                "content": "Bonjour, vos tomates sont-elles disponibles ?",
                "user": { "id": 1, "firstName": "John" },
                "conversation_id": 1,
                "attachment": null,
                "read_at": null,
                "is_read": false,
                "created_at": "...",
                "updated_at": "..."
            },
            {
                "id": 2,
                "content": "Voici une photo de nos tomates.",
                "user": { "id": 2, "firstName": "Jane" },
                "conversation_id": 1,
                "attachment": {
                    "filename": "tomates.jpg",
                    "path": "chat_files/images/1678886400.jpg",
                    "type": "image",
                    "download_url": "http://your-app.com/api/chat/download/2"
                },
                "read_at": null,
                "is_read": false,
                "created_at": "...",
                "updated_at": "..."
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 2
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):}
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

### 3. Envoyer un message
Permet d'envoyer un message (texte, image, vidéo, document) dans une conversation.

*   **Endpoint:** `POST /api/chat/conversations/{conversation}/messages`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un participant de la conversation.
*   **Headers:** `Content-Type: multipart/form-data`
*   **Corps de la requête:**
    ```
    // Exemple 1: Envoi d'un message texte simple
    body: "Bonjour, j'ai une question sur votre produit."

    // Exemple 2: Envoi d'une image avec une légende optionnelle
    body: "Voici une photo de mon animal." // Optionnel
    file: [fichier image.jpg] // Le fichier image à télécharger

    // Exemple 3: Envoi d'une vidéo
    file: [fichier video.mp4] // Le fichier vidéo à télécharger

    // Exemple 4: Envoi d'un document
    file: [fichier document.pdf] // Le fichier document à télécharger
    ```
*   **Validation:**
    *   `body`: requis si `file` est absent, nullable, string, max: 4096.
    *   `file`: requis si `body` est absent, nullable, fichier, mimes: jpeg, png, jpg, gif, svg, mp4, mov, avi, pdf, doc, docx, xls, xlsx, max: 25600KB (25MB).
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "id": 1,
        "conversation_id": 1,
        "sender_id": 1,
        "message": "Votre message ou légende",
        "attachment_type": "image",
        "attachment_path": "chat_files/images/1678886400.jpg",
        "file_name": "image.jpg",
        "read_at": null,
        "created_at": "...",
        "updated_at": "...",
        "user": { "id": 1, "firstName": "John" }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):}
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

### 4. Démarrer une nouvelle conversation
Crée une nouvelle conversation entre l'utilisateur connecté et un autre utilisateur, potentiellement liée à un produit ou une commande.

*   **Endpoint:** `POST /api/chat/conversations/start`
*   **Authentification:** Requise (Bearer Token)
*   **Headers:** `Content-Type: application/json`
*   **Corps de la requête:**
    ```json
    // Exemple 1: Démarrer une conversation simple avec un autre utilisateur
    {
        "user_id": 2 // ID de l'autre utilisateur avec qui démarrer la conversation
    }

    // Exemple 2: Démarrer une conversation liée à un produit
    {
        "user_id": 3, // ID du producteur du produit
        "product_id": 1 // ID du produit concerné
    }

    // Exemple 3: Démarrer une conversation liée à une commande
    {
        "user_id": 4, // ID du client ou du producteur de la commande
        "order_id": 5 // ID de la commande concernée
    }
    ```
*   **Validation:**
    *   `user_id`: requis, exists:users,id.
    *   `product_id`: nullable, exists:produits,id.
    *   `order_id`: nullable, exists:commandes,id.
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "id": 3,
        "product_id": 1,
        "order_id": null,
        "created_at": "...",
        "updated_at": "...",
        "users": [
            { "id": 1, "firstName": "John" },
            { "id": 2, "firstName": "Jane" }
        ]
    }
    ```
*   **Notes:** Il est recommandé au frontend de vérifier si une conversation existe déjà entre les participants (et pour le même produit/commande si applicable) avant d'appeler cet endpoint pour éviter les doublons.

### 5. Marquer un message comme lu
Marque un message spécifique comme lu par l'utilisateur connecté.

*   **Endpoint:** `POST /api/chat/messages/{message}/read`
*   **Authentification:** Requise (Bearer Token)
*   **Headers:** `Content-Type: application/json` (peut être vide)
*   **Corps de la requête:** Aucun corps de requête n'est nécessaire pour cette opération, l'ID du message est dans l'URL.
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Message marqué comme lu."
    }
    ```
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):}
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

---

## 🔔 APIs Notifications (Authentifiées)

### 1. Liste des notifications
Récupère une liste des notifications de l'utilisateur connecté.

*   **Endpoint:** `GET /api/v1/notifications`
*   **Authentification:** Requise (Bearer Token)
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Notifications récupérées avec succès",
        "data": {
            "current_page": 1,
            "data": [
                {
                    "id": "uuid-de-la-notification",
                    "type": "App\\Notifications\\OrderStatusUpdated",
                    "notifiable_type": "App\\Models\\User",
                    "notifiable_id": 1,
                    "data": {
                        "order_num": "CMD-12345",
                        "status": "expédiée"
                    },
                    "read_at": null,
                    "created_at": "2025-07-31T12:00:00.000000Z",
                    "updated_at": "2025-07-31T12:00:00.000000Z"
                }
            ],
            // ... métadonnées de pagination
        }
    }
    ```

### 2. Marquer une notification comme lue
Marque une notification spécifique comme lue.

*   **Endpoint:** `PUT /api/v1/notifications/mark-as-read`
*   **Authentification:** Requise (Bearer Token)
*   **Headers:** `Content-Type: application/json`
*   **Corps de la requête:**
    ```json
    {
        "notification_id": "uuid-de-la-notification" // ID de la notification à marquer comme lue
    }
    ```
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Notification marquée comme lue"
    }
    ```

### 3. Marquer toutes les notifications comme lues
Marque toutes les notifications de l'utilisateur connecté comme lues.

*   **Endpoint:** `PUT /api/v1/notifications/mark-all-as-read`
*   **Authentification:** Requise (Bearer Token)
*   **Headers:** `Content-Type: application/json` (peut être vide)
*   **Corps de la requête:** Aucun corps de requête n'est nécessaire pour cette opération.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Toutes les notifications marquées comme lues"
    }
    ```

### 4. Supprimer une notification
Supprime une notification spécifique.

*   **Endpoint:** `DELETE /api/v1/notifications`
*   **Authentification:** Requise (Bearer Token)
*   **Headers:** `Content-Type: application/json`
*   **Corps de la requête:**
    ```json
    {
        "notification_id": "uuid-de-la-notification" // ID de la notification à supprimer
    }
    ```
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Notification supprimée"
    }
    ```

---

## ⭐ APIs Évaluations (Authentifiées)

### 1. Évaluations d'un produit
Récupère toutes les évaluations pour un produit spécifique.

*   **Endpoint:** `GET /api/v1/reviews/products/{product}
*   **Authentification:** Requise (Bearer Token)
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Évaluations récupérées avec succès",
        "data": [
            {
                "id": 1,
                "user_id": 1,
                "product_id": 1,
                "rating": 5,
                "comment": "Excellent produit, très frais !",
                "created_at": "...",
                "updated_at": "...",
                "user": { "id": 1, "firstName": "Jean" }
            }
        ]
    }
    ```
*   **Réponse (Échec - 404 Not Found):}
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

### 2. Ajouter une évaluation
Permet à un client d'ajouter une évaluation pour un produit qu'il a acheté.

*   **Endpoint:** `POST /api/v1/reviews`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un client et doit avoir acheté le produit (commande validée, expédiée ou livrée).
*   **Headers:** `Content-Type: application/json`
*   **Corps de la requête:**
    ```json
    {
        "product_id": 1, // ID du produit à évaluer
        "rating": 5,     // Note de 1 à 5 (entier)
        "comment": "Excellent produit, très frais et conforme à la description !" // Commentaire optionnel, max 500 caractères
    }
    ```
*   **Validation:**
    *   `product_id`: requis, exists:produits,id.
    *   `rating`: requis, integer, min:1, max:5.
    *   `comment`: nullable, string, max:500.
*   **Réponse (Succès - 201 Created):}
    ```json
    {
        "message": "Évaluation ajoutée avec succès",
        "data": {
            "id": 1,
            "user_id": 1,
            "product_id": 1,
            "rating": 5,
            "comment": "Excellent produit, très frais !",
            "created_at": "2025-07-30T10:00:00.000000Z",
            "updated_at": "2025-07-30T10:00:00.000000Z"
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Vous devez avoir acheté ce produit pour le noter"
    }
    ```
*   **Réponse (Échec - 409 Conflict):}
    ```json
    {
        "message": "Vous avez déjà noté ce produit."
    }
    ```

### 3. Mettre à jour une évaluation
Permet à l'auteur d'une évaluation de la modifier.

*   **Endpoint:** `PUT /api/v1/reviews/{review}
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être l'auteur de l'évaluation.
*   **Headers:** `Content-Type: application/json`
*   **Corps de la requête:**
    ```json
    {
        "rating": 4, // Nouvelle note (de 1 à 5)
        "comment": "Très bon produit, mais la livraison a été un peu longue." // Nouveau commentaire (optionnel)
    }
    ```
*   **Validation:**
    *   `rating`: requis, integer, min:1, max:5.
    *   `comment`: nullable, string, max:500.
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Évaluation mise à jour avec succès",
        "data": {
            "id": 1,
            "rating": 4,
            "comment": "Très bon produit, mais la livraison a été un peu longue.",
            // ... autres détails de l'évaluation
        }
    }
    ```
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):}
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

### 4. Supprimer une évaluation
Permet à l'auteur d'une évaluation de la supprimer.

*   **Endpoint:** `DELETE /api/v1/reviews/{review}
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être l'auteur de l'évaluation ou un administrateur.
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "message": "Évaluation supprimée avec succès"
    }
    ```
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):}
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

---

## 📄 APIs Documents (Authentifiées)

### 1. Liste des documents
Récupère une liste paginée des documents téléchargés par l'utilisateur connecté.

*   **Endpoint:** `GET /api/documents`
*   **Authentification:** Requise (Bearer Token)
*   **Paramètres de requête (optionnels):}
    *   `per_page` (integer): Nombre de documents par page (par défaut: 15).
*   **Réponse (Succès - 200 OK):}
    ```json
    {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 1,
                "name": "mon_document.pdf",
                "path": "documents/user_1/unique_filename.pdf",
                "created_at": "...",
                "updated_at": "..."
            }
        ],
        // ... métadonnées de pagination
    }
    ```

### 2. Télécharger un document
Permet à l'utilisateur connecté de télécharger un ou plusieurs documents.

*   **Endpoint:** `POST /api/documents`
*   **Authentification:** Requise (Bearer Token)
*   **Headers:** `Content-Type: multipart/form-data`
*   **Corps de la requête:**
    ```
    // Exemple d'envoi d'un seul document
    documents[]: [fichier_rapport_annuel.pdf]

    // Exemple d'envoi de plusieurs documents
    documents[]: [fichier_contrat.docx]
    documents[]: [fichier_facture.xlsx]
    ```
*   **Validation:**
    *   `documents.*`: requis, fichier, mimes: pdf, doc, docx, xls, xlsx, max: 10240KB (10MB).
*   **Réponse (Succès - 201 Created):}
    ```json
    [
        {
            "id": 1,
            "user_id": 1,
            "name": "rapport_annuel.pdf",
            "path": "documents/user_1/unique_filename1.pdf",
            "created_at": "2025-07-30T10:00:00.000000Z",
            "updated_at": "2025-07-30T10:00:00.000000Z"
        },
        {
            "id": 2,
            "user_id": 1,
            "name": "contrat.docx",
            "path": "documents/user_1/unique_filename2.docx",
            "created_at": "2025-07-30T10:00:00.000000Z",
            "updated_at": "2025-07-30T10:00:00.000000Z"
        }
    ]
    ```

### 3. Afficher/Télécharger un document spécifique
Permet à l'utilisateur connecté de télécharger un document spécifique.

*   **Endpoint:** `GET /api/documents/{document}
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être le propriétaire du document.
*   **Réponse (Succès - Fichier):} Le fichier est directement téléchargé.
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):}
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```

### 4. Supprimer un document
Supprime un document spécifique de l'utilisateur connecté.

*   **Endpoint:** `DELETE /api/documents/{document}
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être le propriétaire du document.
*   **Réponse (Succès - 204 No Content):} (Aucun contenu retourné)
*   **Réponse (Échec - 403 Forbidden):}
    ```json
    {
        "message": "Accès non autorisé"
    }
    ```
*   **Réponse (Échec - 404 Not Found):}
    ```json
    {
        "message": "Ressource non trouvée"
    }
    ```
*   **Notes:** Cette action supprime également le fichier physique du stockage.

---

## 📊 Codes de Statut HTTP

*   `200 OK` - La requête a réussi.
*   `201 Created` - La ressource a été créée avec succès.
*   `204 No Content` - La requête a réussi, mais il n'y a pas de contenu à retourner (souvent pour les suppressions).
*   `400 Bad Request` - La requête est mal formée ou contient des paramètres invalides.
*   `401 Unauthorized` - L'authentification est requise ou a échoué.
*   `403 Forbidden` - L'utilisateur n'a pas les permissions nécessaires pour accéder à la ressource ou effectuer l'action.
*   `404 Not Found` - La ressource demandée n'a pas été trouvée.
*   `409 Conflict` - La requête ne peut pas être complétée en raison d'un conflit avec l'état actuel de la ressource (par exemple, tentative de créer une ressource qui existe déjà).
*   `422 Unprocessable Entity` - La requête est bien formée mais n'a pas pu être suivie en raison d'erreurs sémantiques (souvent des erreurs de validation).
*   `500 Internal Server Error` - Une erreur inattendue s'est produite sur le serveur.

---

## 🔧 Formats de Réponse Standard

### Réponse de Succès
```json
{
    "message": "Description de l'opération réussie",
    "data": {
        // Données de la ressource ou de la collection
    }
}
```
*   **Note:** Certaines réponses (comme `GET /api/categories` ou les réponses paginées) peuvent avoir une structure légèrement différente, mais elles contiendront toujours les données attendues.

### Réponse d'Erreur (Validation)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": [
            "Message d'erreur pour ce champ."
        ],
        "another_field": [
            "Autre message d'erreur."
        ]
    }
}
```

### Réponse d'Erreur (Générique)
```json
{
    "message": "Description de l'erreur."
}
```

---

## 📱 Utilisation Mobile & Bonnes Pratiques

### Headers requis pour les requêtes authentifiées
```
Authorization: Bearer {token}
Content-Type: application/json // Pour les requêtes JSON
Accept: application/json
```
*   Pour les uploads de fichiers, utilisez `Content-Type: multipart/form-data`.

### Gestion des fichiers (Uploads)
*   Utiliser `multipart/form-data` pour les requêtes `POST` ou `PUT` qui incluent des fichiers.
*   **Images (produits, chat, profil):}
    *   Taille max: 2MB (pour profil et produits), 25MB (pour chat).
    *   Formats acceptés: JPEG, PNG, JPG, GIF, SVG, WEBP.
*   **Vidéos (chat):}
    *   Taille max: 25MB.
    *   Formats acceptés: MP4, MOV, AVI.
*   **Documents (chat, documents):}
    *   Taille max: 25MB (pour chat), 10MB (pour documents).
    *   Formats acceptés: PDF, DOC, DOCX, XLS, XLSX.

### Pagination
*   La plupart des endpoints qui retournent des listes de ressources supportent la pagination.
*   Utilisez le paramètre de requête `page` pour spécifier le numéro de page.
*   Utilisez le paramètre de requête `per_page` pour spécifier le nombre d'éléments par page (par défaut: 15).
*   La réponse inclut les métadonnées de pagination (`current_page`, `last_page`, `total`, `links`, etc.).



---


---

## 📦 APIs Commandes (Admin)

### 1. Lister toutes les commandes
Récupère une liste paginée de toutes les commandes du système.

*   **Endpoint:** `GET /api/v1/commande`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Administrateurs
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Liste des commandes récupérée avec succès",
        "data": [
            // ... liste des commandes
        ]
    }
    ```

### 2. Créer une commande
Crée une nouvelle commande.

*   **Endpoint:** `POST /api/v1/commande`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Administrateurs
*   **Corps de la requête:**
    ```json
    {
        "customer_id": 1,
        "product_id": 1,
        "Quantity": 2,
        "total_price": 50,
        "status": 0,
        "delivery_status": 0,
        "payment": 0
    }
    ```
*   **Réponse (Succès - 201 Created):**
    ```json
    {
        "message": "Commande créée avec succès",
        "data": {
            // ... détails de la commande
        }
    }
    ```

### 3. Afficher une commande
Récupère les détails d'une commande spécifique.

*   **Endpoint:** `GET /api/v1/{commande}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Administrateurs
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Détails de la commande récupérés avec succès",
        "data": {
            // ... détails de la commande
        }
    }
    ```

### 4. Mettre à jour une commande
Met à jour les informations d'une commande.

*   **Endpoint:** `PUT /api/v1/{commande}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Administrateurs
*   **Corps de la requête:**
    ```json
    {
        "status": 1
    }
    ```
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Commande mise à jour avec succès",
        "data": {
            // ... détails de la commande mise à jour
        }
    }
    ```

### 5. Supprimer une commande
Supprime une commande.

*   **Endpoint:** `DELETE /api/v1/{commande}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Administrateurs
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Commande supprimée avec succès"
    }
    ```

### 6. Mettre à jour le statut de livraison
Met à jour le statut de livraison d'une commande.

*   **Endpoint:** `PUT /api/v1/{commande}/delivery-status`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Administrateurs
*   **Corps de la requête:**
    ```json
    {
        "delivery_status": 1
    }
    ```
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Statut de livraison mis à jour avec succès"
    }
    ```

### 7. Mettre à jour le statut de paiement
Met à jour le statut de paiement d'une commande.

*   **Endpoint:** `PUT /api/v1/{commande}/payment-status`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Administrateurs
*   **Corps de la requête:**
    ```json
    {
        "payment_status": 1
    }
    ```
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Statut de paiement mis à jour avec succès"
    }
    ```

### 8. Lister les commandes d'un client
Récupère toutes les commandes d'un client spécifique.

*   **Endpoint:** `GET /api/v1/customer/{customerId}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Administrateurs
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Commandes du client récupérées avec succès",
        "data": [
            // ... liste des commandes
        ]
    }
    ```

---

## 🍎 APIs Produits (Admin)

### 1. Lister tous les produits
Récupère une liste paginée de tous les produits du système.

*   **Endpoint:** `GET /api/v1/produits`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Administrateurs
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Liste des produits récupérée avec succès",
        "data": [
            // ... liste des produits
        ]
    }
    ```

### 2. Créer un produit
Crée un nouveau produit.

*   **Endpoint:** `POST /api/v1/produits`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** Administrateurs
*   **Corps de la requête:**
    ```json
    {
        "name": "Nouveau Produit",
        "description": "Description du nouveau produit",
        "categorie_id": 1,
        "producer_id": 1,
        "quantity": 100,
        "price": 10,
        "measure": "unité",
        "isbio": false
    }
    ```
*   **Réponse (Succès - 201 Created):**
    ```json
    {
        "message": "Produit créé avec succès",
        "data": {
            // ... détails du produit
        }
    }
    ```

---

## 💬 APIs Chat (Authentifiées)

### 6. Quitter une conversation
Permet à un utilisateur de quitter une conversation.

*   **Endpoint:** `DELETE /api/chat/conversations/{conversation}`
*   **Authentification:** Requise (Bearer Token)
*   **Autorisation:** L'utilisateur connecté doit être un participant de la conversation.
*   **Réponse (Succès - 200 OK):**
    ```json
    {
        "message": "Vous avez quitté la conversation."
    }
    ```

Ceci conclut la documentation de l'API.

---

## 📱 Guide d'Implémentation Mobile (Flutter) - Notifications en Temps Réel avec WebSockets

Cette section est destinée à l'équipe de développement mobile pour les guider dans l'implémentation de la réception de notifications en temps réel via WebSockets.

Le backend utilise **Laravel Echo** et un serveur WebSocket compatible **Pusher** (comme Soketi ou le service Pusher lui-même) pour diffuser des événements sur des canaux privés.

### 1. Dépendances Requises

Ajoutez les paquets suivants à votre fichier `pubspec.yaml` :

```yaml
dependencies:
  flutter:
    sdk: flutter
  # Pour la communication WebSocket avec Laravel Echo
  laravel_echo: ^1.1.0
  pusher_client: ^2.0.0 # Ou une version plus récente

  # Pour afficher les notifications locales à l'utilisateur
  flutter_local_notifications: ^17.0.0 # Ou une version plus récente
```

N'oubliez pas d'exécuter `flutter pub get`.

### 2. Configuration du Client Echo

Il est recommandé de créer un service pour gérer la logique de notification. Ce service initialisera le client Echo et gérera la connexion.

**Variables d'environnement :** Les informations de connexion au serveur WebSocket (hôte, port, clé) doivent être récupérées depuis la configuration du backend. Demandez à l'équipe backend les valeurs pour :

*   `PUSHER_APP_KEY`
*   `PUSHER_HOST` (ex: `127.0.0.1`)
*   `PUSHER_PORT` (ex: `6001`)
*   `PUSHER_SCHEME` (ex: `http`)

**Exemple de `NotificationService.dart` :**

```dart
import 'package:laravel_echo/laravel_echo.dart';
import 'package:pusher_client/pusher_client.dart';

class NotificationService {
  late Echo echo;
  final String? userToken; // Le token d'authentification Sanctum de l'utilisateur
  final int? userId; // L'ID de l'utilisateur connecté

  NotificationService({required this.userToken, required this.userId});

  void init() {
    if (userToken == null || userId == null) {
      print('Error: User token or ID is null. Cannot initialize Echo.');
      return;
    }

    PusherClient pusherClient = PusherClient(
      'YOUR_PUSHER_APP_KEY', // À remplacer par la clé Pusher du backend
      PusherOptions(
        host: 'YOUR_PUSHER_HOST', // Hôte du serveur WebSocket
        port: 6001, // Port du serveur WebSocket
        encrypted: false, // Mettre à `true` si vous utilisez `https` et `wss`
        auth: PusherAuth(
          'http://YOUR_BACKEND_URL/broadcasting/auth', // URL d'authentification du backend
          headers: {
            'Authorization': 'Bearer $userToken',
            'Accept': 'application/json',
          },
        ),
      ),
      enableLogging: true, // Activez pour le débogage
    );

    echo = Echo(
      broadcaster: EchoBroadcasterType.Pusher,
      client: pusherClient,
    );

    print('NotificationService initialized.');
  }

  void listenForNotifications() {
    if (userId == null) return;

    // S'abonner au canal privé de l'utilisateur
    // Le nom du canal correspond à celui défini dans `routes/channels.php`
    echo.private('App.Models.User.$userId')
      .listen('.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', (e) {
        if (e != null) {
          print('Notification reçue: ${e.data}');
          // e.data contient les données de la notification
          // Exemple de données : { 'order_num': 'CMD-123', 'status': 'expédiée' }

          // TODO: Analyser les données et afficher une notification locale
          // final String title = 'Mise à jour de la commande';
          // final String body = 'Votre commande ${e.data['order_num']} est maintenant ${e.data['status']}.';
          // showLocalNotification(title, body);
        }
      });

    print('Listening on private channel: App.Models.User.$userId');
  }

  void disconnect() {
    echo.disconnect();
    print('Echo disconnected.');
  }
}
```
```

### 3. Écoute des Événements

*   **Canal d'écoute :** Comme défini dans `routes/channels.php`, chaque utilisateur a un canal privé : `App.Models.User.{id}`. Vous devez vous y abonner en utilisant l'ID de l'utilisateur connecté.

*   **Nom de l'événement :** Pour les notifications standards de Laravel, le nom de l'événement à écouter est `.Illuminate\Notifications\Events\BroadcastNotificationCreated`.

*   **Données de l'événement :** L'objet `e.data` reçu contiendra la charge utile de la notification que vous avez définie dans la méthode `toBroadcast` de votre classe de notification côté backend.

### 4. Affichage des Notifications Locales

Une fois qu'un événement est reçu via le WebSocket, utilisez le paquet `flutter_local_notifications` pour afficher une notification visible à l'utilisateur, même si l'application est en arrière-plan (mais toujours en cours d'exécution).

1.  **Initialisez `flutter_local_notifications`** au démarrage de votre application.
2.  **Créez une fonction** `showLocalNotification(String title, String body)`.
3.  **Appelez cette fonction** depuis le callback `.listen()` de votre client Echo.

### 5. Gestion du Cycle de Vie

*   **Initialisation :** Initialisez le `NotificationService` et appelez `init()` et `listenForNotifications()` après que l'utilisateur se soit connecté avec succès.
*   **Déconnexion :** Appelez `disconnect()` lorsque l'utilisateur se déconnecte pour fermer la connexion WebSocket.
*   **Reprise de l'application :** Lorsque l'application revient du mode arrière-plan, il peut étre nécessaire de vérifier l'état de la connexion WebSocket et de la rétablir si elle a été interrompue par le système d'exploitation.



