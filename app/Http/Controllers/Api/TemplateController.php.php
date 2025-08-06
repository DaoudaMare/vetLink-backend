<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    // TemplateController.php (Nouveau)
public function agriculturalTemplates()
{
    return response()->json([
        'price_request' => "Bonjour, je souhaiterais connaître votre prix pour [produit] en [quantité]. Quelles sont vos conditions de livraison vers [ville] ?",
        'sample_request' => "Serait-il possible de recevoir un échantillon de [produit] avant commande ? Quel est le coût et délai ?",
        'order_confirmation' => "Je confirme ma commande de [quantité] de [produit]. Merci de m'envoyer votre RIB pour le paiement."
    ]);
}
}
