@extends('layouts.app')

@section('pagetitle')
<h1>Tableau de Bord</h1>
<nav>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="./index.html">Tableau de Bord</a></li>
    <li class="breadcrumb-item active">Tableau de Bord</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="row">

  <!-- Colonne de Gauche -->
  <div class="col-lg-8">
    <div class="row">

      <!-- Carte : Utilisateurs Vérifiés -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card sales-card">
          <div class="card-body">
            <h5 class="card-title">Utilisateurs Vérifiés <span>| Ce Mois</span></h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-person-check"></i>
              </div>
              <div class="ps-3">
                <h6>245</h6>
                <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">augmentation</span>
              </div>
            </div>
          </div>
        </div>
      </div><!-- Fin Carte Utilisateurs Vérifiés -->

      <!-- Carte : Transactions Réussies -->
      <div class="col-xxl-4 col-md-6">
        <div class="card info-card revenue-card">
          <div class="card-body">
            <h5 class="card-title">Transactions Réussies <span>| Ce Mois</span></h5>
            <div class="d-flex align-items-center">
              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                <i class="bi bi-currency-dollar"></i>
              </div>
              <div class="ps-3">
                <h6>$12,450</h6>
                <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">augmentation</span>
              </div>
            </div>
          </div>
        </div>
      </div><!-- Fin Carte Transactions Réussies -->

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

      <!-- Rapports -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Rapports <span>| Cette Année</span></h5>
            <!-- Graphique en Ligne -->
            <div id="reportsChart"></div>
            <script>
              document.addEventListener("DOMContentLoaded", () => {
                new ApexCharts(document.querySelector("#reportsChart"), {
                  series: [{
                    name: 'Utilisateurs Vérifiés',
                    data: [31, 40, 28, 51, 42, 82, 56],
                  }, {
                    name: 'Transactions Réussies',
                    data: [11, 32, 45, 32, 34, 52, 41]
                  }, {
                    name: 'Produits Certifiés',
                    data: [15, 11, 32, 18, 9, 24, 11]
                  }],
                  chart: {
                    height: 350,
                    type: 'area',
                    toolbar: {
                      show: false
                    },
                  },
                  markers: {
                    size: 4
                  },
                  colors: ['#4154f1', '#2eca6a', '#ff771d'],
                  fill: {
                    type: "gradient",
                    gradient: {
                      shadeIntensity: 1,
                      opacityFrom: 0.3,
                      opacityTo: 0.4,
                      stops: [0, 90, 100]
                    }
                  },
                  dataLabels: {
                    enabled: false
                  },
                  stroke: {
                    curve: 'smooth',
                    width: 2
                  },
                  xaxis: {
                    type: 'datetime',
                    categories: ["2023-01-01", "2023-02-01", "2023-03-01", "2023-04-01", "2023-05-01", "2023-06-01", "2023-07-01"]
                  },
                  tooltip: {
                    x: {
                      format: 'dd/MM/yy'
                    },
                  }
                }).render();
              });
            </script>
            <!-- Fin Graphique en Ligne -->
          </div>
        </div>
      </div><!-- Fin Rapports -->

      <!-- Transactions Récentes -->
      <div class="col-12">
        <div class="card recent-sales overflow-auto">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li class="dropdown-header text-start">
                <h6>Filtrer</h6>
              </li>
              <li><a class="dropdown-item" href="#">Aujourd'hui</a></li>
              <li><a class="dropdown-item" href="#">Ce Mois</a></li>
              <li><a class="dropdown-item" href="#">Cette Année</a></li>
            </ul>
          </div>
          <div class="card-body">
            <h5 class="card-title">Transactions Récentes <span>| Aujourd'hui</span></h5>
            <table class="table table-borderless datatable">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Acheteur</th>
                  <th scope="col">Produit</th>
                  <th scope="col">Montant</th>
                  <th scope="col">Statut</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row"><a href="#">#2457</a></th>
                  <td>Brandon Jacob</td>
                  <td><a href="#" class="text-primary">Produit Agricole A</a></td>
                  <td>$64</td>
                  <td><span class="badge bg-success">Approuvé</span></td>
                </tr>
                <tr>
                  <th scope="row"><a href="#">#2147</a></th>
                  <td>Bridie Kessler</td>
                  <td><a href="#" class="text-primary">Produit Agricole B</a></td>
                  <td>$47</td>
                  <td><span class="badge bg-warning">En Attente</span></td>
                </tr>
                <tr>
                  <th scope="row"><a href="#">#2049</a></th>
                  <td>Ashleigh Langosh</td>
                  <td><a href="#" class="text-primary">Produit Agricole C</a></td>
                  <td>$147</td>
                  <td><span class="badge bg-success">Approuvé</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- Fin Transactions Récentes -->

      <!-- Produits les Plus Vendus -->
      <div class="col-12">
        <div class="card top-selling overflow-auto">
          <div class="filter">
            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <li class="dropdown-header text-start">
                <h6>Filtrer</h6>
              </li>
              <li><a class="dropdown-item" href="#">Aujourd'hui</a></li>
              <li><a class="dropdown-item" href="#">Ce Mois</a></li>
              <li><a class="dropdown-item" href="#">Cette Année</a></li>
            </ul>
          </div>
          <div class="card-body pb-0">
            <h5 class="card-title">Produits les Plus Vendus <span>| Aujourd'hui</span></h5>
            <table class="table table-borderless">
              <thead>
                <tr>
                  <th scope="col">Image</th>
                  <th scope="col">Produit</th>
                  <th scope="col">Prix</th>
                  <th scope="col">Vendus</th>
                  <th scope="col">Revenu</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row"><a href="#"><img src="{{asset('assets/img/product-1.jpg')}}" alt="Produit 1"></a></th>
                  <td><a href="#" class="text-primary fw-bold">Produit Agricole A</a></td>
                  <td>$64</td>
                  <td class="fw-bold">124</td>
                  <td>$5,828</td>
                </tr>
                <tr>
                  <th scope="row"><a href="#"><img src="{{asset('assets/img/product-2.jpg')}}" alt="Produit 2"></a></th>
                  <td><a href="#" class="text-primary fw-bold">Produit Agricole B</a></td>
                  <td>$46</td>
                  <td class="fw-bold">98</td>
                  <td>$4,508</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- Fin Produits les Plus Vendus -->

    </div>
  </div><!-- Fin Colonne de Gauche -->

  <!-- Colonne de Droite -->
  <div class="col-lg-4">

    <!-- Activité Récente -->
    <div class="card">
      <div class="filter">
        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <li class="dropdown-header text-start">
            <h6>Filtrer</h6>
          </li>
          <li><a class="dropdown-item" href="#">Aujourd'hui</a></li>
          <li><a class="dropdown-item" href="#">Ce Mois</a></li>
          <li><a class="dropdown-item" href="#">Cette Année</a></li>
        </ul>
      </div>
      <div class="card-body">
        <h5 class="card-title">Activité Récente <span>| Aujourd'hui</span></h5>
        <div class="activity">
          <div class="activity-item d-flex">
            <div class="activite-label">32 min</div>
            <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
            <div class="activity-content">
              Nouveau profil vérifié : <a href="#" class="fw-bold text-dark">John Doe</a>
            </div>
          </div><!-- Fin Activité Item -->
          <div class="activity-item d-flex">
            <div class="activite-label">1 heure</div>
            <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
            <div class="activity-content">
              Litige signalé sur la transaction #2457
            </div>
          </div><!-- Fin Activité Item -->
        </div>
      </div>
    </div><!-- Fin Activité Récente -->

    <!-- Rapport de Traçabilité -->
    <div class="card">
      <div class="filter">
        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <li class="dropdown-header text-start">
            <h6>Filtrer</h6>
          </li>
          <li><a class="dropdown-item" href="#">Aujourd'hui</a></li>
          <li><a class="dropdown-item" href="#">Ce Mois</a></li>
          <li><a class="dropdown-item" href="#">Cette Année</a></li>
        </ul>
      </div>
      <div class="card-body pb-0">
        <h5 class="card-title">Rapport de Traçabilité <span>| Ce Mois</span></h5>
        <div id="traceabilityChart" style="min-height: 400px;" class="echart"></div>
        <script>
          document.addEventListener("DOMContentLoaded", () => {
            echarts.init(document.querySelector("#traceabilityChart")).setOption({
              tooltip: {
                trigger: 'item'
              },
              legend: {
                top: '5%',
                left: 'center'
              },
              series: [{
                name: 'Traçabilité',
                type: 'pie',
                radius: ['40%', '70%'],
                avoidLabelOverlap: false,
                label: {
                  show: false,
                  position: 'center'
                },
                emphasis: {
                  label: {
                    show: true,
                    fontSize: '18',
                    fontWeight: 'bold'
                  }
                },
                labelLine: {
                  show: false
                },
                data: [{
                  value: 1048,
                  name: 'Produits Certifiés'
                },
                {
                  value: 735,
                  name: 'Produits en Attente'
                },
                {
                  value: 580,
                  name: 'Produits Non Certifiés'
                }
                ]
              }]
            });
          });
        </script>
      </div>
    </div><!-- Fin Rapport de Traçabilité -->

  </div><!-- Fin Colonne de Droite -->

</div>
@endsection