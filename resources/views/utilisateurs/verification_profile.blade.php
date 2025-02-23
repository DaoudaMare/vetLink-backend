@extends('layouts.app')

@section('pagetitle')
<h1>Profiles en Cours de Vérification</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Utilisateurs</li>
    <li class="breadcrumb-item active">Profiles en Cours de Vérification</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Profiles en Attente de Vérification</h5>

      <!-- Table pour afficher les profils en attente de vérification -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">Nom</th>
            <th scope="col">Type d'Utilisateur</th>
            <th scope="col">Inscrit le</th>
            <th scope="col">Documents Soumis</th>
            <th scope="col">Statut</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>John Doe</td>
            <td>Producteur</td>
            <td>01/01/2023</td>
            <td>
              <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#documentModal1">
                <i class="bi bi-file-earmark-text"></i> Voir les Documents
              </a>
            </td>
            <td><span class="badge bg-warning">En Attente</span></td>
            <td>
              <button class="btn btn-success btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir approuver ce profil ?')">
                <i class="bi bi-check-circle"></i> Approuver
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir rejeter ce profil ?')">
                <i class="bi bi-x-circle"></i> Rejeter
              </button>
            </td>
          </tr>
          <tr>
            <td>Jane Smith</td>
            <td>Acheteur</td>
            <td>15/02/2023</td>
            <td>
              <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#documentModal2">
                <i class="bi bi-file-earmark-text"></i> Voir les Documents
              </a>
            </td>
            <td><span class="badge bg-warning">En Attente</span></td>
            <td>
              <button class="btn btn-success btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir approuver ce profil ?')">
                <i class="bi bi-check-circle"></i> Approuver
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir rejeter ce profil ?')">
                <i class="bi bi-x-circle"></i> Rejeter
              </button>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des profils en attente -->
        </tbody>
      </table>
      <!-- Fin de la table des profils en attente de vérification -->
    </div>
  </div>
</section>

<!-- Modals pour afficher les documents soumis -->
<!-- Modal 1 -->
<div class="modal fade" id="documentModal1" tabindex="-1" aria-labelledby="documentModalLabel1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="documentModalLabel1">Documents Soumis par John Doe</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <h6>Carte d'Identité</h6>
            <img src="{{asset('assets/img/id-card.jpg')}}" alt="Carte d'Identité" class="img-fluid">
          </div>
          <div class="col-md-6">
            <h6>Certificat de Producteur</h6>
            <img src="{{asset('assets/img/certificate.jpg')}}" alt="Certificat de Producteur" class="img-fluid">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal 2 -->
<div class="modal fade" id="documentModal2" tabindex="-1" aria-labelledby="documentModalLabel2" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="documentModalLabel2">Documents Soumis par Jane Smith</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <h6>Carte d'Identité</h6>
            <img src="{{asset('assets/img/id-card.jpg')}}" alt="Carte d'Identité" class="img-fluid">
          </div>
          <div class="col-md-6">
            <h6>Registre de Commerce</h6>
            <img src="{{asset('assets/img/commerce-register.jpg')}}" alt="Registre de Commerce" class="img-fluid">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<!-- Fin des Modals -->

@endsection