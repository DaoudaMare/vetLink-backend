<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('vetlink.admin.dashboard') ? 'active' : 'collapsed' }} " href="{{route('vetlink.admin.dashboard')}}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Gestion des Utilisateurs -->
    <li class="nav-item">
      <a  @class(['nav-link ', 'collapsed' => !request()->routeIs('vetlink.admin.users.*')])  data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people"></i><span>Utilisateurs</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="users-nav" @class(['nav-content', 'collapse', 'show' => request()->routeIs('vetlink.admin.users.*')])>
        <li>
          <a href="{{ route('vetlink.admin.users.utilisateurs') }}" class="{{request()->routeIs('vetlink.admin.users.utilisateurs') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Liste des Utilisateurs</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.users.verification_profile') }}" class="{{request()->routeIs('vetlink.admin.users.verification_profile') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Vérification des Profils</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.users.new_moderateur') }}" class="{{request()->routeIs('vetlink.admin.users.new_moderateur') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Moderateur & Administitrateur</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Gestion des Produits -->
    <li class="nav-item">
      <a @class(['nav-link ', 'collapsed' => !request()->routeIs('vetlink.admin.produits.*')]) data-bs-target="#products-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-box-seam"></i><span>Produits</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="products-nav" @class(['nav-content', 'collapse', 'show' => request()->routeIs('vetlink.admin.produits.*')])>
        <li>
          <a href="{{ route('vetlink.admin.produits.liste_produits') }}" class="{{request()->routeIs('vetlink.admin.produits.liste_produits') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Liste des Produits</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.produits.certifications_produit') }}" class="{{request()->routeIs('vetlink.admin.produits.certifications_produit') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Certifications des Produits</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.produits.traceability_produit') }}" class="{{request()->routeIs('vetlink.admin.produits.traceability_produit') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Traçabilité</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Gestion des Transactions -->
    <li class="nav-item">
      <a @class(['nav-link ', 'collapsed' => !request()->routeIs('vetlink.admin.transactions.*')]) data-bs-target="#transactions-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-cash-coin"></i><span>Transactions</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="transactions-nav" @class(['nav-content', 'collapse', 'show' => request()->routeIs('vetlink.admin.transactions.*')])>
        <li>
          <a href="{{ route('vetlink.admin.transactions.listes_transactions') }}" class="{{request()->routeIs('vetlink.admin.transactions.listes_transactions') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Liste des Transactions</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.transactions.transactions_litiges') }}" class="{{request()->routeIs('vetlink.admin.transactions.transactions_litiges') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Litiges</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Gestion des Badges -->
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('vetlink.admin.badge_verifier') ? 'active' : 'collapsed' }} " href="{{route('vetlink.admin.badge_verifier')}}">
        <i class="bi bi-award"></i><span>Badges</span>
      </a>
    </li>

    <!-- Gamification et Récompenses -->
    <li class="nav-item">
      <a @class(['nav-link ', 'collapsed' => !request()->routeIs('vetlink.admin.gamification.*')]) data-bs-target="#gamification-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-trophy"></i><span>Gamification</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="gamification-nav"  @class(['nav-content', 'collapse', 'show' => request()->routeIs('vetlink.admin.gamification.*')])>
        <li>
          <a href="{{ route('vetlink.admin.gamification.defis') }}" class="{{request()->routeIs('vetlink.admin.gamification.defis') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Défis</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.gamification.rewards') }}" class="{{request()->routeIs('vetlink.admin.gamification.rewards') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Récompenses</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.gamification.classement') }}" class="{{request()->routeIs('vetlink.admin.gamification.classement') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Classement</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Notifications et Relances -->
    <li class="nav-item">
      <a @class(['nav-link ', 'collapsed' => !request()->routeIs('vetlink.admin.notifications.*')]) data-bs-target="#notifications-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-bell"></i><span>Notifications</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="notifications-nav"  @class(['nav-content', 'collapse', 'show' => request()->routeIs('vetlink.admin.notifications.*')])>
        <li>
          <a href="{{ route('vetlink.admin.notifications.notifications') }}" class="{{request()->routeIs('vetlink.admin.notifications.notifications') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Liste des Notifications</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.notifications.parametres') }}" class="{{request()->routeIs('vetlink.admin.notifications.parametres') ? 'active' : ''}}">
            <i class="bi bi-circle"></i><span>Paramètres</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Déconnexion -->
    <li class="nav-heading">
      <hr>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" onclick="return confirm('Êtes-vous sûr ?')">
        <i class="bi bi-box-arrow-right"></i>
        <span>Déconnexion</span>
      </a>
    </li>
  </ul>
</aside>