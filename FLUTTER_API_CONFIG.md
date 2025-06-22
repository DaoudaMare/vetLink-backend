# 📱 Configuration Flutter - API Laravel

## 🌐 URLs de Base

### Développement Local
```dart
// Pour le développement local
const String baseUrl = 'http://10.0.2.2:8000/api';  // Android Emulator
// OU
const String baseUrl = 'http://localhost:8000/api';  // iOS Simulator
// OU
const String baseUrl = 'http://192.168.1.XXX:8000/api';  // Appareil physique
```

### Production
```dart
const String baseUrl = 'https://votre-domaine.com/api';
```

## 🔧 Configuration Flutter

### 1. **Fichier de configuration API**

Créez un fichier `lib/config/api_config.dart` :

```dart
class ApiConfig {
  // URLs de base
  static const String localBaseUrl = 'http://10.0.2.2:8000/api';
  static const String productionBaseUrl = 'https://votre-domaine.com/api';
  
  // URL active (changez selon l'environnement)
  static const String baseUrl = localBaseUrl;
  
  // Endpoints
  static const String login = '/login';
  static const String register = '/register';
  static const String logout = '/v1/logout';
  
  // Produits
  static const String products = '/products';
  static const String categories = '/categories';
  
  // Producteurs
  static const String producerProfile = '/v1/producer/profile';
  static const String producerProducts = '/v1/producer/products';
  static const String producerOrders = '/v1/producer/orders';
  static const String producerStatistics = '/v1/producer/statistics';
  
  // Clients
  static const String customerProfile = '/v1/customer/profile';
  static const String customerSearchProducts = '/v1/customer/search-products';
  static const String customerOrders = '/v1/customer/orders';
  static const String customerRecommendedProducts = '/v1/customer/recommended-products';
  
  // Notifications
  static const String notifications = '/v1/notifications';
  
  // Évaluations
  static const String reviews = '/v1/reviews';
}
```

### 2. **Service API**

Créez un fichier `lib/services/api_service.dart` :

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../config/api_config.dart';

class ApiService {
  static String? _token;
  
  // Initialiser le token depuis le stockage local
  static Future<void> initializeToken() async {
    final prefs = await SharedPreferences.getInstance();
    _token = prefs.getString('auth_token');
  }
  
  // Sauvegarder le token
  static Future<void> saveToken(String token) async {
    _token = token;
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }
  
  // Supprimer le token
  static Future<void> clearToken() async {
    _token = null;
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }
  
  // Headers pour les requêtes authentifiées
  static Map<String, String> get _authHeaders => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    if (_token != null) 'Authorization': 'Bearer $_token',
  };
  
  // Headers pour les requêtes publiques
  static Map<String, String> get _publicHeaders => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  };
  
  // Méthode générique pour les requêtes GET
  static Future<Map<String, dynamic>> get(String endpoint) async {
    try {
      final response = await http.get(
        Uri.parse('${ApiConfig.baseUrl}$endpoint'),
        headers: _authHeaders,
      );
      
      return _handleResponse(response);
    } catch (e) {
      throw Exception('Erreur réseau: $e');
    }
  }
  
  // Méthode générique pour les requêtes POST
  static Future<Map<String, dynamic>> post(String endpoint, Map<String, dynamic> data) async {
    try {
      final response = await http.post(
        Uri.parse('${ApiConfig.baseUrl}$endpoint'),
        headers: _authHeaders,
        body: jsonEncode(data),
      );
      
      return _handleResponse(response);
    } catch (e) {
      throw Exception('Erreur réseau: $e');
    }
  }
  
  // Méthode générique pour les requêtes PUT
  static Future<Map<String, dynamic>> put(String endpoint, Map<String, dynamic> data) async {
    try {
      final response = await http.put(
        Uri.parse('${ApiConfig.baseUrl}$endpoint'),
        headers: _authHeaders,
        body: jsonEncode(data),
      );
      
      return _handleResponse(response);
    } catch (e) {
      throw Exception('Erreur réseau: $e');
    }
  }
  
  // Méthode générique pour les requêtes DELETE
  static Future<Map<String, dynamic>> delete(String endpoint) async {
    try {
      final response = await http.delete(
        Uri.parse('${ApiConfig.baseUrl}$endpoint'),
        headers: _authHeaders,
      );
      
      return _handleResponse(response);
    } catch (e) {
      throw Exception('Erreur réseau: $e');
    }
  }
  
  // Gestion des réponses
  static Map<String, dynamic> _handleResponse(http.Response response) {
    final body = jsonDecode(response.body);
    
    if (response.statusCode >= 200 && response.statusCode < 300) {
      return body;
    } else {
      throw Exception(body['message'] ?? 'Erreur inconnue');
    }
  }
}
```

### 3. **Exemples d'utilisation**

#### Authentification
```dart
// Connexion
Future<void> login(String email, String password) async {
  try {
    final response = await ApiService.post(ApiConfig.login, {
      'email': email,
      'password': password,
    });
    
    // Sauvegarder le token
    await ApiService.saveToken(response['token']);
    
    print('Connexion réussie: ${response['message']}');
  } catch (e) {
    print('Erreur de connexion: $e');
  }
}

