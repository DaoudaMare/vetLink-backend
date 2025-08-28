@extends('layouts.app')

@section('pagetitle')
<h1>Tableau de Bord VetLink</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Tableau de Bord</li>
  </ol>
</nav>
@endsection

@section('content')
<!-- Alertes Système -->
<div id="alerts-container" class="mb-4"></div>

<div class="row">
  <!-- Statistiques Principales -->
  <div class="col-lg-8">
    <div class="row">

      <!-- Carte : Total Utilisateurs -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card sales-card">
          <div class="card-body">
            <h5 class="card-title">Total Utilisateurs</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-people"></i>
              </div>
              <div class="ps-3">
                <h6 id="total-users">{{ number_format($stats['total_users']) }}</h6>
                <small class="text-muted">Inscrits sur la plateforme</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carte : Total Produits -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card revenue-card">
          <div class="card-body">
            <h5 class="card-title">Total Produits</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-box"></i>
              </div>
              <div class="ps-3">
                <h6>{{ number_format($stats['total_products']) }}</h6>
                @if($stats['pending_products'] > 0)
                  <span class="text-warning small">{{ $stats['pending_products'] }} en attente</span>
                @else
                  <small class="text-muted">Tous validés</small>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carte : Total Commandes -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card customers-card">
          <div class="card-body">
            <h5 class="card-title">Total Commandes</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-cart"></i>
              </div>
              <div class="ps-3">
                <h6>{{ number_format($stats['total_orders']) }}</h6>
                @if($stats['pending_orders'] > 0)
                  <span class="text-danger small">{{ $stats['pending_orders'] }} en attente</span>
                @else
                  <small class="text-muted">Toutes traitées</small>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carte : Chiffre d'Affaires -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card revenue-card">
          <div class="card-body">
            <h5 class="card-title">Chiffre d'Affaires</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-currency-euro"></i>
              </div>
              <div class="ps-3">
                <h6>{{ number_format($stats['total_revenue'], 0, ',', ' ') }} €</h6>
                <small class="text-muted">Total des ventes</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carte : Évaluations -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Évaluations</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-star"></i>
              </div>
              <div class="ps-3">
                <h6>{{ number_format($stats['total_reviews']) }}</h6>
                <small class="text-muted">Avis clients</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carte : Conversations Actives -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Chat Actif</h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-chat-dots"></i>
              </div>
              <div class="ps-3">
                <h6>{{ number_format($stats['active_conversations']) }}</h6>
                <small class="text-muted">Conversations cette semaine</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Carte : Produits Certifiés -->
      <div class="col-xxl-4 col-xl-12">
        <div class="card info-card customers-card">
          <div class="card-body">
            <h5 class="card-title">Produits Certifiés <span>| Ce Mois</span></h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-check-circle"></i>
              </div>
              <div class="ps-3">
                <h6>1,234</h6>
                <span class="text-success small pt-1 fw-bold">15%</span> <span class="text-muted small pt-2 ps-1">augmentation</span>
              </div>
            </div>
          </div>
        </div>
      </div><!-- Fin Carte Produits Certifiés -->

      <!-- Graphique des Ventes Mensuelles -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Évolution Mensuelle</h5>
            <canvas id="monthlyChart" style="max-height: 400px;"></canvas>
          </div>
        </div>

    </div>
  </div>

  <!-- Colonne de Droite -->
  <div class="col-lg-4">
    
    <!-- Actions Rapides -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Actions Rapides</h5>
        <div class="d-grid gap-2">
          <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
            <i class="bi bi-people"></i> Gérer Utilisateurs
          </a>
          <a href="{{ route('admin.products.pending') }}" class="btn btn-warning">
            <i class="bi bi-box"></i> Produits en Attente ({{ $stats['pending_products'] }})
          </a>
          <a href="{{ route('admin.orders.pending') }}" class="btn btn-danger">
            <i class="bi bi-cart"></i> Commandes en Attente ({{ $stats['pending_orders'] }})
          </a>
          <a href="{{ route('admin.chat.index') }}" class="btn btn-info">
            <i class="bi bi-chat-dots"></i> Modération Chat
          </a>
        </div>
      </div>
    </div>

    <!-- Nouveaux Utilisateurs -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Nouveaux Utilisateurs <span class="badge bg-primary">{{ $newUsers->count() }}</span></h5>
        <div class="activity">
          @forelse($newUsers as $user)
          <div class="activity-item d-flex">
            <div class="activite-label">{{ $user->created_at->diffForHumans() }}</div>
            <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
            <div class="activity-content">
              <strong>{{ $user->firstName }} {{ $user->lastName }}</strong><br>
              <small class="text-muted">{{ $user->userType->title ?? 'N/A' }} - {{ $user->email }}</small>
            </div>
          </div>
          @empty
          <p class="text-muted">Aucun nouvel utilisateur cette semaine</p>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Commandes Récentes -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Commandes Récentes</h5>
        <div class="activity">
          @forelse($recentOrders as $order)
          <div class="activity-item d-flex">
            <div class="activite-label">{{ $order->created_at->diffForHumans() }}</div>
            <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
            <div class="activity-content">
              <strong>{{ $order->num }}</strong><br>
              <small class="text-muted">
                {{ $order->customer->firstName }} {{ $order->customer->lastName }} - 
                {{ number_format($order->total_price) }} €
              </small>
            </div>
          </div>
          @empty
          <p class="text-muted">Aucune commande récente</p>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Produits en Attente -->
    @if($pendingProducts->count() > 0)
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Produits à Valider <span class="badge bg-warning">{{ $pendingProducts->count() }}</span></h5>
        <div class="activity">
          @foreach($pendingProducts as $product)
          <div class="activity-item d-flex">
            <div class="activite-label">{{ $product->created_at->diffForHumans() }}</div>
            <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
            <div class="activity-content">
              <strong>{{ $product->name }}</strong><br>
              <small class="text-muted">Par {{ $product->producer->firstName }} {{ $product->producer->lastName }}</small>
              <div class="mt-1">
                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-primary">Voir</a>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif

  </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique mensuel
const monthlyData = @json($monthlyStats);
const ctx = document.getElementById('monthlyChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: monthlyData.map(item => item.month),
        datasets: [{
            label: 'Utilisateurs',
            data: monthlyData.map(item => item.users),
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }, {
            label: 'Commandes',
            data: monthlyData.map(item => item.orders),
            borderColor: 'rgb(255, 99, 132)',
            tension: 0.1
        }, {
            label: 'Produits',
            data: monthlyData.map(item => item.products),
            borderColor: 'rgb(255, 205, 86)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Mise à jour des stats en temps réel
function updateLiveStats() {
    fetch('{{ route("admin.dashboard.live-stats") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-users').textContent = data.today_users;
            // Mettre à jour d'autres éléments si nécessaire
        })
        .catch(error => console.error('Erreur:', error));
}

// Charger les alertes
function loadAlerts() {
    fetch('{{ route("admin.dashboard.alerts") }}')
        .then(response => response.json())
        .then(alerts => {
            const container = document.getElementById('alerts-container');
            container.innerHTML = '';
            alerts.forEach(alert => {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${alert.type} alert-dismissible fade show`;
                alertDiv.innerHTML = `
                    ${alert.message}
                    ${alert.action ? `<a href="${alert.action}" class="alert-link ms-2">Voir</a>` : ''}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                container.appendChild(alertDiv);
            });
        });
}

// Actualiser toutes les 30 secondes
setInterval(updateLiveStats, 30000);
setInterval(loadAlerts, 60000);

// Charger au démarrage
loadAlerts();
</script>
@endpush