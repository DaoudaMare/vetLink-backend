@extends('layouts.app')

@section('pagetitle')
<h1>Paramètres des Notifications</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item">Notifications</li>
    <li class="breadcrumb-item active">Paramètres</li>
  </ol>
</nav>
@endsection

@section('content')
<section class="section">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Configurer les Paramètres des Notifications</h5>

      <!-- Formulaire pour configurer les paramètres des notifications -->
      <form>
        <div class="mb-3">
          <label for="notificationFrequency" class="form-label">Fréquence des Relances</label>
          <select class="form-control" id="notificationFrequency">
            <option value="daily">Quotidienne</option>
            <option value="weekly">Hebdomadaire</option>
            <option value="monthly">Mensuelle</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="notificationChannels" class="form-label">Canaux de Notification</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
            <label class="form-check-label" for="emailNotifications">Email</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
            <label class="form-check-label" for="pushNotifications">Notifications Push</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="inAppNotifications">
            <label class="form-check-label" for="inAppNotifications">Notifications In-App</label>
          </div>
        </div>
        <div class="mb-3">
          <label for="notificationTemplates" class="form-label">Modèles de Notification</label>
          <textarea class="form-control" id="notificationTemplates" rows="5" placeholder="Ex: Bonjour {nom}, votre profil est incomplet. Complétez-le pour accéder à toutes les fonctionnalités."></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les Paramètres</button>
      </form>
    </div>
  </div>
</section>
@endsection