<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : 'collapsed' }} " href="{{route('admin.dashboard')}}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Gestion des Utilisateurs -->
    <li class="nav-item">
      <a  @class(['nav-link ', 'collapsed' => !request()->routeIs('admin.users.*')])  data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people"></i><span>Utilisateurs</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="users-nav" @class(['nav-content', 'collapse', 'show' => request()->routeIs('admin.users.*')])>
        <li>
          <a href="{{ route('admin.users.index') }}" class="{{request()->routeIs('admin.users.index') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Liste des Utilisateurs</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.users.create') }}" class="{{request()->routeIs('admin.users.create') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Nouvel Utilisateur</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Gestion des Produits -->
    <li class="nav-item">
      <a @class(['nav-link ', 'collapsed' => !request()->routeIs('admin.products.*')]) data-bs-target="#products-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-box-seam"></i><span>Produits</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="products-nav" @class(['nav-content', 'collapse', 'show' => request()->routeIs('admin.products.*')])>
        <li>
          <a href="{{ route('admin.products.index') }}" class="{{request()->routeIs('admin.products.index') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Liste des Produits</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.products.pending') }}" class="{{request()->routeIs('admin.products.pending') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Produits en Attente</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Gestion des Commandes -->
    <li class="nav-item">
      <a @class(['nav-link ', 'collapsed' => !request()->routeIs('admin.orders.*')]) data-bs-target="#orders-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-cart"></i><span>Commandes</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="orders-nav" @class(['nav-content', 'collapse', 'show' => request()->routeIs('admin.orders.*')])>
        <li>
          <a href="{{ route('admin.orders.index') }}" class="{{request()->routeIs('admin.orders.index') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Liste des Commandes</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.orders.pending') }}" class="{{request()->routeIs('admin.orders.pending') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Commandes en Attente</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Modération Chat -->
    <li class="nav-item">
      <a @class(['nav-link ', 'collapsed' => !request()->routeIs('admin.chat.*')]) data-bs-target="#chat-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-chat-dots"></i><span>Chat</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="chat-nav" @class(['nav-content', 'collapse', 'show' => request()->routeIs('admin.chat.*')])>
        <li>
          <a href="{{ route('admin.chat.index') }}" class="{{request()->routeIs('admin.chat.index') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Conversations</span>
          </a>
        </li>
        <li>
          <a href="{{ route('admin.chat.flagged') }}" class="{{request()->routeIs('admin.chat.flagged') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Messages Signalés</span>
          </a>
        </li>
      </ul>
    </li>

  </ul>
</aside>