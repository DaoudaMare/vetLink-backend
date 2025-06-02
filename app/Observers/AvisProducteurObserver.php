<?php
namespace App\Observers;

use App\Models\AvisProducteur;
use App\Models\Producteur;

class AvisProducteurObserver
{
    public function created(AvisProducteur $avis)
    {
        self::updateNote($avis->producteur_id);
    }

    public function updated(AvisProducteur $avis)
    {
        self::updateNote($avis->producteur_id);
    }

    public function deleted(AvisProducteur $avis)
    {
        self::updateNote($avis->producteur_id);
    }

    private static function updateNote($producteurId)
    {
        $moyenne = AvisProducteur::where('producteur_id', $producteurId)->avg('note');
        Producteur::where('id', $producteurId)->update([
            'notation' => round($moyenne ?? 0, 2)
        ]);
    }
}
