@extends('layouts.app')

@section('pagetitle')
<h1>Gestion des Utilisateurs</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Utilisateurs</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Liste des Utilisateurs</h5>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
          <i class="bi bi-plus"></i> Nouvel Utilisateur
        </a>
      </div>
      
      <div class="card-body">
        <!-- Filtres -->
        <form method="GET" class="row g-3 mb-4">
          <div class="col-md-3">
            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Rechercher...">
          </div>
          <div class="col-md-2">
            <select name="type" class="form-select">
              <option value="">Tous les types</option>
              @foreach($userTypes as $type)
                <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                  {{ $type->title }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <select name="status" class="form-select">
              <option value="">Tous les statuts</option>
              <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
              <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspendu</option>
              <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Vérifié</option>
            </select>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary">Filtrer</button>
          </div>
        </form>

        <!-- Tableau -->
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Type</th>
                <th>Statut</th>
                <th>Inscription</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
              <tr>
                <td>{{ $user->id }}</td>
                <td>
                  <div class="d-flex align-items-center">
                    <div>
                      <strong>{{ $user->firstName }} {{ $user->lastName }}</strong>
                      @if($user->email_verified_at)
                        <i class="bi bi-patch-check text-success ms-1" title="Email vérifié"></i>
                      @endif
                    </div>
                  </div>
                </td>
                <td>{{ $user->email }}</td>
                <td>
                  <span class="badge bg-secondary">{{ $user->userType->title ?? 'N/A' }}</span>
                </td>
                <td>
                  @if($user->is_suspended)
                    <span class="badge bg-danger">Suspendu</span>
                  @elseif($user->profile_verified)
                    <span class="badge bg-success">Vérifié</span>
                  @else
                    <span class="badge bg-warning">En attente</span>
                  @endif
                </td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary">
                      <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-secondary">
                      <i class="bi bi-pencil"></i>
                    </a>
                    @if($user->is_suspended)
                      <form method="POST" action="{{ route('admin.users.activate', $user) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success" title="Activer">
                          <i class="bi bi-check-circle"></i>
                        </button>
                      </form>
                    @else
                      <form method="POST" action="{{ route('admin.users.suspend', $user) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" title="Suspendre" 
                                onclick="return confirm('Suspendre cet utilisateur ?')">
                          <i class="bi bi-x-circle"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted">Aucun utilisateur trouvé</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
          {{ $users->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Statistiques -->
<div class="row mt-4">
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">{{ number_format($stats['total']) }}</h5>
        <p class="card-text text-muted">Total Utilisateurs</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-success">{{ number_format($stats['verified']) }}</h5>
        <p class="card-text text-muted">Vérifiés</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-warning">{{ number_format($stats['pending']) }}</h5>
        <p class="card-text text-muted">En Attente</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-danger">{{ number_format($stats['suspended']) }}</h5>
        <p class="card-text text-muted">Suspendus</p>
      </div>
    </div>
  </div>
</div>
@endsection
