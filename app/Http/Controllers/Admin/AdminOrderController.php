<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\User;
use App\Models\Produit;
use App\Http\Resources\CommandeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Afficher la liste des commandes avec filtres
     */
    public function index(Request $request)
    {
        $query = Commande::with(['customer', 'produits']);
        
        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('delivery_status')) {
            $query->where('delivery_status', $request->delivery_status);
        }
        
        if ($request->has('payment')) {
            $query->where('payment', $request->payment);
        }
        
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('num', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($subQ) use ($search) {
                      $subQ->where('firstName', 'like', "%{$search}%")
                           ->orWhere('lastName', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->latest()->paginate(20);
        $customers = User::whereHas('userType', function($q) {
            $q->where('title', 'client');
        })->get();
        
        return view('admin.orders.index', compact('orders', 'customers'));
    }
    
    /**
     * Afficher les détails d'une commande
     */
    public function show(Commande $order)
    {
        $order->load(['customer', 'produits.producer', 'produits.categorie']);
        
        $timeline = [
            ['status' => 'Commande créée', 'date' => $order->created_at, 'completed' => true],
            ['status' => 'Confirmée', 'date' => $order->status >= 1 ? $order->updated_at : null, 'completed' => $order->status >= 1],
            ['status' => 'En préparation', 'date' => $order->status >= 2 ? $order->updated_at : null, 'completed' => $order->status >= 2],
            ['status' => 'Expédiée', 'date' => $order->status >= 3 ? $order->updated_at : null, 'completed' => $order->status >= 3],
            ['status' => 'Livrée', 'date' => $order->status >= 4 ? $order->updated_at : null, 'completed' => $order->status >= 4],
        ];
        
        return view('admin.orders.show', compact('order', 'timeline'));
    }
    
    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(Request $request, Commande $order)
    {
        $request->validate([
            'status' => 'required|integer|min:0|max:5',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $oldStatus = $order->status;
        $order->update([
            'status' => $request->status,
            'admin_notes' => $request->notes
        ]);
        
        // Log du changement de statut (optionnel)
        
        return redirect()->back()
            ->with('success', 'Statut de commande mis à jour avec succès');
    }
    
    /**
     * Mettre à jour le statut de livraison
     */
    public function updateDeliveryStatus(Request $request, Commande $order)
    {
        $request->validate([
            'delivery_status' => 'required|integer|min:0|max:3',
            'tracking_number' => 'nullable|string|max:100'
        ]);
        
        $order->update([
            'delivery_status' => $request->delivery_status,
            'tracking_number' => $request->tracking_number
        ]);
        
        return redirect()->back()
            ->with('success', 'Statut de livraison mis à jour avec succès');
    }
    
    /**
     * Marquer comme payée
     */
    public function markAsPaid(Commande $order)
    {
        $order->update(['payment' => 1]);
        
        return redirect()->back()
            ->with('success', 'Commande marquée comme payée');
    }
    
    /**
     * Annuler une commande
     */
    public function cancel(Request $request, Commande $order)
    {
        $request->validate([
            'cancellation_reason' => 'required|string|max:500'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Remettre en stock tous les produits
            foreach ($order->produits as $produit) {
                $quantity = $produit->pivot->quantity;
                $produit->increment('quantity', $quantity);
            }
            
            $order->update([
                'status' => 5, // Annulée
                'cancellation_reason' => $request->cancellation_reason,
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id()
            ]);
            
            DB::commit();
            
            return redirect()->back()
                ->with('success', 'Commande annulée avec succès');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'annulation de la commande');
        }
    }
    
    /**
     * Commandes en attente
     */
    public function pending()
    {
        $orders = Commande::with(['customer', 'produits'])
            ->where('status', 0)
            ->latest()
            ->paginate(20);
            
        return view('admin.orders.pending', compact('orders'));
    }
    
    /**
     * Commandes problématiques (litiges)
     */
    public function disputes()
    {
        $orders = Commande::with(['customer', 'produits'])
            ->where('has_dispute', true)
            ->orWhere('status', 5) // Annulées
            ->latest()
            ->paginate(20);
            
        return view('admin.orders.disputes', compact('orders'));
    }
    
    /**
     * Statistiques des commandes
     */
    public function statistics(Request $request)
    {
        $period = $request->get('period', '30'); // 30 jours par défaut
        $startDate = now()->subDays($period);
        
        $stats = [
            'total_orders' => Commande::where('created_at', '>=', $startDate)->count(),
            'total_revenue' => Commande::where('created_at', '>=', $startDate)->sum('total_price'),
            'pending_orders' => Commande::where('status', 0)->count(),
            'completed_orders' => Commande::where('status', 4)->where('created_at', '>=', $startDate)->count(),
            'cancelled_orders' => Commande::where('status', 5)->where('created_at', '>=', $startDate)->count(),
            'average_order_value' => Commande::where('created_at', '>=', $startDate)->avg('total_price'),
        ];
        
        // Graphique des commandes par jour
        $dailyOrders = Commande::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_price) as revenue')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Top clients
        $topCustomers = User::withCount('commandes')
            ->whereHas('commandes', function($q) use ($startDate) {
                $q->where('created_at', '>=', $startDate);
            })
            ->orderBy('commandes_count', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.orders.statistics', compact('stats', 'dailyOrders', 'topCustomers', 'period'));
    }
    
    /**
     * Exporter les commandes
     */
    public function export(Request $request)
    {
        $query = Commande::with(['customer', 'produits']);
        
        // Appliquer les mêmes filtres que l'index
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->get();
        
        // Générer CSV
        $filename = 'commandes_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Numéro', 'Client', 'Email', 'Total', 'Statut', 'Date']);
            
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->num,
                    $order->customer->firstName . ' ' . $order->customer->lastName,
                    $order->customer->email,
                    $order->total_price,
                    $this->getStatusLabel($order->status),
                    $order->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    private function getStatusLabel($status)
    {
        return match($status) {
            0 => 'En attente',
            1 => 'Confirmée',
            2 => 'En préparation',
            3 => 'Expédiée',
            4 => 'Livrée',
            5 => 'Annulée',
            default => 'Inconnu'
        };
    }
}
