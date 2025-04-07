@extends('layouts.app')

@section('pagetitle')
<h1>Défis</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Gamification</li>
    <li class="breadcrumb-item active">Défis</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Liste des Défis</h5>

      <!-- Bouton pour ajouter un nouveau défi -->
      <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChallengeModal">
          <i class="bi bi-plus-circle"></i> Ajouter un Défi
        </button>
      </div>

      <!-- Table pour afficher les défis -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">Nom du Défi</th>
            <th scope="col">Description</th>
            <th scope="col">Récompense</th>
            <th scope="col">Statut</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>Défi de Vente</td>
            <td>Vendez 10 produits en une semaine.</td>
            <td>Badge "Vendeur Étoile"</td>
            <td><span class="badge bg-success">Actif</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editChallengeModal1">
                <i class="bi bi-pencil"></i> Modifier
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce défi ?')">
                <i class="bi bi-ban"></i> Désactiver
              </button>
            </td>
          </tr>
          <tr>
            <td>Défi d'Engagement</td>
            <td>Parrainez 5 nouveaux utilisateurs.</td>
            <td>Badge "Ambassadeur"</td>
            <td><span class="badge bg-warning">Inactif</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editChallengeModal2">
                <i class="bi bi-pencil"></i> Modifier
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce défi ?')">
                <i class="bi bi-ban"></i> Désactiver
              </button>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des défis -->
        </tbody>
      </table>
      <!-- Fin de la table des défis -->
    </div>
  </div>
</section>

<!-- Modal pour ajouter un nouveau défi -->
<div class="modal fade" id="addChallengeModal" tabindex="-1" aria-labelledby="addChallengeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addChallengeModalLabel">Ajouter un Nouveau Défi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="challengeName" class="form-label">Nom du Défi</label>
            <input type="text" class="form-control" id="challengeName" placeholder="Ex: Défi de Vente">
          </div>
          <div class="mb-3">
            <label for="challengeDescription" class="form-label">Description</label>
            <textarea class="form-control" id="challengeDescription" rows="3" placeholder="Ex: Vendez 10 produits en une semaine"></textarea>
          </div>
          <div class="mb-3">
            <label for="challengeReward" class="form-label">Récompense</label>
            <input type="text" class="form-control" id="challengeReward" placeholder="Ex: Badge Vendeur Étoile">
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

<!-- Modal pour modifier un défi (exemple pour le défi "Défi de Vente") -->
<div class="modal fade" id="editChallengeModal1" tabindex="-1" aria-labelledby="editChallengeModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editChallengeModalLabel1">Modifier le Défi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="editChallengeName1" class="form-label">Nom du Défi</label>
            <input type="text" class="form-control" id="editChallengeName1" value="Défi de Vente">
          </div>
          <div class="mb-3">
            <label for="editChallengeDescription1" class="form-label">Description</label>
            <textarea class="form-control" id="editChallengeDescription1" rows="3">Vendez 10 produits en une semaine</textarea>
          </div>
          <div class="mb-3">
            <label for="editChallengeReward1" class="form-label">Récompense</label>
            <input type="text" class="form-control" id="editChallengeReward1" value="Badge Vendeur Étoile">
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

<!-- Modal pour modifier un défi (exemple pour le défi "Défi d'Engagement") -->
<div class="modal fade" id="editChallengeModal2" tabindex="-1" aria-labelledby="editChallengeModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editChallengeModalLabel2">Modifier le Défi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="editChallengeName2" class="form-label">Nom du Défi</label>
            <input type="text" class="form-control" id="editChallengeName2" value="Défi d'Engagement">
          </div>
          <div class="mb-3">
            <label for="editChallengeDescription2" class="form-label">Description</label>
            <textarea class="form-control" id="editChallengeDescription2" rows="3">Parrainez 5 nouveaux utilisateurs</textarea>
          </div>
          <div class="mb-3">
            <label for="editChallengeReward2" class="form-label">Récompense</label>
            <input type="text" class="form-control" id="editChallengeReward2" value="Badge Ambassadeur">
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