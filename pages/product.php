<?php
/**
 * @var mysqli $conn
 */
  session_start();

  if(!isset($_GET["product"]) || !is_numeric($_GET["product"]))
  {
    header("location: ./index.php");
  }
  require_once("../scripts/dbconnect.php");
  $sql = "SELECT * FROM products WHERE product_id=$_GET[product];";
  $result = $conn->query($sql);
  $product = $result->fetch_assoc();
  if ($result->num_rows == 0)
  {
    header("location: ./index.php");
  }

if(isset($_SESSION["loggedIn"]["user_ID"])) //Logout if user got deleted
{
  $userid = $_SESSION["loggedIn"]["user_ID"];
  require_once("../scripts/dbconnect.php");
  $sql = "SELECT id FROM users WHERE id=$userid;";
  $result = $conn->query($sql);
  $product = $result->fetch_assoc();
  if ($result->num_rows == 0)
  {
    header("location: ../scripts/logout.php");
  }
}
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
  if(!isset($_SESSION["loggedIn"]["role_ID"]))
  {
    require_once "./content_none/navbar.php";
  }
  else
  {
    switch ($_SESSION["loggedIn"]["role_ID"])
    {
      case 1:
        require_once "./content_user/navbar.php";
        break;
      case 2:
        require_once "./content_mod/navbar.php";
        break;
      case 3:
        require_once "./content_admin/navbar.php";
        break;
    }
  }

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
        <!-- Modal Popup Conditional -->
        <?php
        if (isset($_SESSION["success"]))
        {
          echo <<< SUCCESS
      <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content bg-success">
            <div class="modal-header">
              <h5 class="modal-title" id="alertModalLabel">Udało się!</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              $_SESSION[success]
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">OK!</button>
            </div>
          </div>
        </div>
      </div>
SUCCESS;
        }

        if (isset($_SESSION["error"]))
        {
          echo <<< ERROR
      <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content bg-danger">
            <div class="modal-header">
              <h5 class="modal-title" id="alertModalLabel">Coś poszło nie tak...</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              $_SESSION[error]
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">OK!</button>
            </div>
          </div>
        </div>
      </div>
ERROR;
        }
        ?>
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
                </div>
                <div class="col-12 product-image-thumbs">
                  <?php
                  echo "<div class='product-image-thumb active'><img src=$product[picture_link]></div>";
                  $sql = "SELECT * FROM pictures WHERE product_id=$_GET[product];";
                  $result = $conn->query($sql);
                  while ($pictures = $result->fetch_assoc()){
                    echo "<div class='product-image-thumb'><img src=$pictures[picture_link]></div>";
                  }
                  //echo "<div class='product-image-thumb active'><img src=$product[picture_link]></div>";
                  //echo "<div class='product-image-thumb'><img src=$picture[picture_link]</div>";
                  //echo "<div class='product-image-thumb'><img src=$picture[picture_link]></div>";
                  ?>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <?php
                  echo <<< PRODUCT_DETAILS
                  <h3 class="my-3">$product[tytul]</h3>
                  <p>$product[opis_short]</p>
                  <hr>
                  <h4 class="mt-3">Rozmiar</h4>
                  <form action="../scripts/addcart.php" method="POST">
                    <select title="size" name="size" class="form-control">
                      <option value="" disabled selected hidden>Wybierz rozmiar...</option>
PRODUCT_DETAILS;
                  $sql_wh = "SELECT DISTINCT W.product_id, S.size_id, S.size FROM warehouse W INNER JOIN sizes S ON S.size_id = W.size WHERE W.product_id=$_GET[product];";
                  $result_wh = $conn->query($sql_wh);
                  while ($wh = $result_wh->fetch_assoc())
                  {
                    echo "<option value='$wh[size_id]'>$wh[size]</option>";
                  }
                  echo <<< PRODUCT_DETAILS
                    </select>
                      <div class="bg-gray py-2 px-3 mt-4">
                        <h2 class="mb-0">
                          $product[cena] zł
                        </h2>
                    </div>

                    <input type="hidden" name="product_id" value="$_GET[product]">

                    <div class="mt-4">
                      <button type="submit" class="btn btn-primary btn-lg btn-flat">
                        <i class="fas fa-cart-plus fa-lg mr-2"></i>
                        Dodaj do koszyka
                      </button>
                  </form>
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
  //Replace images
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
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
  //Enable tooltips
  $(function () {
    $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" })
  })
</script>
<?php
  //Modal Popup Script
  if (isset($_SESSION["success"]) || isset($_SESSION["error"]))
  {
    echo <<< MODALJS
    <script>
      $(window).on('load', function() {
        $('#alertModal').modal('show')
    })
    </script>
MODALJS;
  }
  unset($_SESSION["success"]);
  unset($_SESSION["error"]);
?>
</body>
</html>
