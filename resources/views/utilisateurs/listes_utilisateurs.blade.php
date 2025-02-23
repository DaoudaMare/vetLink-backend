@extends('layouts.app')

@section('pagetitle')
<h1>Liste des Utilisateurs</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Utilisateurs</li>
    <li class="breadcrumb-item active">Liste des Utilisateurs</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Liste des Utilisateurs</h5>

      <!-- Table pour afficher les utilisateurs -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">Nom</th>
            <th scope="col">Type d'Utilisateur</th>
            <th scope="col">Inscrit le</th>
            <th scope="col">Statut de Vérification</th>
            <th scope="col">Badges</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Supposition que ces données sont générées dynamiquement à partir d'une base de données -->
          <tr>
            <td>John Doe</td>
            <td>Producteur</td>
            <td>01/01/2023</td>
            <td><span class="badge bg-success">Vérifié</span></td>
            <td>
              <span class="badge bg-primary">Compte Certifié</span>
              <span class="badge bg-warning">Vendeur Vérifié</span>
            </td>
            <td>
              <button class="btn btn-info btn-sm" onclick="window.location.href='./user-details.html'">
                <i class="bi bi-eye"></i> Voir
              </button>
              <button class="btn btn-warning btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir suspendre cet utilisateur ?')">
                <i class="bi bi-pause"></i> Suspendre
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir bannir cet utilisateur ?')">
                <i class="bi bi-ban"></i> Bannir
              </button>
            </td>
          </tr>
          <tr>
            <td>Jane Smith</td>
            <td>Acheteur</td>
            <td>15/02/2023</td>
            <td><span class="badge bg-warning">En Attente</span></td>
            <td>
              <span class="badge bg-secondary">Acheteur Fiable</span>
            </td>
            <td>
              <button class="btn btn-info btn-sm" onclick="window.location.href='./user-details.html'">
                <i class="bi bi-eye"></i> Voir
              </button>
              <button class="btn btn-warning btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir suspendre cet utilisateur ?')">
                <i class="bi bi-pause"></i> Suspendre
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir bannir cet utilisateur ?')">
                <i class="bi bi-ban"></i> Bannir
              </button>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des utilisateurs -->
        </tbody>
      </table>
      <!-- Fin de la table des utilisateurs -->
    </div>
  </div>
</section>
@endsection