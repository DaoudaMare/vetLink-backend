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
          <a href="./users-list.html">
            <i class="bi bi-circle"></i><span>Liste des Utilisateurs</span>
          </a>
        </li>
        <li>
          <a href="./users-verification.html">
            <i class="bi bi-circle"></i><span>Vérification des Profils</span>
          </a>
        </li>
        <li>
          <a href="./users-badges.html">
            <i class="bi bi-circle"></i><span>Badges Attribués</span>
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
          <a href="./products-list.html">
            <i class="bi bi-circle"></i><span>Liste des Produits</span>
          </a>
        </li>
        <li>
          <a href="./products-certifications.html">
            <i class="bi bi-circle"></i><span>Certifications des Produits</span>
          </a>
        </li>
        <li>
          <a href="./products-traceability.html">
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
          <a href="./transactions-list.html">
            <i class="bi bi-circle"></i><span>Liste des Transactions</span>
          </a>
        </li>
        <li>
          <a href="./transactions-disputes.html">
            <i class="bi bi-circle"></i><span>Litiges</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Gestion des Badges -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#badges-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-award"></i><span>Badges</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="badges-nav" class="nav-content collapse">
        <li>
          <a href="./badges-list.html">
            <i class="bi bi-circle"></i><span>Liste des Badges</span>
          </a>
        </li>
        <li>
          <a href="./badges-criteria.html">
            <i class="bi bi-circle"></i><span>Critères d'Attribution</span>
          </a>
        </li>
        <li>
          <a href="./badges-assign.html">
            <i class="bi bi-circle"></i><span>Attribuer des Badges</span>
          </a>
        </li>
      </ul>
    </li>

    <!-- Gamification et Récompenses -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-target="#gamification-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-trophy"></i><span>Gamification</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="gamification-nav" class="nav-content collapse">
        <li>
          <a href="./gamification-challenges.html">
            <i class="bi bi-circle"></i><span>Défis</span>
          </a>
        </li>
        <li>
          <a href="./gamification-rewards.html">
            <i class="bi bi-circle"></i><span>Récompenses</span>
          </a>
        </li>
        <li>
          <a href="./gamification-leaderboard.html">
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
          <a href="./notifications-list.html">
            <i class="bi bi-circle"></i><span>Liste des Notifications</span>
          </a>
        </li>
        <li>
          <a href="./notifications-settings.html">
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