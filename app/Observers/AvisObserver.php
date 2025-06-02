<?php

namespace App\Observers;
namespace App\Observers;

use App\Models\Avis;
use App\Models\Produit;

class AvisObserver
{
    public function created(Avis $avis)
    {
        self::updateProduitNote($avis->produit_id);
    }

    public function updated(Avis $avis)
    {
        self::updateProduitNote($avis->produit_id);
    }

    public function deleted(Avis $avis)
    {
        self::updateProduitNote($avis->produit_id);
    }

    private static function updateProduitNote($produitId)
    {
        $moyenne = \App\Models\Avis::where('produit_id', $produitId)->avg('note');
        Produit::where('id', $produitId)->update([
            'note' => round($moyenne ?? 0, 2)
        ]);
    }
}
