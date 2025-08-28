<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\User;
use App\Models\ProductImage;
use App\Http\Resources\ProduitResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * Afficher la liste des produits avec filtres
     */
    public function index(Request $request)
    {
        $query = Produit::with(['categorie', 'producer', 'images']);
        
        // Filtres
        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        
        if ($request->has('producer_id')) {
            $query->where('producer_id', $request->producer_id);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $products = $query->latest()->paginate(20);
        $categories = Categorie::all();
        $producers = User::whereHas('userType', function($q) {
            $q->whereIn('title', ['producteur', 'agriculteur', 'éleveur']);
        })->get();
        
        return view('admin.products.index', compact('products', 'categories', 'producers'));
    }
    
    /**
     * Afficher les détails d'un produit
     */
    public function show(Produit $product)
    {
        $product->load(['categorie', 'producer', 'images', 'reviews.user', 'commandes']);
        
        $stats = [
            'reviews_count' => $product->reviews()->count(),
            'average_rating' => $product->reviews()->avg('rating'),
            'orders_count' => $product->commandes()->count(),
            'total_sold' => $product->commandes()->sum('pivot.quantity'),
        ];
        
        return view('admin.products.show', compact('product', 'stats'));
    }
    
    /**
     * Approuver un produit
     */
    public function approve(Produit $product)
    {
        $product->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id()
        ]);
        
        return redirect()->back()
            ->with('success', 'Produit approuvé avec succès');
    }
    
    /**
     * Rejeter un produit
     */
    public function reject(Request $request, Produit $product)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        
        $product->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'rejected_at' => now(),
            'rejected_by' => auth()->id()
        ]);
        
        return redirect()->back()
            ->with('success', 'Produit rejeté avec succès');
    }
    
    /**
     * Mettre en vedette un produit
     */
    public function feature(Produit $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        
        $message = $product->is_featured ? 'Produit mis en vedette' : 'Produit retiré de la vedette';
        
        return redirect()->back()
            ->with('success', $message);
    }
    
    /**
     * Formulaire d'édition d'un produit
     */
    public function edit(Produit $product)
    {
        $categories = Categorie::all();
        $producers = User::whereHas('userType', function($q) {
            $q->whereIn('title', ['producteur', 'agriculteur', 'éleveur']);
        })->get();
        
        return view('admin.products.edit', compact('product', 'categories', 'producers'));
    }
    
    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, Produit $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'producer_id' => 'required|exists:users,id',
            'measure' => 'required|in:kg,g,L,unité',
            'isbio' => 'boolean',
        ]);
        
        $product->update($request->only([
            'name', 'description', 'price', 'quantity', 
            'categorie_id', 'producer_id', 'measure', 'isbio'
        ]));
        
        return redirect()->route('admin.products.show', $product)
            ->with('success', 'Produit mis à jour avec succès');
    }
    
    /**
     * Supprimer un produit
     */
    public function destroy(Produit $product)
    {
        // Supprimer les images associées
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
            $image->delete();
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé avec succès');
    }
    
    /**
     * Supprimer une image de produit
     */
    public function deleteImage(Produit $product, ProductImage $image)
    {
        if (Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }
        
        $image->delete();
        
        return redirect()->back()
            ->with('success', 'Image supprimée avec succès');
    }
    
    /**
     * Gestion des stocks - Ajustement manuel
     */
    public function adjustStock(Request $request, Produit $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255'
        ]);
        
        $oldQuantity = $product->quantity;
        $product->update(['quantity' => $request->quantity]);
        
        // Log de l'ajustement (optionnel - créer une table stock_adjustments)
        
        return redirect()->back()
            ->with('success', "Stock ajusté de {$oldQuantity} à {$request->quantity}");
    }
    
    /**
     * Produits en attente de validation
     */
    public function pending()
    {
        $products = Produit::with(['categorie', 'producer', 'images'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);
            
        return view('admin.products.pending', compact('products'));
    }
    
    /**
     * Produits les plus vendus
     */
    public function topSelling()
    {
        $products = Produit::with(['categorie', 'producer'])
            ->withCount('commandes')
            ->orderBy('commandes_count', 'desc')
            ->take(50)
            ->get();
            
        return view('admin.products.top-selling', compact('products'));
    }
}
