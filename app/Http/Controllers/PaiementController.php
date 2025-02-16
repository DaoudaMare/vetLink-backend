<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;

class PaiementController extends Controller
{
    // Créer un paiement
    public function store(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:1',
            'devise' => 'required|string',
            'operateur' => 'required|string',
            'telephone' => 'required|string|min:8|max:15',
            'mode_paiement' => 'required|string',
            'commande_id' => 'required|exists:commandes,id'
        ]);

        $paiement = Paiement::create([
            //'transaction_id' => uniqid('txn_'),
            'operateur' => $request->operateur,
            'devise' => $request->devise,
            'status' => 'pending', // Toujours en attente à la création
            'telephone' => $request->telephone,
            'montant' => $request->montant,
            'date_paiement' => now(),
            'mode_paiement' => $request->mode_paiement,
            'commande_id' => $request->commande_id,
        ]);

        return response()->json([
            'message' => 'Paiement initié avec succès',
            'paiement' => $paiement
        ], 201);
    }

    // Obtenir les détails d'un paiement
    public function show($id)
    {
        $paiement = Paiement::findOrFail($id);
        return response()->json($paiement);
    }

    // Confirmer un paiement
    public function confirm($id)
    {
        $paiement = Paiement::findOrFail($id);
        if ($paiement->status !== 'pending') {
            return response()->json(['error' => 'Le paiement ne peut pas être confirmé'], 400);
        }
        $paiement->update(['status' => 'confirmed']);
        return response()->json(['message' => 'Paiement confirmé', 'paiement' => $paiement]);
    }

    // Annuler un paiement
    public function cancel($id)
    {
        $paiement = Paiement::findOrFail($id);
        if ($paiement->status !== 'pending') {
            return response()->json(['error' => 'Le paiement ne peut pas être annulé'], 400);
        }
        $paiement->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Paiement annulé', 'paiement' => $paiement]);
    }

    // Liste des paiements
    public function index()
    {
        $paiements = Paiement::all();
        return response()->json($paiements);
    }

    // Rembourser un paiement
    public function refund($id)
    {
        $paiement = Paiement::findOrFail($id);
        if ($paiement->status !== 'confirmed') {
            return response()->json(['error' => 'Seuls les paiements confirmés peuvent être remboursés'], 400);
        }
        $paiement->update(['status' => 'refunded']);
        return response()->json(['message' => 'Paiement remboursé', 'paiement' => $paiement]);
    }
}
