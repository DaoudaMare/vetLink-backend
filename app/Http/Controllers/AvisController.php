<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Produit;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'note' => 'required|numeric|min:0|max:5',
            'commentaire' => 'nullable|string',
        ]);

        $userId = auth()->id();

        // Vérifie si l'utilisateur a déjà noté ce produit
        $avisExistant = Avis::where('user_id', $userId)
            ->where('produit_id', $request->produit_id)
            ->first();

        if ($avisExistant) {
            // Met à jour la note existante
            $avisExistant->update([
                'note' => $request->note,
                'commentaire' => $request->commentaire,
            ]);
        } else {
            // Crée un nouvel avis
            Avis::create([
                'produit_id' => $request->produit_id,
                'user_id' => $userId,
                'note' => $request->note,
                'commentaire' => $request->commentaire,
            ]);
        }

        // Recalculer la moyenne des avis pour ce produit
        $moyenne = Avis::where('produit_id', $request->produit_id)->avg('note');

        Produit::where('id', $request->produit_id)->update([
            'note' => round($moyenne, 2),
        ]);

        return response()->json(['message' => 'Votre avis a été enregistré avec succès.']);
    }

    public function avisParProduit(Request $request, $id)
{
    $noteMin = $request->query('min_note', 0); // par défaut, aucune limite
    $perPage = $request->query('per_page', 10); // nombre d'avis par page (par défaut : 10)

    $avis = \App\Models\Avis::with('user:id,nom_raison_sociale')
        ->where('produit_id', $id)
        ->where('note', '>=', $noteMin)
        ->orderByDesc('created_at')
        ->paginate($perPage);

    return response()->json([
        'message' => 'Liste des avis pour le produit ID ' . $id,
        'avis' => $avis
    ]);
}


}
