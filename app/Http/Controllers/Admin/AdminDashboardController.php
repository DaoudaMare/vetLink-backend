<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\Review;
use App\Models\Document;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Afficher le tableau de bord administrateur
     */
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_users' => User::count(),
            'total_products' => Produit::count(),
            'total_orders' => Commande::count(),
            'total_revenue' => Commande::where('payment', 1)->sum('total_price'),
            'pending_orders' => Commande::where('status', 0)->count(),
            'pending_products' => 0, // Temporaire jusqu'à migration
            'active_conversations' => Conversation::whereHas('messages', function($q) {
                $q->where('created_at', '>=', now()->subDays(7));
            })->count(),
            'total_reviews' => Review::count(),
        ];

        // Nouveaux utilisateurs (7 derniers jours)
        $newUsers = User::where('created_at', '>=', now()->subDays(7))
            ->with('userType')
            ->latest()
            ->take(10)
            ->get();

        // Commandes récentes
        $recentOrders = Commande::with(['customer', 'produits'])
            ->latest()
            ->take(10)
            ->get();

        // Produits en attente de validation
        $pendingProducts = collect(); // Temporaire jusqu'à migration

        // Statistiques par mois (12 derniers mois)
        $monthlyStats = $this->getMonthlyStats();

        // Top produits
        $topProducts = Produit::withCount('commandes')
            ->orderBy('commandes_count', 'desc')
            ->take(10)
            ->get();

        // Répartition des utilisateurs par type
        $usersByType = User::select('user_type_id', DB::raw('count(*) as count'))
            ->with('userType')
            ->groupBy('user_type_id')
            ->get();

        // Activité récente
        $recentActivity = $this->getRecentActivity();

        return view('dashboard', compact(
            'stats',
            'newUsers',
            'recentOrders',
            'pendingProducts',
            'monthlyStats',
            'topProducts',
            'usersByType',
            'recentActivity'
        ));
    }

    /**
     * Obtenir les statistiques mensuelles
     */
    private function getMonthlyStats()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $months[] = [
                'month' => $date->format('M Y'),
                'users' => User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'orders' => Commande::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'revenue' => Commande::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->where('payment', 1)
                    ->sum('total_price'),
                'products' => Produit::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            ];
        }

        return $months;
    }

    /**
     * Obtenir l'activité récente
     */
    private function getRecentActivity()
    {
        $activities = collect();

        // Nouveaux utilisateurs
        $newUsers = User::where('created_at', '>=', now()->subDays(7))
            ->get()
            ->map(function($user) {
                return [
                    'type' => 'user_registered',
                    'message' => "Nouvel utilisateur inscrit: {$user->firstName} {$user->lastName}",
                    'date' => $user->created_at,
                    'icon' => 'user-plus',
                    'color' => 'success'
                ];
            });

        // Nouvelles commandes
        $newOrders = Commande::where('created_at', '>=', now()->subDays(7))
            ->with('customer')
            ->get()
            ->map(function($order) {
                return [
                    'type' => 'order_placed',
                    'message' => "Nouvelle commande #{$order->num} par {$order->customer->firstName} {$order->customer->lastName}",
                    'date' => $order->created_at,
                    'icon' => 'shopping-cart',
                    'color' => 'primary'
                ];
            });

        // Nouveaux produits
        $newProducts = Produit::where('created_at', '>=', now()->subDays(7))
            ->with('producer')
            ->get()
            ->map(function($product) {
                return [
                    'type' => 'product_added',
                    'message' => "Nouveau produit ajouté: {$product->name}",
                    'date' => $product->created_at,
                    'icon' => 'package',
                    'color' => 'info'
                ];
            });

        // Nouvelles évaluations
        $newReviews = Review::where('created_at', '>=', now()->subDays(7))
            ->with(['user', 'product'])
            ->get()
            ->map(function($review) {
                return [
                    'type' => 'review_added',
                    'message' => "Nouvelle évaluation ({$review->rating}★) pour {$review->product->name}",
                    'date' => $review->created_at,
                    'icon' => 'star',
                    'color' => 'warning'
                ];
            });

        return $activities
            ->merge($newUsers)
            ->merge($newOrders)
            ->merge($newProducts)
            ->merge($newReviews)
            ->sortByDesc('date')
            ->take(20)
            ->values();
    }

    /**
     * API pour les statistiques en temps réel
     */
    public function liveStats()
    {
        return response()->json([
            'pending_orders' => Commande::where('status', 0)->count(),
            'pending_products' => 0, // Temporaire jusqu'à migration
            'unread_messages' => Message::whereNull('read_at')->count(),
            'today_revenue' => Commande::whereDate('created_at', today())
                ->where('payment', 1)
                ->sum('total_price'),
            'today_orders' => Commande::whereDate('created_at', today())->count(),
            'today_users' => User::whereDate('created_at', today())->count(),
        ]);
    }

    /**
     * Graphique des ventes
     */
    public function salesChart(Request $request)
    {
        $period = $request->get('period', '30'); // 30 jours par défaut
        $startDate = now()->subDays($period);

        $sales = Commande::selectRaw('DATE(created_at) as date, COUNT(*) as orders, SUM(total_price) as revenue')
            ->where('created_at', '>=', $startDate)
            ->where('payment', 1)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($sales);
    }

    /**
     * Alertes système
     */
    public function alerts()
    {
        $alerts = [];

        // Produits en rupture de stock
        $outOfStock = Produit::where('quantity', 0)->count();
        if ($outOfStock > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$outOfStock} produit(s) en rupture de stock",
                'action' => route('admin.products.index', ['filter' => 'out_of_stock'])
            ];
        }

        // Commandes en attente depuis plus de 24h
        $oldPendingOrders = Commande::where('status', 0)
            ->where('created_at', '<', now()->subHours(24))
            ->count();
        if ($oldPendingOrders > 0) {
            $alerts[] = [
                'type' => 'danger',
                'message' => "{$oldPendingOrders} commande(s) en attente depuis plus de 24h",
                'action' => route('admin.orders.pending')
            ];
        }

        // Produits en attente de validation
        $pendingProducts = Produit::where('status', 'pending')->count();
        if ($pendingProducts > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$pendingProducts} produit(s) en attente de validation",
                'action' => route('admin.products.pending')
            ];
        }

        return response()->json($alerts);
    }
}
