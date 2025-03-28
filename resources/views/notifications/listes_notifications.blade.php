@extends('layouts.app')

@section('pagetitle')
<h1>Liste des Notifications</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Notifications</li>
    <li class="breadcrumb-item active">Liste des Notifications</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Liste des Notifications Envoyées</h5>

      <!-- Table pour afficher les notifications -->
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th scope="col">ID Notification</th>
            <th scope="col">Destinataire</th>
            <th scope="col">Type</th>
            <th scope="col">Message</th>
            <th scope="col">Date d'Envoi</th>
            <th scope="col">Statut</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Exemple de données statiques (à remplacer par des données dynamiques) -->
          <tr>
            <td>#123</td>
            <td>John Doe</td>
            <td>Relance de Profil</td>
            <td>Votre profil est incomplet. Complétez-le pour accéder à toutes les fonctionnalités.</td>
            <td>01/01/2023</td>
            <td><span class="badge bg-success">Envoyée</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewNotificationModal1">
                <i class="bi bi-eye"></i> Voir
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                <i class="bi bi-trash"></i> Supprimer
              </button>
            </td>
          </tr>
          <tr>
            <td>#124</td>
            <td>Jane Smith</td>
            <td>Notification de Transaction</td>
            <td>Votre transaction #2457 a été approuvée.</td>
            <td>15/02/2023</td>
            <td><span class="badge bg-warning">En Attente</span></td>
            <td>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewNotificationModal2">
                <i class="bi bi-eye"></i> Voir
              </button>
              <button class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette notification ?')">
                <i class="bi bi-trash"></i> Supprimer
              </button>
            </td>
          </tr>
          <!-- Ajoutez plus de lignes ici en fonction des notifications -->
        </tbody>
      </table>
      <!-- Fin de la table des notifications -->
    </div>
  </div>
</section>

<!-- Modal pour voir les détails d'une notification (exemple pour la notification #123) -->
<div class="modal fade" id="viewNotificationModal1" tabindex="-1" aria-labelledby="viewNotificationModalLabel1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewNotificationModalLabel1">Détails de la Notification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>ID Notification :</strong> #123</p>
        <p><strong>Destinataire :</strong> John Doe</p>
        <p><strong>Type :</strong> Relance de Profil</p>
        <p><strong>Message :</strong> Votre profil est incomplet. Complétez-le pour accéder à toutes les fonctionnalités.</p>
        <p><strong>Date d'Envoi :</strong> 01/01/2023</p>
        <p><strong>Statut :</strong> <span class="badge bg-success">Envoyée</span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal pour voir les détails d'une notification (exemple pour la notification #124) -->
<div class="modal fade" id="viewNotificationModal2" tabindex="-1" aria-labelledby="viewNotificationModalLabel2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewNotificationModalLabel2">Détails de la Notification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>ID Notification :</strong> #124</p>
        <p><strong>Destinataire :</strong> Jane Smith</p>
        <p><strong>Type :</strong> Notification de Transaction</p>
        <p><strong>Message :</strong> Votre transaction #2457 a été approuvée.</p>
        <p><strong>Date d'Envoi :</strong> 15/02/2023</p>
        <p><strong>Statut :</strong> <span class="badge bg-warning">En Attente</span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
<!-- Fin des Modals -->

@endsection