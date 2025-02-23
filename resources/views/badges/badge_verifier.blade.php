@extends('layouts.app')

@section('pagetitle')
<h1>Badges Vérifiés</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Badges</li>
    <li class="breadcrumb-item active">Badges Vérifiés</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Liste des Badges Vérifiés</h5>

      <!-- Bouton pour ajouter un nouveau badge -->
      <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBadgeModal">
          <i class="bi bi-plus-circle"></i> Ajouter un Badge
        </button>
      </div>

      <!-- Table pour afficher les badges vérifiés -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">Nom du Badge</th>
            <th scope="col">Description</th>
            <th scope="col">Critères d'Attribution</th>
            <th scope="col">Nombre d'Utilisateurs</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>Compte Certifié</td>
            <td>Badge attribué aux utilisateurs ayant complété leur profil et soumis des documents valides.</td>
            <td>
              <ul>
                <li>Profil complété à 100%</li>
                <li>Documents d'identité vérifiés</li>
              </ul>
            </td>
            <td>245</td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editBadgeModal1">
                <i class="bi bi-pencil"></i> Modifier
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce badge ?')">
                <i class="bi bi-trash"></i> Supprimer
              </button>
            </td>
          </tr>
          <tr>
            <td>Vendeur Vérifié</td>
            <td>Badge attribué aux vendeurs ayant effectué au moins 5 transactions sans litige.</td>
            <td>
              <ul>
                <li>5 transactions réussies</li>
                <li>Aucun litige majeur</li>
              </ul>
            </td>
            <td>120</td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editBadgeModal2">
                <i class="bi bi-pencil"></i> Modifier
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce badge ?')">
                <i class="bi bi-trash"></i> Supprimer
              </button>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des badges vérifiés -->
        </tbody>
      </table>
      <!-- Fin de la table des badges vérifiés -->
    </div>
  </div>
</section>

<!-- Modal pour ajouter un nouveau badge -->
<div class="modal fade" id="addBadgeModal" tabindex="-1" aria-labelledby="addBadgeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addBadgeModalLabel">Ajouter un Nouveau Badge</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="badgeName" class="form-label">Nom du Badge</label>
            <input type="text" class="form-control" id="badgeName" placeholder="Ex: Compte Certifié">
          </div>
          <div class="mb-3">
            <label for="badgeDescription" class="form-label">Description</label>
            <textarea class="form-control" id="badgeDescription" rows="3" placeholder="Ex: Badge attribué aux utilisateurs ayant complété leur profil"></textarea>
          </div>
          <div class="mb-3">
            <label for="badgeCriteria" class="form-label">Critères d'Attribution</label>
            <textarea class="form-control" id="badgeCriteria" rows="3" placeholder="Ex: Profil complété à 100%, Documents vérifiés"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour modifier un badge (exemple pour le badge "Compte Certifié") -->
<div class="modal fade" id="editBadgeModal1" tabindex="-1" aria-labelledby="editBadgeModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBadgeModalLabel1">Modifier le Badge</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="editBadgeName1" class="form-label">Nom du Badge</label>
            <input type="text" class="form-control" id="editBadgeName1" value="Compte Certifié">
          </div>
          <div class="mb-3">
            <label for="editBadgeDescription1" class="form-label">Description</label>
            <textarea class="form-control" id="editBadgeDescription1" rows="3">Badge attribué aux utilisateurs ayant complété leur profil et soumis des documents valides.</textarea>
          </div>
          <div class="mb-3">
            <label for="editBadgeCriteria1" class="form-label">Critères d'Attribution</label>
            <textarea class="form-control" id="editBadgeCriteria1" rows="3">Profil complété à 100%, Documents d'identité vérifiés</textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary">Enregistrer les Modifications</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour modifier un badge (exemple pour le badge "Vendeur Vérifié") -->
<div class="modal fade" id="editBadgeModal2" tabindex="-1" aria-labelledby="editBadgeModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBadgeModalLabel2">Modifier le Badge</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="editBadgeName2" class="form-label">Nom du Badge</label>
            <input type="text" class="form-control" id="editBadgeName2" value="Vendeur Vérifié">
          </div>
          <div class="mb-3">
            <label for="editBadgeDescription2" class="form-label">Description</label>
            <textarea class="form-control" id="editBadgeDescription2" rows="3">Badge attribué aux vendeurs ayant effectué au moins 5 transactions sans litige.</textarea>
          </div>
          <div class="mb-3">
            <label for="editBadgeCriteria2" class="form-label">Critères d'Attribution</label>
            <textarea class="form-control" id="editBadgeCriteria2" rows="3">5 transactions réussies, Aucun litige majeur</textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        <button type="button" class="btn btn-primary">Enregistrer les Modifications</button>
      </div>
    </div>
  </div>
</div>
<!-- Fin des Modals -->

@endsection