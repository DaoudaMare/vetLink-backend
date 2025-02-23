@extends('layouts.app')

@section('pagetitle')
<h1>Récompenses</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Gamification</li>
    <li class="breadcrumb-item active">Récompenses</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Liste des Récompenses</h5>

      <!-- Bouton pour ajouter une nouvelle récompense -->
      <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRewardModal">
          <i class="bi bi-plus-circle"></i> Ajouter une Récompense
        </button>
      </div>

      <!-- Table pour afficher les récompenses -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">Nom de la Récompense</th>
            <th scope="col">Description</th>
            <th scope="col">Type</th>
            <th scope="col">Statut</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>Badge Vendeur Étoile</td>
            <td>Attribué aux vendeurs ayant réalisé 10 ventes en une semaine.</td>
            <td>Badge</td>
            <td><span class="badge bg-success">Actif</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editRewardModal1">
                <i class="bi bi-pencil"></i> Modifier
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette récompense ?')">
                <i class="bi bi-ban"></i> Désactiver
              </button>
            </td>
          </tr>
          <tr>
            <td>Bonus de Visibilité</td>
            <td>Mise en avant des produits pendant 7 jours.</td>
            <td>Bonus</td>
            <td><span class="badge bg-warning">Inactif</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editRewardModal2">
                <i class="bi bi-pencil"></i> Modifier
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette récompense ?')">
                <i class="bi bi-ban"></i> Désactiver
              </button>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des récompenses -->
        </tbody>
      </table>
      <!-- Fin de la table des récompenses -->
    </div>
  </div>
</section>

<!-- Modal pour ajouter une nouvelle récompense -->
<div class="modal fade" id="addRewardModal" tabindex="-1" aria-labelledby="addRewardModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRewardModalLabel">Ajouter une Nouvelle Récompense</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="rewardName" class="form-label">Nom de la Récompense</label>
            <input type="text" class="form-control" id="rewardName" placeholder="Ex: Badge Vendeur Étoile">
          </div>
          <div class="mb-3">
            <label for="rewardDescription" class="form-label">Description</label>
            <textarea class="form-control" id="rewardDescription" rows="3" placeholder="Ex: Attribué aux vendeurs ayant réalisé 10 ventes en une semaine"></textarea>
          </div>
          <div class="mb-3">
            <label for="rewardType" class="form-label">Type</label>
            <select class="form-control" id="rewardType">
              <option value="badge">Badge</option>
              <option value="bonus">Bonus</option>
            </select>
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

<!-- Modal pour modifier une récompense (exemple pour la récompense "Badge Vendeur Étoile") -->
<div class="modal fade" id="editRewardModal1" tabindex="-1" aria-labelledby="editRewardModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRewardModalLabel1">Modifier la Récompense</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="editRewardName1" class="form-label">Nom de la Récompense</label>
            <input type="text" class="form-control" id="editRewardName1" value="Badge Vendeur Étoile">
          </div>
          <div class="mb-3">
            <label for="editRewardDescription1" class="form-label">Description</label>
            <textarea class="form-control" id="editRewardDescription1" rows="3">Attribué aux vendeurs ayant réalisé 10 ventes en une semaine</textarea>
          </div>
          <div class="mb-3">
            <label for="editRewardType1" class="form-label">Type</label>
            <select class="form-control" id="editRewardType1">
              <option value="badge" selected>Badge</option>
              <option value="bonus">Bonus</option>
            </select>
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

<!-- Modal pour modifier une récompense (exemple pour la récompense "Bonus de Visibilité") -->
<div class="modal fade" id="editRewardModal2" tabindex="-1" aria-labelledby="editRewardModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRewardModalLabel2">Modifier la Récompense</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="editRewardName2" class="form-label">Nom de la Récompense</label>
            <input type="text" class="form-control" id="editRewardName2" value="Bonus de Visibilité">
          </div>
          <div class="mb-3">
            <label for="editRewardDescription2" class="form-label">Description</label>
            <textarea class="form-control" id="editRewardDescription2" rows="3">Mise en avant des produits pendant 7 jours</textarea>
          </div>
          <div class="mb-3">
            <label for="editRewardType2" class="form-label">Type</label>
            <select class="form-control" id="editRewardType2">
              <option value="badge">Badge</option>
              <option value="bonus" selected>Bonus</option>
            </select>
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