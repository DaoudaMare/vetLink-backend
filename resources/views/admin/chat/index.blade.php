@extends('layouts.app')

@section('pagetitle')
<h1>Modération Chat</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Chat</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Conversations</h5>
        <div>
          <a href="{{ route('admin.chat.flagged') }}" class="btn btn-warning me-2">
            <i class="bi bi-flag"></i> Signalées ({{ $flaggedCount }})
          </a>
          <a href="{{ route('admin.chat.stats') }}" class="btn btn-info">
            <i class="bi bi-graph-up"></i> Statistiques
          </a>
        </div>
      </div>
      
      <div class="card-body">
        <!-- Filtres -->
        <form method="GET" class="row g-3 mb-4">
          <div class="col-md-3">
            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Rechercher utilisateur...">
          </div>
          <div class="col-md-2">
            <select name="status" class="form-select">
              <option value="">Tous statuts</option>
              <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
              <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Fermée</option>
              <option value="flagged" {{ request('status') == 'flagged' ? 'selected' : '' }}>Signalée</option>
            </select>
          </div>
          <div class="col-md-2">
            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
          </div>
          <div class="col-md-2">
            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
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
                <th>Participants</th>
                <th>Dernier Message</th>
                <th>Messages</th>
                <th>Statut</th>
                <th>Dernière Activité</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($conversations as $conversation)
              <tr>
                <td>
                  <div class="d-flex flex-column">
                    @foreach($conversation->users as $user)
                      <div class="mb-1">
                        <strong>{{ $user->firstName }} {{ $user->lastName }}</strong>
                        @if($user->is_suspended)
                          <span class="badge bg-danger ms-1">Suspendu</span>
                        @endif
                        <br>
                        <small class="text-muted">{{ $user->email }}</small>
                      </div>
                    @endforeach
                  </div>
                </td>
                <td>
                  @if($conversation->lastMessage)
                    <div class="text-truncate" style="max-width: 200px;">
                      {{ $conversation->lastMessage->content }}
                    </div>
                    <small class="text-muted">
                      Par {{ $conversation->lastMessage->user->firstName }}
                    </small>
                  @else
                    <span class="text-muted">Aucun message</span>
                  @endif
                </td>
                <td>
                  <span class="badge bg-info">{{ $conversation->messages_count }}</span>
                  @if($conversation->flagged_messages_count > 0)
                    <span class="badge bg-warning ms-1">{{ $conversation->flagged_messages_count }} signalé(s)</span>
                  @endif
                </td>
                <td>
                  @if($conversation->is_closed)
                    <span class="badge bg-secondary">Fermée</span>
                  @elseif($conversation->flagged_messages_count > 0)
                    <span class="badge bg-warning">Signalée</span>
                  @else
                    <span class="badge bg-success">Active</span>
                  @endif
                </td>
                <td>{{ $conversation->updated_at->diffForHumans() }}</td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.chat.show', $conversation) }}" class="btn btn-outline-primary">
                      <i class="bi bi-eye"></i>
                    </a>
                    
                    @if(!$conversation->is_closed)
                      <form method="POST" action="{{ route('admin.chat.close', $conversation) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning" title="Fermer">
                          <i class="bi bi-lock"></i>
                        </button>
                      </form>
                    @else
                      <form method="POST" action="{{ route('admin.chat.reopen', $conversation) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success" title="Rouvrir">
                          <i class="bi bi-unlock"></i>
                        </button>
                      </form>
                    @endif

                    <div class="dropdown">
                      <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i>
                      </button>
                      <ul class="dropdown-menu">
                        <li>
                          <a class="dropdown-item" href="{{ route('admin.chat.show', $conversation) }}">
                            <i class="bi bi-eye"></i> Voir détails
                          </a>
                        </li>
                        @if($conversation->flagged_messages_count > 0)
                          <li>
                            <a class="dropdown-item text-warning" href="{{ route('admin.chat.flagged-messages', $conversation) }}">
                              <i class="bi bi-flag"></i> Messages signalés
                            </a>
                          </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        @foreach($conversation->users as $user)
                          @if(!$user->is_suspended)
                            <li>
                              <form method="POST" action="{{ route('admin.chat.suspend-user', $user) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" 
                                        onclick="return confirm('Suspendre {{ $user->firstName }} du chat ?')">
                                  <i class="bi bi-person-x"></i> Suspendre {{ $user->firstName }}
                                </button>
                              </form>
                            </li>
                          @endif
                        @endforeach
                      </ul>
                    </div>
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center text-muted">Aucune conversation trouvée</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
          {{ $conversations->withQueryString()->links() }}
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
        <h5 class="card-title">{{ number_format($stats['total_conversations']) }}</h5>
        <p class="card-text text-muted">Total Conversations</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-success">{{ number_format($stats['active_conversations']) }}</h5>
        <p class="card-text text-muted">Actives</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-warning">{{ number_format($stats['flagged_conversations']) }}</h5>
        <p class="card-text text-muted">Signalées</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-danger">{{ number_format($stats['suspended_users']) }}</h5>
        <p class="card-text text-muted">Utilisateurs Suspendus</p>
      </div>
    </div>
  </div>
</div>

<!-- Actions en Masse -->
<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h6 class="card-title mb-0">Actions de Modération</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h6>Envoyer un Avertissement</h6>
            <form method="POST" action="{{ route('admin.chat.send-warning') }}">
              @csrf
              <div class="mb-3">
                <select name="user_id" class="form-select" required>
                  <option value="">Sélectionner un utilisateur</option>
                  @foreach($recentUsers as $user)
                    <option value="{{ $user->id }}">{{ $user->firstName }} {{ $user->lastName }} ({{ $user->email }})</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <textarea name="message" class="form-control" rows="3" placeholder="Message d'avertissement..." required></textarea>
              </div>
              <button type="submit" class="btn btn-warning">
                <i class="bi bi-exclamation-triangle"></i> Envoyer Avertissement
              </button>
            </form>
          </div>
          <div class="col-md-6">
            <h6>Statistiques Rapides</h6>
            <div class="list-group">
              <div class="list-group-item d-flex justify-content-between align-items-center">
                Messages aujourd'hui
                <span class="badge bg-primary">{{ $stats['today_messages'] ?? 0 }}</span>
              </div>
              <div class="list-group-item d-flex justify-content-between align-items-center">
                Nouveaux signalements
                <span class="badge bg-warning">{{ $stats['new_flags'] ?? 0 }}</span>
              </div>
              <div class="list-group-item d-flex justify-content-between align-items-center">
                Conversations fermées cette semaine
                <span class="badge bg-secondary">{{ $stats['closed_this_week'] ?? 0 }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
