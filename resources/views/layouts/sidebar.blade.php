<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="./index.html">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Gestion des Utilisateurs -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people"></i><span>Utilisateurs</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="users-nav" class="nav-content collapse">
        <li>
          <a href="{{ route('vetlink.admin.utilisateurs') }}">
            <i class="bi bi-circle"></i><span>Liste des Utilisateurs</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.verification_profile') }}">
            <i class="bi bi-circle"></i><span>Vérification des Profils</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Gestion des Produits -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#products-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-box-seam"></i><span>Produits</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="products-nav" class="nav-content collapse">
        <li>
          <a href="{{ route('vetlink.admin.liste_produits') }}">
            <i class="bi bi-circle"></i><span>Liste des Produits</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.certifications_produit') }}">
            <i class="bi bi-circle"></i><span>Certifications des Produits</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.traceability_produit') }}">
            <i class="bi bi-circle"></i><span>Traçabilité</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Gestion des Transactions -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#transactions-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-cash-coin"></i><span>Transactions</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="transactions-nav" class="nav-content collapse">
        <li>
          <a href="{{ route('vetlink.admin.listes_transactions') }}">
            <i class="bi bi-circle"></i><span>Liste des Transactions</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.transactions_litiges') }}">
            <i class="bi bi-circle"></i><span>Litiges</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Gestion des Badges -->
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{route('vetlink.admin.badge_verifier')}}">
        <i class="bi bi-award"></i><span>Badges</span>
      </a>
    </li>

    <!-- Gamification et Récompenses -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#gamification-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-trophy"></i><span>Gamification</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="gamification-nav" class="nav-content collapse">
        <li>
          <a href="{{ route('vetlink.admin.defis') }}">
            <i class="bi bi-circle"></i><span>Défis</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.rewards') }}">
            <i class="bi bi-circle"></i><span>Récompenses</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.classement') }}">
            <i class="bi bi-circle"></i><span>Classement</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Notifications et Relances -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#notifications-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-bell"></i><span>Notifications</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="notifications-nav" class="nav-content collapse">
        <li>
          <a href="{{ route('vetlink.admin.notifications') }}">
            <i class="bi bi-circle"></i><span>Liste des Notifications</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vetlink.admin.parametres') }}">
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