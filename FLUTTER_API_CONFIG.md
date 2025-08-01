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
      enableLogging: true; // Activez pour le débogage
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

### 3. Écoute des Événements

*   **Canal d'écoute :** Comme défini dans `routes/channels.php`, chaque utilisateur a un canal privé : `App.Models.User.{id}`. Vous devez vous y abonner en utilisant l'ID de l'utilisateur connecté.

*   **Nom de l'événement :** Pour les notifications standards de Laravel, le nom de l'événement à écouter est `.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated`.

*   **Données de l'événement :** L'objet `e.data` reçu contiendra la charge utile de la notification que vous avez définie dans la méthode `toBroadcast` de votre classe de notification côté backend.

### 4. Affichage des Notifications Locales

Une fois qu'un événement est reçu via le WebSocket, utilisez le paquet `flutter_local_notifications` pour afficher une notification visible à l'utilisateur, même si l'application est en arrière-plan (mais toujours en cours d'exécution).

1.  **Initialisez `flutter_local_notifications`** au démarrage de votre application.
2.  **Créez une fonction** `showLocalNotification(String title, String body)`.
3.  **Appelez cette fonction** depuis le callback `.listen()` de votre client Echo.

### 5. Gestion du Cycle de Vie

*   **Initialisation :** Initialisez le `NotificationService` et appelez `init()` et `listenForNotifications()` après que l'utilisateur se soit connecté avec succès.
*   **Déconnexion :** Appelez `disconnect()` lorsque l'utilisateur se déconnecte pour fermer la connexion WebSocket.
*   **Reprise de l'application :** Lorsque l'application revient du mode arrière-plan, il peut être nécessaire de vérifier l'état de la connexion WebSocket et de la rétablir si elle a été interrompue par le système d'exploitation.

---

## 💬 Guide d'Implémentation Mobile (Flutter) - Fonctionnalités de Chat

Cette section détaille comment implémenter les fonctionnalités de chat dans votre application Flutter, en utilisant les APIs RESTful du backend et les WebSockets pour les messages en temps réel.

### 1. Architecture Générale

Le chat repose sur deux piliers :

*   **APIs RESTful :** Pour la gestion des conversations (liste, démarrage, suppression) et la récupération de l'historique des messages.
*   **WebSockets (Laravel Echo) :** Pour l'envoi et la réception de messages en temps réel.

### 2. Gestion des Conversations

Utilisez les endpoints suivants pour gérer les conversations :

*   **Lister les conversations :**
    *   **Endpoint:** `GET /api/chat/conversations`
    *   **Description:** Récupère une liste paginée des conversations de l'utilisateur connecté.
    *   **Paramètres de requête (optionnels):** `filter` (e.g., `unread`), `with_role` (e.g., `Producteur`), `per_page`.
    *   **Implémentation Flutter :** Utilisez un client HTTP (e.g., `dio`, `http`) pour effectuer cette requête. Gérez la pagination si nécessaire.

*   **Démarrer une nouvelle conversation :**
    *   **Endpoint:** `POST /api/chat/conversations/start`
    *   **Description:** Crée une nouvelle conversation entre l'utilisateur connecté et un autre utilisateur, potentiellement liée à un produit ou une commande.
    *   **Corps de la requête:** `user_id` (requis), `product_id` (optionnel), `order_id` (optionnel).
    *   **Implémentation Flutter :** Envoyez une requête POST avec le corps JSON approprié. Il est recommandé de vérifier si une conversation existe déjà avant d'en créer une nouvelle.

*   **Quitter une conversation :**
    *   **Endpoint:** `DELETE /api/chat/conversations/{conversation}`
    *   **Description:** Permet à un utilisateur de quitter une conversation.
    *   **Implémentation Flutter :** Envoyez une requête DELETE à l'endpoint.

### 3. Gestion des Messages (Historique & Envoi)

