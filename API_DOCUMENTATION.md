# 📱 API Documentation - Marketplace Agricole

## 🔐 Authentification

### Inscription
```http
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "phone": "+1234567890",
    "address": "123 Main St",
    "user_type": "client"
}
```

### Connexion
```http
POST /api/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

### Déconnexion
```http
POST /api/v1/logout
Authorization: Bearer {token}
```

## 🛒 APIs Publiques

### Liste des produits
```http
GET /api/products?page=1&categorie_id=1&min_price=1000&max_price=5000&isbio=true&search=pommes
```

### Détails d'un produit
```http
GET /api/products/{id}
```

### Catégories disponibles
```http
GET /api/categories
```

## 👨‍🌾 APIs Producteurs

### Profil du producteur
```http
GET /api/v1/producer/profile
Authorization: Bearer {token}
```

### Mes produits
```http
GET /api/v1/producer/products?page=1
Authorization: Bearer {token}
```

### Créer un produit
```http
POST /api/v1/producer/products
Authorization: Bearer {token}
Content-Type: multipart/form-data

{
    "name": "Pommes Bio",
    "description": "Pommes biologiques fraîches",
    "categorie_id": 1,
    "quantity": 100.5,
    "price": 2500,
    "measure": "kg",
    "isbio": true,
    "images[]": [file1, file2]
}
```

### Mettre à jour un produit
```http
PUT /api/v1/producer/products/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Pommes Bio Premium",
    "price": 3000,
    "quantity": 75.0
}
```

### Supprimer un produit
```http
DELETE /api/v1/producer/products/{id}
Authorization: Bearer {token}
```

### Mes commandes reçues
```http
GET /api/v1/producer/orders?page=1&status=0
Authorization: Bearer {token}
```

### Statistiques
```http
GET /api/v1/producer/statistics
Authorization: Bearer {token}
```

## 🛍️ APIs Clients

### Profil du client
```http
GET /api/v1/customer/profile
Authorization: Bearer {token}
```

### Rechercher des produits
```http
GET /api/v1/customer/search-products?search=pommes&categorie_id=1&min_price=1000&max_price=5000&isbio=true&sort_by=price&sort_order=asc&page=1
Authorization: Bearer {token}
```

### Détails d'un produit
```http
GET /api/v1/customer/products/{id}
Authorization: Bearer {token}
```

### Passer une commande
```http
POST /api/v1/customer/orders
Authorization: Bearer {token}
Content-Type: application/json

{
    "product_id": 1,
    "quantity": 5
}
```

### Historique des commandes
```http
GET /api/v1/customer/orders?page=1
Authorization: Bearer {token}
```

### Détails d'une commande
```http
GET /api/v1/customer/orders/{id}
Authorization: Bearer {token}
```

### Annuler une commande
```http
PUT /api/v1/customer/orders/{id}/cancel
Authorization: Bearer {token}
```

### Produits recommandés
```http
GET /api/v1/customer/recommended-products
Authorization: Bearer {token}
```

## 🔔 APIs Notifications

### Liste des notifications
```http
GET /api/v1/notifications
Authorization: Bearer {token}
```

### Marquer comme lue
```http
PUT /api/v1/notifications/mark-as-read
Authorization: Bearer {token}
Content-Type: application/json

{
    "notification_id": 1
}
```

### Marquer toutes comme lues
```http
PUT /api/v1/notifications/mark-all-as-read
Authorization: Bearer {token}
```

### Supprimer une notification
```http
DELETE /api/v1/notifications
Authorization: Bearer {token}
Content-Type: application/json

{
    "notification_id": 1
}
```

## ⭐ APIs Évaluations

### Évaluations d'un produit
```http
GET /api/v1/reviews/products/{id}
Authorization: Bearer {token}
```

### Ajouter une évaluation
```http
POST /api/v1/reviews
Authorization: Bearer {token}
Content-Type: application/json

{
    "product_id": 1,
    "rating": 5,
    "comment": "Excellent produit, très frais !"
}
```

### Mettre à jour une évaluation
```http
PUT /api/v1/reviews
Authorization: Bearer {token}
Content-Type: application/json

{
    "review_id": 1,
    "rating": 4,
    "comment": "Très bon produit"
}
```

### Supprimer une évaluation
```http
DELETE /api/v1/reviews
Authorization: Bearer {token}
Content-Type: application/json

{
    "review_id": 1
}
```

## 📊 Codes de Statut

- `200` - Succès
- `201` - Créé avec succès
- `400` - Requête invalide
- `401` - Non authentifié
- `403` - Accès interdit
- `404` - Ressource non trouvée
- `422` - Erreur de validation
- `500` - Erreur serveur

## 🔧 Formats de Réponse

### Succès
```json
{
    "message": "Opération réussie",
    "data": {
        // Données de la réponse
    }
}
```

### Erreur
```json
{
    "message": "Message d'erreur",
    "errors": {
        "field": ["Erreur de validation"]
    }
}
```

## 📱 Utilisation Mobile

### Headers requis
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

### Gestion des images
- Utiliser `multipart/form-data` pour les uploads
- Taille max : 2MB par image
- Formats acceptés : JPEG, PNG, JPG

### Pagination
- Paramètre `page` pour la pagination
- 15 éléments par page par défaut
- Réponse inclut les métadonnées de pagination

## 🚀 Fonctionnalités Avancées

### Filtres de recherche
- Par catégorie
- Par prix (min/max)
- Par producteur
- Par type bio
- Par nom de produit

### Tri
- Par prix (asc/desc)
- Par date (asc/desc)
- Par popularité

### Notifications en temps réel
- Statuts de commande
- Nouvelles commandes
- Messages système

### Système de recommandations
- Basé sur l'historique d'achat
- Par catégorie préférée
- Produits populaires 