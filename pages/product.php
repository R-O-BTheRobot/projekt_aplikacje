<?php
/**
 * @var string $conn
 */

  if(!isset($_GET["product"]))
    {
      echo "<script>history.back()</script>";
    }
  require_once("../scripts/dbconnect.php");
  $sql = "SELECT * FROM products WHERE product_id=$_GET[product];";
  $result = $conn->query($sql);
  $product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
    echo "<title>$product[tytul] - Sklep XYZ</title>";
  ?>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-collapse">
<div class="wrapper">
  <?php
    require_once "./content_user/navbar.php";
  ?>
  <?php
    require_once "./aside.php";
  ?>
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="card card-solid">
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-sm-6">
                <div class="col-12">
                  <?php
                  $sql = "SELECT * FROM products WHERE product_id=$_GET[product];";
                  $result = $conn->query($sql);
                  $product = $result->fetch_assoc();
                  echo "<img class='product-image' src=$product[picture_link]>";
                  ?>
                  <!--<img src="../../dist/img/prod-1.jpg" class="product-image" alt="Product Image">-->
                </div>
                <div class="col-12 product-image-thumbs">
                  <?php
                  echo "<div class='product-image-thumb active'><img src=$product[picture_link]></div>";
                  echo "<div class='product-image-thumb'><img src=https://via.placeholder.com/300/000000?text=2></div>";
                  echo "<div class='product-image-thumb'><img src=https://via.placeholder.com/300/000000?text=3></div>";
                  ?>
                  <!--<div class="product-image-thumb active"><img src="../../dist/img/prod-1.jpg" alt="Product Image"></div>
                  <div class="product-image-thumb" ><img src="../../dist/img/prod-2.jpg" alt="Product Image"></div>
                  <div class="product-image-thumb" ><img src="../../dist/img/prod-3.jpg" alt="Product Image"></div>
                  <div class="product-image-thumb" ><img src="../../dist/img/prod-4.jpg" alt="Product Image"></div>
                  <div class="product-image-thumb" ><img src="../../dist/img/prod-5.jpg" alt="Product Image"></div>-->
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <?php
                  echo <<< PRODUCT_DETAILS
                  <h3 class="my-3">$product[tytul]</h3>
                  <p>$product[opis_short]</p>
                  <hr>
                  <h4 class="mt-3">Rozmiar</h4>
                  <select class="form-control">
PRODUCT_DETAILS;
                $sql_wh = "SELECT S.size FROM warehouse W INNER JOIN sizes S ON S.size_id = W.size WHERE W.product_id=$_GET[product];";
                $result_wh = $conn->query($sql_wh);
                while ($wh = $result_wh->fetch_assoc())
                {
                  echo "<option>$wh[size]</option>";
                }
                echo <<< PRODUCT_DETAILS
                  </select>
                  <div class="bg-gray py-2 px-3 mt-4">
                    <h2 class="mb-0">
                      $product[cena] zł
                    </h2>
                  </div>

                  <div class="mt-4">
                    <div class="btn btn-primary btn-lg btn-flat">
                      <i class="fas fa-cart-plus fa-lg mr-2"></i>
                      Dodaj do koszyka
                  </div>
PRODUCT_DETAILS;

                ?>
              </div>

            </div>
          </div>
          <div class="row mt-4">
            <h3 class="w-100">Opis produktu</h3>
            <div class="tab-content p-3" id="nav-tabContent">
              <?php
                echo "<div class='tab-pane fade show active' id='product-desc' role='tabpanel' aria-labelledby='product-desc-tab'>$product[opis_long]</div>";
              ?>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    $('.product-image-thumb').on('click', function () {
      var $image_element = $(this).find('img')
      $('.product-image').prop('src', $image_element.attr('src'))
      $('.product-image-thumb.active').removeClass('active')
      $(this).addClass('active')
    })
  })
</script>
<script src="../dist/js/adminlte.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