// Inscription
Future<void> register(String name, String email, String password) async {
  try {
    final response = await ApiService.post(ApiConfig.register, {
      'name': name,
      'email': email,
      'password': password,
      'user_type': 'client',
    });
    
    print('Inscription réussie: ${response['message']}');
  } catch (e) {
    print('Erreur d\'inscription: $e');
  }
}
```

#### Produits
```dart
// Récupérer les produits
Future<List<dynamic>> getProducts() async {
  try {
    final response = await ApiService.get(ApiConfig.products);
    return response['data']['data'];
  } catch (e) {
    print('Erreur récupération produits: $e');
    return [];
  }
}

// Rechercher des produits
Future<List<dynamic>> searchProducts({
  String? search,
  int? categorieId,
  double? minPrice,
  double? maxPrice,
  bool? isBio,
}) async {
  try {
    final queryParams = <String, String>{};
    if (search != null) queryParams['search'] = search;
    if (categorieId != null) queryParams['categorie_id'] = categorieId.toString();
    if (minPrice != null) queryParams['min_price'] = minPrice.toString();
    if (maxPrice != null) queryParams['max_price'] = maxPrice.toString();
    if (isBio != null) queryParams['isbio'] = isBio.toString();
    
    final uri = Uri.parse('${ApiConfig.baseUrl}${ApiConfig.customerSearchProducts}')
        .replace(queryParameters: queryParams);
    
    final response = await http.get(uri, headers: ApiService._authHeaders);
    final body = jsonDecode(response.body);
    
    return body['data']['data'];
  } catch (e) {
    print('Erreur recherche produits: $e');
    return [];
  }
}
```

#### Commandes
```dart
// Passer une commande
Future<void> placeOrder(int productId, int quantity) async {
  try {
    final response = await ApiService.post(ApiConfig.customerOrders, {
      'product_id': productId,
      'quantity': quantity,
    });
    
    print('Commande passée: ${response['message']}');
  } catch (e) {
    print('Erreur commande: $e');
  }
}

// Historique des commandes
Future<List<dynamic>> getOrderHistory() async {
  try {
    final response = await ApiService.get(ApiConfig.customerOrders);
    return response['data']['data'];
  } catch (e) {
    print('Erreur historique commandes: $e');
    return [];
  }
}
```

## 📱 Configuration Flutter

### 1. **Dépendances dans pubspec.yaml**
```yaml
dependencies:
  flutter:
    sdk: flutter
  http: ^1.1.0
  shared_preferences: ^2.2.2
  dio: ^5.3.2  # Alternative à http
```

### 2. **Permissions Android**

Dans `android/app/src/main/AndroidManifest.xml` :
```xml
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.ACCESS_NETWORK_STATE" />
```

### 3. **Permissions iOS**

Dans `ios/Runner/Info.plist` :
```xml
<key>NSAppTransportSecurity</key>
<dict>
    <key>NSAllowsArbitraryLoads</key>
    <true/>
</dict>
```

## 🔍 Test des APIs

### 1. **Test avec Postman**
- Importez la collection depuis `API_DOCUMENTATION.md`
- Testez chaque endpoint

### 2. **Test avec Flutter**
```dart
// Dans votre widget
@override
void initState() {
  super.initState();
  _testApi();
}

Future<void> _testApi() async {
  try {
    final response = await ApiService.get(ApiConfig.categories);
    print('Test API réussi: ${response['data']}');
  } catch (e) {
    print('Test API échoué: $e');
  }
}
```

## 🚨 Résolution des problèmes

### Erreur "Connection refused"
- Vérifiez que Laravel est démarré : `php artisan serve`
- Vérifiez l'URL dans `ApiConfig.baseUrl`

### Erreur CORS
- Vérifiez la configuration CORS dans `config/cors.php`
- Redémarrez le serveur Laravel

### Erreur "Invalid token"
- Vérifiez que l'authentification fonctionne
- Vérifiez le format du token dans les headers

## 📊 Monitoring

### Logs Laravel
```bash
tail -f storage/logs/laravel.log
```

### Debug Flutter
```dart
print('URL: ${ApiConfig.baseUrl}');
print('Token: $_token');
print('Response: $response');
``` 