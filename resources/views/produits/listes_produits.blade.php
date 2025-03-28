@extends('layouts.app')

@section('pagetitle')
<h1>Liste des Produits</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Produits</li>
    <li class="breadcrumb-item active">Liste des Produits</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Liste des Produits</h5>

      <!-- Table pour afficher les produits -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">Nom du Produit</th>
            <th scope="col">Catégorie</th>
            <th scope="col">Producteur</th>
            <th scope="col">Prix</th>
            <th scope="col">Statut</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>Tomates Bio</td>
            <td>Légumes</td>
            <td>John Doe</td>
            <td>$2.50/kg</td>
            <td><span class="badge bg-success">Disponible</span></td>
            <td>
              <button class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i> Voir
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                <i class="bi bi-ban"></i> Rejeter
              </button>
            </td>
          </tr>
          <tr>
            <td>Pommes Golden</td>
            <td>Fruits</td>
            <td>Jane Smith</td>
            <td>$1.80/kg</td>
            <td><span class="badge bg-warning">En Rupture</span></td>
            <td>
              <button class="btn btn-info btn-sm">
                <i class="bi bi-eye"></i> Voir
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                <i class="bi bi-ban"></i> Rejeter
              </button>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des produits -->
        </tbody>
      </table>
      <!-- Fin de la table des produits -->
    </div>
  </div>
</section>
@endsection