*   **Messages d'une conversation :**
    *   **Endpoint:** `GET /api/chat/conversations/{conversation}/messages`
    *   **Description:** Récupère une liste paginée des messages d'une conversation spécifique.
    *   **Paramètres de requête (optionnels):** `per_page`.
    *   **Implémentation Flutter :** Utilisez un client HTTP pour récupérer l'historique des messages. Affichez-les dans une interface de chat (e.g., `ListView.builder`).

*   **Envoyer un message :**
    *   **Endpoint:** `POST /api/chat/conversations/{conversation}/messages`
    *   **Description:** Permet d'envoyer un message (texte, image, vidéo, document) dans une conversation.
    *   **Corps de la requête:** `body` (texte) ou `file` (fichier multipart).
    *   **Implémentation Flutter :**
        *   **Texte :** Envoyez une requête POST avec le corps JSON contenant le `body`.
        *   **Fichier :** Utilisez `multipart/form-data` pour envoyer le fichier. Le backend gérera le type de message (`image`, `video`, `document`) en fonction du type MIME du fichier.

*   **Marquer un message comme lu :**
    *   **Endpoint:** `POST /api/chat/messages/{message}/read`
    *   **Description:** Marque un message spécifique comme lu par l'utilisateur connecté.
    *   **Implémentation Flutter :** Envoyez une requête POST à l'endpoint.

### 4. Réception des Messages en Temps Réel (WebSockets)

Les nouveaux messages sont diffusés via le même système WebSocket que les notifications.

*   **Réutilisation de `NotificationService` :** Vous pouvez étendre ou adapter votre `NotificationService` (ou un service de chat dédié) pour écouter les événements de message.

*   **Canal d'écoute :** Les messages sont diffusés sur le canal privé de la conversation. Le nom du canal sera `private-chat.conversations.{conversation_id}`.

*   **Nom de l'événement :** L'événement diffusé pour un nouveau message est `.App\\Events\\MessageSent`.

*   **Exemple d'écoute dans Flutter (dans votre service Echo/Chat) :**

    ```dart
    void listenForChatMessages(int conversationId) {
      echo.private('chat.conversations.$conversationId')
        .listen('.App\\Events\\MessageSent', (e) {
          if (e != null && e.data != null) {
            print('Nouveau message reçu: ${e.data}');
            // e.data contiendra les détails du message (id, user_id, body, file_url, etc.)
            // TODO: Ajouter le message à l'interface utilisateur du chat
            // final newMessage = Message.fromJson(e.data);
            // updateChatUI(newMessage);
          }
        });
      print('Listening for chat messages on conversation $conversationId');
    }
    ```

### 5. Gestion de l'Interface Utilisateur du Chat

*   **Affichage des messages :** Utilisez un `ListView.builder` pour afficher les messages, en faisant défiler automatiquement vers le bas pour les nouveaux messages.
*   **Saisie de message :** Un champ de texte pour la saisie et un bouton pour l'envoi.
*   **Upload de fichiers :** Intégrez des sélecteurs de fichiers (images, vidéos, documents) pour permettre l'envoi de médias.
*   **Indicateurs de lecture :** Mettez à jour l'état des messages pour indiquer s'ils ont été lus.

### 6. Bonnes Pratiques

*   **Gestion de l'état :** Utilisez un gestionnaire d'état (Provider, Riverpod, BLoC, GetX) pour gérer les conversations, les messages et l'état de la connexion WebSocket.
*   **Optimisation des performances :** Pour les conversations avec de nombreux messages, implémentez la pagination côté client pour charger les messages au fur et à mesure du défilement.
*   **Gestion des erreurs :** Implémentez une gestion robuste des erreurs pour les requêtes HTTP et les connexions WebSocket.
*   **Sécurité :** Assurez-vous que toutes les requêtes API sont authentifiées avec le token de l'utilisateur.

Ce guide devrait vous aider à démarrer l'implémentation du chat dans votre application Flutter.