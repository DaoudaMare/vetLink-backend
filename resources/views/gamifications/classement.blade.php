@extends('layouts.app')

@section('pagetitle')
<h1>Classement</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Gamification</li>
    <li class="breadcrumb-item active">Classement</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Classement des Utilisateurs</h5>

      <!-- Table pour afficher le classement -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">Position</th>
            <th scope="col">Nom</th>
            <th scope="col">Points</th>
            <th scope="col">Badges</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>1</td>
            <td>John Doe</td>
            <td>500</td>
            <td>
              <span class="badge bg-primary">Vendeur Étoile</span>
              <span class="badge bg-success">Ambassadeur</span>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>Jane Smith</td>
            <td>450</td>
            <td>
              <span class="badge bg-primary">Vendeur Étoile</span>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>Alice Johnson</td>
            <td>400</td>
            <td>
              <span class="badge bg-success">Ambassadeur</span>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des utilisateurs -->
        </tbody>
      </table>
      <!-- Fin de la table du classement -->
    </div>
  </div>
</section>
@endsection