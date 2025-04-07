@extends('layouts.app')

@section('pagetitle')
<h1>Traçabilité des Produits</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Produits</li>
    <li class="breadcrumb-item active">Traçabilité</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Traçabilité des Produits</h5>

      <!-- Table pour afficher la traçabilité des produits -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">Nom du Produit</th>
            <th scope="col">Origine</th>
            <th scope="col">Producteur</th>
            <th scope="col">Date de Production</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>Tomates Bio</td>
            <td>Ferme Bio du Sud</td>
            <td>John Doe</td>
            <td>01/01/2023</td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewTraceabilityModal1">
                <i class="bi bi-eye"></i> Voir
              </button>
            </td>
          </tr>
          <tr>
            <td>Pommes Golden</td>
            <td>Verger de l'Ouest</td>
            <td>Jane Smith</td>
            <td>15/02/2023</td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewTraceabilityModal2">
                <i class="bi bi-eye"></i> Voir
              </button>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des produits -->
        </tbody>
      </table>
      <!-- Fin de la table de traçabilité -->
    </div>
  </div>
</section>

<!-- Modal pour voir les détails de la traçabilité (exemple pour "Tomates Bio") -->
<div class="modal fade" id="viewTraceabilityModal1" tabindex="-1" aria-labelledby="viewTraceabilityModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewTraceabilityModalLabel1">Détails de la Traçabilité</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nom du Produit :</strong> Tomates Bio</p>
        <p><strong>Origine :</strong> Ferme Bio du Sud</p>
        <p><strong>Producteur :</strong> John Doe</p>
        <p><strong>Date de Production :</strong> 01/01/2023</p>
        <p><strong>Certifications :</strong> Bio, GlobalG.A.P.</p>
        <p><strong>Document :</strong> <a href="#" class="btn btn-sm btn-primary"><i class="bi bi-download"></i> Télécharger</a></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour voir les détails de la traçabilité (exemple pour "Pommes Golden") -->
<div class="modal fade" id="viewTraceabilityModal2" tabindex="-1" aria-labelledby="viewTraceabilityModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewTraceabilityModalLabel2">Détails de la Traçabilité</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nom du Produit :</strong> Pommes Golden</p>
        <p><strong>Origine :</strong> Verger de l'Ouest</p>
        <p><strong>Producteur :</strong> Jane Smith</p>
        <p><strong>Date de Production :</strong> 15/02/2023</p>
        <p><strong>Certifications :</strong> GlobalG.A.P.</p>
        <p><strong>Document :</strong> <a href="#" class="btn btn-sm btn-primary"><i class="bi bi-download"></i> Télécharger</a></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<!-- Fin des Modals -->

@endsection