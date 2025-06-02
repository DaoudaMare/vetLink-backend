<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;

use App\Models\AvisProducteur;
use App\Models\Producteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisProducteurController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'producteur_id' => 'required|exists:producteurs,id',
            'note' => 'required|numeric|min:0|max:5',
            'commentaire' => 'nullable|string',
        ]);

        $userId = Auth::id();

        // Vérifie si l'utilisateur a déjà noté ce producteur
        $avis = AvisProducteur::where('user_id', $userId)
            ->where('producteur_id', $request->producteur_id)
            ->first();

        if ($avis) {
            // Mise à jour de l'avis existant
            $avis->update([
                'note' => $request->note,
                'commentaire' => $request->commentaire,
            ]);
        } else {
            // Création d'un nouvel avis
            AvisProducteur::create([
                'user_id' => $userId,
                'producteur_id' => $request->producteur_id,
                'note' => $request->note,
                'commentaire' => $request->commentaire,
            ]);
        }

        // Recalcul de la moyenne des notes pour le producteur
        $moyenne = AvisProducteur::where('producteur_id', $request->producteur_id)->avg('note');

        Producteur::where('id', $request->producteur_id)->update([
            'notation' => round($moyenne, 2),
        ]);

        return response()->json(['message' => 'Avis enregistré pour le producteur avec succès.']);
    }


    public function avisParProducteur(Request $request, $id)
{
    $noteMin = $request->query('min_note', 0);
    $perPage = $request->query('per_page', 10);

    $avis = AvisProducteur::with('user:id,nom_raison_sociale')
        ->where('producteur_id', $id)
        ->where('note', '>=', $noteMin)
        ->orderByDesc('created_at')
        ->paginate($perPage);

    return response()->json([
        'message' => 'Avis du producteur ID ' . $id,
        'avis' => $avis
    ]);
}

}

