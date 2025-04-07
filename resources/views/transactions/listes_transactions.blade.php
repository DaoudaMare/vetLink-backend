@extends('layouts.app')

@section('pagetitle')
<h1>Liste des Transactions</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Transactions</li>
    <li class="breadcrumb-item active">Liste des Transactions</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Liste des Transactions</h5>

      <!-- Table pour afficher les transactions -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">ID Transaction</th>
            <th scope="col">Acheteur</th>
            <th scope="col">Vendeur</th>
            <th scope="col">Montant</th>
            <th scope="col">Date</th>
            <th scope="col">Statut</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>#2457</td>
            <td>John Doe</td>
            <td>Jane Smith</td>
            <td>$150</td>
            <td>01/01/2023</td>
            <td><span class="badge bg-success">Terminée</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewTransactionModal1">
                <i class="bi bi-eye"></i> Voir
              </button>
            </td>
          </tr>
          <tr>
            <td>#2147</td>
            <td>Alice Johnson</td>
            <td>Bob Brown</td>
            <td>$200</td>
            <td>15/02/2023</td>
            <td><span class="badge bg-warning">En Cours</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewTransactionModal2">
                <i class="bi bi-eye"></i> Voir
              </button>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des transactions -->
        </tbody>
      </table>
      <!-- Fin de la table des transactions -->
    </div>
  </div>
</section>

<!-- Modal pour voir les détails d'une transaction (exemple pour la transaction #2457) -->
<div class="modal fade" id="viewTransactionModal1" tabindex="-1" aria-labelledby="viewTransactionModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewTransactionModalLabel1">Détails de la Transaction</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>ID Transaction :</strong> #2457</p>
        <p><strong>Acheteur :</strong> John Doe</p>
        <p><strong>Vendeur :</strong> Jane Smith</p>
        <p><strong>Montant :</strong> $150</p>
        <p><strong>Date :</strong> 01/01/2023</p>
        <p><strong>Statut :</strong> <span class="badge bg-success">Terminée</span></p>
        <p><strong>Produits :</strong> Tomates Bio, Pommes Golden</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour voir les détails d'une transaction (exemple pour la transaction #2147) -->
<div class="modal fade" id="viewTransactionModal2" tabindex="-1" aria-labelledby="viewTransactionModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewTransactionModalLabel2">Détails de la Transaction</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>ID Transaction :</strong> #2147</p>
        <p><strong>Acheteur :</strong> Alice Johnson</p>
        <p><strong>Vendeur :</strong> Bob Brown</p>
        <p><strong>Montant :</strong> $200</p>
        <p><strong>Date :</strong> 15/02/2023</p>
        <p><strong>Statut :</strong> <span class="badge bg-warning">En Cours</span></p>
        <p><strong>Produits :</strong> Pommes Golden, Lait Bio</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<!-- Fin des Modals -->

@endsection