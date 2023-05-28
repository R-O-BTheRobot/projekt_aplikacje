<?php
/**
 * @var string $conn
 */
?>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sklep XYZ</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ekko Lightbox -->
  <link rel="stylesheet" href="../plugins/ekko-lightbox/ekko-lightbox.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body>
<div class="d-flex justify-content-center">
  <h1>Sklep z obuwiem</h1>
</div>
<div class="wrapper">
  <aside class="main-sidebar sidebar-dark-primary elevation-4 d-flex align-items-stretch">
    <nav class="d-flex align-items-center">
      <ul class="nav nav-pills nav-sidebar flex-column vertical" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
          <a href="#" rel="nofollow" class="nav-link">
            <p>
              Wszystkie produkty
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <p>
              Buty turystyczne
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <p>
              Buty do biegania
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <p>
              Buty do ćwiczeń sportowych
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <p>
              Buty do użytku codziennego
            </p>
          </a>
        </li>
      </ul>
    </nav>
  </aside>
  <div class="content-wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-primary">
              <!-- <div class="card-header">
                 <h4 class="card-title">Witamy na stronie sklepu internetowego XYZ!</h4>
               </div> -->
              <div class="card-body">
                <div class="row">
                  <?php
                  require_once "../scripts/dbconnect.php";

                  $sql = "SELECT * FROM `products`";
                  $result = $conn->query($sql);
                  while ($product = $result->fetch_assoc())
                  {
                    echo <<< GET_PRODUCTS_FROM_DB
                    <div class="col-sm-2">
                        <a href="./product.php?product=$product[product_id]" data-toggle="lightbox" data-title="$product[product_id]" data-gallery="gallery">
                                <img src="$product[picture_link]" class="img-fluid mb-2" alt="Zdjęcie produktu $product[product_id]">
                        </a>
                    </div>
GET_PRODUCTS_FROM_DB;
                  }
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
</body>
</html>


