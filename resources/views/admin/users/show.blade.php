@extends('layouts.app')

@section('pagetitle')
<h1>Détails Utilisateur</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
    <li class="breadcrumb-item active">{{ $user->firstName }} {{ $user->lastName }}</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="row">
  <!-- Informations Principales -->
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Informations Utilisateur</h5>
        <div>
          @if($user->is_suspended)
            <form method="POST" action="{{ route('admin.users.activate', $user) }}" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Activer
              </button>
            </form>
          @else
            <form method="POST" action="{{ route('admin.users.suspend', $user) }}" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-danger" onclick="return confirm('Suspendre cet utilisateur ?')">
                <i class="bi bi-x-circle"></i> Suspendre
              </button>
            </form>
          @endif
          <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Modifier
          </a>
        </div>
      </div>
      
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr>
                <td><strong>ID:</strong></td>
                <td>{{ $user->id }}</td>
              </tr>
              <tr>
                <td><strong>Nom:</strong></td>
                <td>{{ $user->firstName }} {{ $user->lastName }}</td>
              </tr>
              <tr>
                <td><strong>Email:</strong></td>
                <td>
                  {{ $user->email }}
                  @if($user->email_verified_at)
                    <i class="bi bi-patch-check text-success ms-1" title="Email vérifié"></i>
                  @else
                    <i class="bi bi-exclamation-triangle text-warning ms-1" title="Email non vérifié"></i>
                  @endif
                </td>
              </tr>
              <tr>
                <td><strong>Téléphone:</strong></td>
                <td>{{ $user->phone ?? 'Non renseigné' }}</td>
              </tr>
              <tr>
                <td><strong>Type:</strong></td>
                <td>
                  <span class="badge bg-secondary">{{ $user->userType->title ?? 'N/A' }}</span>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr>
                <td><strong>Statut:</strong></td>
                <td>
                  @if($user->is_suspended)
                    <span class="badge bg-danger">Suspendu</span>
                  @elseif($user->profile_verified)
                    <span class="badge bg-success">Vérifié</span>
                  @else
                    <span class="badge bg-warning">En attente</span>
                  @endif
                </td>
              </tr>
              <tr>
                <td><strong>Inscription:</strong></td>
                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
              </tr>
              <tr>
                <td><strong>Dernière connexion:</strong></td>
                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
              </tr>
              <tr>
                <td><strong>Profil vérifié:</strong></td>
                <td>
                  @if($user->profile_verified)
                    <span class="text-success">Oui</span>
                  @else
                    <span class="text-warning">Non</span>
                    <form method="POST" action="{{ route('admin.users.verify-profile', $user) }}" class="d-inline ms-2">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-outline-success">Vérifier</button>
                    </form>
                  @endif
                </td>
              </tr>
            </table>
          </div>
        </div>

        @if($user->organization)
        <hr>
        <h6>Organisation</h6>
        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr>
                <td><strong>Nom:</strong></td>
                <td>{{ $user->organization->name }}</td>
              </tr>
              <tr>
                <td><strong>Secteur:</strong></td>
                <td>{{ $user->organization->businessSector->name ?? 'N/A' }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr>
                <td><strong>Adresse:</strong></td>
                <td>{{ $user->organization->address ?? 'Non renseignée' }}</td>
              </tr>
              <tr>
                <td><strong>Téléphone:</strong></td>
                <td>{{ $user->organization->phone ?? 'Non renseigné' }}</td>
              </tr>
            </table>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Actions et Statistiques -->
  <div class="col-lg-4">
    <!-- Actions Rapides -->
    <div class="card mb-3">
      <div class="card-header">
        <h6 class="card-title mb-0">Actions Rapides</h6>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
            @csrf
            <button type="submit" class="btn btn-outline-warning w-100">
              <i class="bi bi-key"></i> Réinitialiser Mot de Passe
            </button>
          </form>
          
          @if(!$user->email_verified_at)
          <form method="POST" action="{{ route('admin.users.verify-email', $user) }}">
            @csrf
            <button type="submit" class="btn btn-outline-info w-100">
              <i class="bi bi-envelope-check"></i> Vérifier Email
            </button>
          </form>
          @endif

          @if(!$user->profile_verified)
          <form method="POST" action="{{ route('admin.users.verify-profile', $user) }}">
            @csrf
            <button type="submit" class="btn btn-outline-success w-100">
              <i class="bi bi-person-check"></i> Vérifier Profil
            </button>
          </form>
          @endif
        </div>
      </div>
    </div>

    <!-- Statistiques -->
    <div class="card">
      <div class="card-header">
        <h6 class="card-title mb-0">Statistiques</h6>
      </div>
      <div class="card-body">
        @if($stats)
        <div class="row text-center">
          <div class="col-6">
            <h5 class="text-primary">{{ $stats['orders_count'] ?? 0 }}</h5>
            <small class="text-muted">Commandes</small>
          </div>
          <div class="col-6">
            <h5 class="text-success">{{ number_format($stats['total_spent'] ?? 0) }} €</h5>
            <small class="text-muted">Total Dépensé</small>
          </div>
          <div class="col-6 mt-3">
            <h5 class="text-info">{{ $stats['products_count'] ?? 0 }}</h5>
            <small class="text-muted">Produits</small>
          </div>
          <div class="col-6 mt-3">
            <h5 class="text-warning">{{ $stats['reviews_count'] ?? 0 }}</h5>
            <small class="text-muted">Avis</small>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Activité Récente -->
@if($recentActivity && $recentActivity->count() > 0)
<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h6 class="card-title mb-0">Activité Récente</h6>
      </div>
      <div class="card-body">
        <div class="activity">
          @foreach($recentActivity as $activity)
          <div class="activity-item d-flex">
            <div class="activite-label">{{ $activity->created_at->diffForHumans() }}</div>
            <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
            <div class="activity-content">
              {{ $activity->description }}
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endif
@endsection
