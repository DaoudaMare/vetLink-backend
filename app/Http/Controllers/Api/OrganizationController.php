<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrganizationType;
use App\Models\BusinessSector;
use Illuminate\Http\JsonResponse;

class OrganizationController extends Controller
{
    /**
     * Récupère la liste des types d'organisations.
     */
    public function getTypes(): JsonResponse
    {
        $types = OrganizationType::all();
        return response()->json(['data' => $types]);
    }

    /**
     * Récupère la liste des secteurs d'activité.
     */
    public function getSectors(): JsonResponse
    {
        $sectors = BusinessSector::all();
        return response()->json(['data' => $sectors]);
    }
}