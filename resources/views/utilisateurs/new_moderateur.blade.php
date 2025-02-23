@extends('layouts.app')

@section('pagetitle')
<h1>Liste des Utilisateurs Moderateur & Administrateurs</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Utilisateurs</li>
    <li class="breadcrumb-item active">Moderateur & Administrateurs</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Moderateur & Administrateurs</h5>

      <!-- Bouton pour ajouter une nouvelle récompense -->
      <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
          <i class="bi bi-plus-circle"></i> Ajouter un Moderateur ou Administrateurs
        </button>
      </div>

      <!-- Table pour afficher les récompenses -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">Nom</th>
            <th scope="col">Role d'Utilisateur</th>
            <th scope="col">Inscrit le</th>
            <th scope="col">Compte Activé</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>John Doe</td>
            <td>Administrateur</td>
            <td>01/01/2023</td>
            <td><span class="badge bg-success">Oui</span></td>
            <td>
              <button class="btn btn-info btn-sm" onclick="window.location.href='./user-details.html'">
                <i class="bi bi-eye"></i> Voir
              </button>
              <button class="btn btn-warning btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir suspendre cet utilisateur ?')">
                <i class="bi bi-pause"></i> Avertir
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette récompense ?')">
                <i class="bi bi-ban"></i> Bannir
              </button>
            </td>
          </tr>
          <tr>
            <td>Jane Smith</td>
            <td>Moderateur</td>
            <td>01/01/2023</td>
            <td><span class="badge bg-warning">Non</span></td>
            <td>
              <button class="btn btn-info btn-sm" onclick="window.location.href='./user-details.html'">
                <i class="bi bi-eye"></i> Voir
              </button>
              <button class="btn btn-warning btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir suspendre cet utilisateur ?')">
                <i class="bi bi-pause"></i> Avertir
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette récompense ?')">
                <i class="bi bi-ban"></i> Bannir
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
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Ajouter un utilisateur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="nom_raison_sociale" class="form-label">Nom & Prénom(s)</label>
            <input type="text" class="form-control" id="nom_raison_sociale" placeholder="Ex: Nom & Prénom(s)">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Adresse mail</label>
            <input type="email" class="form-control" id="email" placeholder="Ex: Adresse mail">
          </div>
          <div class="mb-3">
            <label for="pays" class="form-label">Pays</label>
            <select class="form-control" id="pays">
              <option value="+226">Burkina Faso</option>
              <option value="+225">Cote D'ivoir</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="telephone" class="form-label">Telephone(sans indicatif)</label>
           <input type="number" name="telephone" id="telephone" class="form-control" placeholder="Ex: Telephone(sans indicatif)">
          </div>
          <div class="mb-3">
            <label for="rewardType" class="form-label">Role</label>
            <select class="form-control" id="rewardType">
              <option value="moderateur">Moderateur</option>
              <option value="administrateur">Administrateur</option>
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
@endsection