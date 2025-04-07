<?php

namespace App\Http\Controllers\Admin\Produits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function listeProduits()
    {
        return view('produits.listes_produits');
    }
    public function certificationsProduit()
    {
        return view('produits.certifications');
    }

    public function traceabilityProduit()
    {
        return view('produits.traceability');
    }
}
