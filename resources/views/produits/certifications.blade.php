@extends('layouts.app')

@section('pagetitle')
<h1>Certifications des Produits</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Produits</li>
    <li class="breadcrumb-item active">Certifications des Produits</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Certifications des Produits</h5>

      <!-- Table pour afficher les certifications des produits -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">Nom du Produit</th>
            <th scope="col">Type de Certification</th>
            <th scope="col">Date d'Expiration</th>
            <th scope="col">Statut</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>Tomates Bio</td>
            <td>Bio</td>
            <td>01/01/2024</td>
            <td><span class="badge bg-success">Valide</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewCertificationModal1">
                <i class="bi bi-eye"></i> Voir
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette certification ?')">
                <i class="bi bi-trash"></i> Supprimer
              </button>
            </td>
          </tr>
          <tr>
            <td>Pommes Golden</td>
            <td>GlobalG.A.P.</td>
            <td>15/06/2023</td>
            <td><span class="badge bg-warning">Expiré</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewCertificationModal2">
                <i class="bi bi-eye"></i> Voir
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette certification ?')">
                <i class="bi bi-trash"></i> Supprimer
              </button>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des certifications -->
        </tbody>
      </table>
      <!-- Fin de la table des certifications -->
    </div>
  </div>
</section>

<!-- Modal pour voir les détails d'une certification (exemple pour "Tomates Bio") -->
<div class="modal fade" id="viewCertificationModal1" tabindex="-1" aria-labelledby="viewCertificationModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewCertificationModalLabel1">Détails de la Certification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nom du Produit :</strong> Tomates Bio</p>
        <p><strong>Type de Certification :</strong> Bio</p>
        <p><strong>Date d'Expiration :</strong> 01/01/2024</p>
        <p><strong>Statut :</strong> <span class="badge bg-success">Valide</span></p>
        <p><strong>Document :</strong> <a href="#" class="btn btn-sm btn-primary"><i class="bi bi-download"></i> Télécharger</a></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour voir les détails d'une certification (exemple pour "Pommes Golden") -->
<div class="modal fade" id="viewCertificationModal2" tabindex="-1" aria-labelledby="viewCertificationModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewCertificationModalLabel2">Détails de la Certification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Nom du Produit :</strong> Pommes Golden</p>
        <p><strong>Type de Certification :</strong> GlobalG.A.P.</p>
        <p><strong>Date d'Expiration :</strong> 15/06/2023</p>
        <p><strong>Statut :</strong> <span class="badge bg-warning">Expiré</span></p>
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