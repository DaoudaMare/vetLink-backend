@extends('layouts.app')

@section('pagetitle')
<h1>Litiges</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Transactions</li>
    <li class="breadcrumb-item active">Litiges</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Liste des Litiges</h5>

      <!-- Table pour afficher les litiges -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">ID Litige</th>
            <th scope="col">Transaction</th>
            <th scope="col">Acheteur</th>
            <th scope="col">Vendeur</th>
            <th scope="col">Raison</th>
            <th scope="col">Statut</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>#123</td>
            <td>#2457</td>
            <td>John Doe</td>
            <td>Jane Smith</td>
            <td>Produit non conforme</td>
            <td><span class="badge bg-warning">En Cours</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewDisputeModal1">
                <i class="bi bi-eye"></i> Voir
              </button>
              <button class="btn btn-success btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir résoudre ce litige ?')">
                <i class="bi bi-check-circle"></i> Résoudre
              </button>
            </td>
          </tr>
          <tr>
            <td>#124</td>
            <td>#2147</td>
            <td>Alice Johnson</td>
            <td>Bob Brown</td>
            <td>Retard de livraison</td>
            <td><span class="badge bg-success">Résolu</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewDisputeModal2">
                <i class="bi bi-eye"></i> Voir
              </button>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des litiges -->
        </tbody>
      </table>
      <!-- Fin de la table des litiges -->
    </div>
  </div>
</section>

<!-- Modal pour voir les détails d'un litige (exemple pour le litige #123) -->
<div class="modal fade" id="viewDisputeModal1" tabindex="-1" aria-labelledby="viewDisputeModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewDisputeModalLabel1">Détails du Litige</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>ID Litige :</strong> #123</p>
        <p><strong>Transaction :</strong> #2457</p>
        <p><strong>Acheteur :</strong> John Doe</p>
        <p><strong>Vendeur :</strong> Jane Smith</p>
        <p><strong>Raison :</strong> Produit non conforme</p>
        <p><strong>Statut :</strong> <span class="badge bg-warning">En Cours</span></p>
        <p><strong>Commentaires :</strong> L'acheteur a signalé que les tomates ne correspondaient pas à la description.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour voir les détails d'un litige (exemple pour le litige #124) -->
<div class="modal fade" id="viewDisputeModal2" tabindex="-1" aria-labelledby="viewDisputeModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewDisputeModalLabel2">Détails du Litige</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>ID Litige :</strong> #124</p>
        <p><strong>Transaction :</strong> #2147</p>
        <p><strong>Acheteur :</strong> Alice Johnson</p>
        <p><strong>Vendeur :</strong> Bob Brown</p>
        <p><strong>Raison :</strong> Retard de livraison</p>
        <p><strong>Statut :</strong> <span class="badge bg-success">Résolu</span></p>
        <p><strong>Commentaires :</strong> Le vendeur a remboursé l'acheteur pour le retard.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<!-- Fin des Modals -->

@endsection