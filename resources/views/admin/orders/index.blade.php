@extends('layouts.app')

@section('pagetitle')
<h1>Gestion des Commandes</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Commandes</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Liste des Commandes</h5>
        <div>
          <a href="{{ route('admin.orders.pending') }}" class="btn btn-warning me-2">
            <i class="bi bi-clock"></i> En Attente ({{ $pendingCount }})
          </a>
          <a href="{{ route('admin.orders.export') }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export CSV
          </a>
        </div>
      </div>
      
      <div class="card-body">
        <!-- Filtres -->
        <form method="GET" class="row g-3 mb-4">
          <div class="col-md-3">
            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Numéro commande...">
          </div>
          <div class="col-md-2">
            <select name="status" class="form-select">
              <option value="">Tous statuts</option>
              <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>En attente</option>
              <option value="confirmee" {{ request('status') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
              <option value="en_preparation" {{ request('status') == 'en_preparation' ? 'selected' : '' }}>En préparation</option>
              <option value="expediee" {{ request('status') == 'expediee' ? 'selected' : '' }}>Expédiée</option>
              <option value="livree" {{ request('status') == 'livree' ? 'selected' : '' }}>Livrée</option>
              <option value="annulee" {{ request('status') == 'annulee' ? 'selected' : '' }}>Annulée</option>
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
                <th>Numéro</th>
                <th>Client</th>
                <th>Produits</th>
                <th>Total</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $order)
              <tr>
                <td>
                  <strong>{{ $order->num }}</strong><br>
                  <small class="text-muted">ID: {{ $order->id }}</small>
                </td>
                <td>
                  {{ $order->customer->firstName }} {{ $order->customer->lastName }}<br>
                  <small class="text-muted">{{ $order->customer->email }}</small>
                </td>
                <td>
                  <span class="badge bg-info">{{ $order->produits->count() }} produit(s)</span><br>
                  <small class="text-muted">{{ $order->produits->sum('pivot.quantity') }} article(s)</small>
                </td>
                <td>
                  <strong>{{ number_format($order->total_price, 0, ',', ' ') }} €</strong>
                </td>
                <td>
                  @switch($order->status)
                    @case('en_attente')
                      <span class="badge bg-warning">En attente</span>
                      @break
                    @case('confirmee')
                      <span class="badge bg-primary">Confirmée</span>
                      @break
                    @case('en_preparation')
                      <span class="badge bg-info">En préparation</span>
                      @break
                    @case('expediee')
                      <span class="badge bg-secondary">Expédiée</span>
                      @break
                    @case('livree')
                      <span class="badge bg-success">Livrée</span>
                      @break
                    @case('annulee')
                      <span class="badge bg-danger">Annulée</span>
                      @break
                    @default
                      <span class="badge bg-light text-dark">{{ $order->status }}</span>
                  @endswitch
                </td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary">
                      <i class="bi bi-eye"></i>
                    </a>
                    
                    @if($order->status !== 'annulee' && $order->status !== 'livree')
                      <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                          <i class="bi bi-gear"></i>
                        </button>
                        <ul class="dropdown-menu">
                          @if($order->status === 'en_attente')
                            <li>
                              <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="confirmee">
                                <button type="submit" class="dropdown-item">
                                  <i class="bi bi-check"></i> Confirmer
                                </button>
                              </form>
                            </li>
                          @endif
                          @if($order->status === 'confirmee')
                            <li>
                              <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="en_preparation">
                                <button type="submit" class="dropdown-item">
                                  <i class="bi bi-box"></i> En préparation
                                </button>
                              </form>
                            </li>
                          @endif
                          @if($order->status === 'en_preparation')
                            <li>
                              <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="expediee">
                                <button type="submit" class="dropdown-item">
                                  <i class="bi bi-truck"></i> Expédier
                                </button>
                              </form>
                            </li>
                          @endif
                          @if($order->status === 'expediee')
                            <li>
                              <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="livree">
                                <button type="submit" class="dropdown-item">
                                  <i class="bi bi-check-circle"></i> Marquer livrée
                                </button>
                              </form>
                            </li>
                          @endif
                          <li><hr class="dropdown-divider"></li>
                          <li>
                            <form method="POST" action="{{ route('admin.orders.cancel', $order) }}" class="d-inline">
                              @csrf
                              <button type="submit" class="dropdown-item text-danger" 
                                      onclick="return confirm('Annuler cette commande ?')">
                                <i class="bi bi-x-circle"></i> Annuler
                              </button>
                            </form>
                          </li>
                        </ul>
                      </div>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center text-muted">Aucune commande trouvée</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
          {{ $orders->withQueryString()->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Statistiques -->
<div class="row mt-4">
  <div class="col-md-2">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title">{{ number_format($stats['total']) }}</h5>
        <p class="card-text text-muted">Total</p>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-warning">{{ number_format($stats['pending']) }}</h5>
        <p class="card-text text-muted">En Attente</p>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-primary">{{ number_format($stats['confirmed']) }}</h5>
        <p class="card-text text-muted">Confirmées</p>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-success">{{ number_format($stats['delivered']) }}</h5>
        <p class="card-text text-muted">Livrées</p>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-danger">{{ number_format($stats['cancelled']) }}</h5>
        <p class="card-text text-muted">Annulées</p>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-info">{{ number_format($stats['total_revenue']) }} €</h5>
        <p class="card-text text-muted">CA Total</p>
      </div>
    </div>
  </div>
</div>
@endsection
