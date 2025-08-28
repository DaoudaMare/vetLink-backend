@extends('layouts.app')

@section('pagetitle')
<h1>Gestion des Produits</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
    <li class="breadcrumb-item active">Produits</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Liste des Produits</h5>
        <div>
          <a href="{{ route('admin.products.pending') }}" class="btn btn-warning me-2">
            <i class="bi bi-clock"></i> En Attente ({{ $pendingCount }})
          </a>
          <a href="{{ route('admin.products.featured') }}" class="btn btn-success">
            <i class="bi bi-star"></i> En Vedette
          </a>
        </div>
      </div>
      
      <div class="card-body">
        <!-- Filtres -->
        <form method="GET" class="row g-3 mb-4">
          <div class="col-md-3">
            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Rechercher produit...">
          </div>
          <div class="col-md-2">
            <select name="category" class="form-select">
              <option value="">Toutes catégories</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <select name="status" class="form-select">
              <option value="">Tous statuts</option>
              <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approuvé</option>
              <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
              <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
            </select>
          </div>
          <div class="col-md-2">
            <select name="featured" class="form-select">
              <option value="">Vedette</option>
              <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>En vedette</option>
              <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Normal</option>
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
                <th>Image</th>
                <th>Nom</th>
                <th>Producteur</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Stock</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($products as $product)
              <tr>
                <td>
                  @if($product->images->count() > 0)
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                         alt="{{ $product->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                  @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                      <i class="bi bi-image text-muted"></i>
                    </div>
                  @endif
                </td>
                <td>
                  <div>
                    <strong>{{ $product->name }}</strong>
                    @if($product->is_featured)
                      <i class="bi bi-star-fill text-warning ms-1" title="En vedette"></i>
                    @endif
                    <br>
                    <small class="text-muted">ID: {{ $product->id }}</small>
                  </div>
                </td>
                <td>{{ $product->producer->firstName }} {{ $product->producer->lastName }}</td>
                <td>
                  <span class="badge bg-secondary">{{ $product->category->name ?? 'N/A' }}</span>
                </td>
                <td>{{ number_format($product->price, 0, ',', ' ') }} €</td>
                <td>
                  @if($product->stock_quantity <= 5)
                    <span class="text-danger">{{ $product->stock_quantity }}</span>
                  @else
                    {{ $product->stock_quantity }}
                  @endif
                </td>
                <td>
                  @if($product->is_approved === null)
                    <span class="badge bg-warning">En attente</span>
                  @elseif($product->is_approved)
                    <span class="badge bg-success">Approuvé</span>
                  @else
                    <span class="badge bg-danger">Rejeté</span>
                  @endif
                </td>
                <td>
                  <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-primary">
                      <i class="bi bi-eye"></i>
                    </a>
                    
                    @if($product->is_approved === null)
                      <form method="POST" action="{{ route('admin.products.approve', $product) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success" title="Approuver">
                          <i class="bi bi-check"></i>
                        </button>
                      </form>
                      <form method="POST" action="{{ route('admin.products.reject', $product) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger" title="Rejeter">
                          <i class="bi bi-x"></i>
                        </button>
                      </form>
                    @endif

                    @if($product->is_featured)
                      <form method="POST" action="{{ route('admin.products.unfeature', $product) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning" title="Retirer de la vedette">
                          <i class="bi bi-star"></i>
                        </button>
                      </form>
                    @else
                      <form method="POST" action="{{ route('admin.products.feature', $product) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning" title="Mettre en vedette">
                          <i class="bi bi-star"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="8" class="text-center text-muted">Aucun produit trouvé</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
          {{ $products->withQueryString()->links() }}
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
        <p class="card-text text-muted">Total Produits</p>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title text-success">{{ number_format($stats['approved']) }}</h5>
        <p class="card-text text-muted">Approuvés</p>
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
        <h5 class="card-title text-info">{{ number_format($stats['featured']) }}</h5>
        <p class="card-text text-muted">En Vedette</p>
      </div>
    </div>
  </div>
</div>
@endsection
