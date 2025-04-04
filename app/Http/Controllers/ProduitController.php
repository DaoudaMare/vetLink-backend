<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Models\Produit;
use App\Models\Secteur;
use App\Models\SousSecteur;
use App\Models\Activite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    /**
     * Afficher la liste des produits avec filtres optionnels
     */
    public function index(Request $request): JsonResponse
    {
        $query = Produit::query()
            ->with(['secteur', 'sousSecteur', 'activite', 'producteur']);

        // Filtrage par secteur
        if ($request->has('secteur_id')) {
            $query->where('secteur_id', $request->secteur_id);
        }

        // Filtrage par sous-secteur
        if ($request->has('sous_secteur_id')) {
            $query->where('sous_secteur_id', $request->sous_secteur_id);
        }

        // Filtrage par activité
        if ($request->has('activite_id')) {
            $query->where('activite_id', $request->activite_id);
        }

        // Filtrage par type (code_type)
        if ($request->has('code_type')) {
            $query->where('code_type', $request->code_type);
        }

        // Filtrage bio
        if ($request->has('est_bio')) {
            $query->where('est_bio', $request->boolean('est_bio'));
        }

        $produits = $query->get();

        return response()->json([
            'message' => 'Liste des produits récupérée avec succès',
            'produits' => $produits
        ], 200);
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(StoreProduitRequest $request): JsonResponse
    {
        $this->authorize('create', Produit::class);

        $data = $request->validated();

        // Gestion de l'image principale
        if ($request->hasFile('image_principale')) {
            $data['image_principale'] = $request->file('image_principale')->store('produits', 'public');
        }

        // Gestion des images secondaires
        if ($request->hasFile('images_secondaires')) {
            $secondaryImages = [];
            foreach ($request->file('images_secondaires') as $image) {
                $secondaryImages[] = $image->store('produits/secondaires', 'public');
            }
            $data['images_secondaires'] = json_encode($secondaryImages);
        }

        $produit = Produit::create($data);

        return response()->json([
            'message' => 'Produit créé avec succès.',
            'produit' => $produit->load(['secteur', 'sousSecteur', 'activite'])
        ], 201);
    }

    /**
     * Afficher un produit spécifique
     */
    public function show(Produit $produit): JsonResponse
    {
        $this->authorize('view', $produit);

        return response()->json([
            'message' => 'Produit récupéré avec succès',
            'produit' => $produit->load(['secteur', 'sousSecteur', 'activite', 'producteur'])
        ], 200);
    }

    /**
     * Mettre à jour un produit
     */
    public function update(UpdateProduitRequest $request, Produit $produit): JsonResponse
    {
        $this->authorize('update', $produit);

        $data = $request->validated();

        // Gestion de l'image principale
        if ($request->hasFile('image_principale')) {
            // Supprimer l'ancienne image si elle existe
            if ($produit->image_principale) {
                Storage::disk('public')->delete($produit->image_principale);
            }
            $data['image_principale'] = $request->file('image_principale')->store('produits', 'public');
        }

        // Gestion des images secondaires
        if ($request->hasFile('images_secondaires')) {
            // Supprimer les anciennes images si elles existent
            if ($produit->images_secondaires) {
                foreach (json_decode($produit->images_secondaires) as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $secondaryImages = [];
            foreach ($request->file('images_secondaires') as $image) {
                $secondaryImages[] = $image->store('produits/secondaires', 'public');
            }
            $data['images_secondaires'] = json_encode($secondaryImages);
        }

        $produit->update($data);

        return response()->json([
            'message' => 'Produit mis à jour avec succès.',
            'produit' => $produit->refresh()->load(['secteur', 'sousSecteur', 'activite'])
        ], 200);
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Produit $produit): JsonResponse
    {
        $this->authorize('delete', $produit);

        // Suppression des images
        if ($produit->image_principale) {
            Storage::disk('public')->delete($produit->image_principale);
        }

        if ($produit->images_secondaires) {
            foreach (json_decode($produit->images_secondaires) as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $produit->delete();

        return response()->json([
            'message' => 'Produit supprimé avec succès.'
        ], 200);
    }

    /**
     * Produits les plus vendus
     */
    public function topVendus(): JsonResponse
    {
        $produits = Produit::with(['secteur', 'sousSecteur'])
            ->orderBy('ventes', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'message' => 'Top 10 des produits les plus vendus',
            'produits' => $produits
        ]);
    }

    /**
     * Produits les mieux notés
     */
    public function topApprecies(): JsonResponse
    {
        $produits = Produit::with(['secteur', 'sousSecteur'])
            ->orderByDesc('note')
            ->take(10)
            ->get();

        return response()->json([
            'message' => 'Top 10 des produits les mieux notés',
            'produits' => $produits
        ]);
    }

    /**
     * Produits par secteur
     */
    public function produitsParSecteur($secteur_id): JsonResponse
    {
        $produits = Produit::with(['sousSecteur', 'activite'])
            ->where('secteur_id', $secteur_id)
            ->get();

        return response()->json([
            'message' => 'Produits par secteur',
            'produits' => $produits
        ]);
    }

    /**
     * Produits par sous-secteur
     */
    public function produitsParSousSecteur($sous_secteur_id): JsonResponse
    {
        $produits = Produit::with(['secteur', 'activite'])
            ->where('sous_secteur_id', $sous_secteur_id)
            ->get();

        return response()->json([
            'message' => 'Produits par sous-secteur',
            'produits' => $produits
        ]);
    }

    /**
     * Produits par activité
     */
    public function produitsParActivite($activite_id): JsonResponse
    {
        $produits = Produit::with(['secteur', 'sousSecteur'])
            ->where('activite_id', $activite_id)
            ->get();

        return response()->json([
            'message' => 'Produits par activité',
            'produits' => $produits
        ]);
    }

    /**
     * Produits bio
     */
    public function produitsBio(): JsonResponse
    {
        $produits = Produit::with(['secteur', 'sousSecteur'])
            ->where('est_bio', true)
            ->get();

        return response()->json([
            'message' => 'Liste des produits bio',
            'produits' => $produits
        ]);
    }
}
