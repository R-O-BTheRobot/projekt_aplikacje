<?php
/**
 * @var mysqli $conn
 */
  session_start();
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
  <!-- Toastr -->
  <link rel="stylesheet" href="../plugins/toastr/toastr.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition">
<div class="wrapper">

  <?php
    if(!isset($_SESSION["loggedIn"]["role_id"]))
    {
      require_once "./content_none/navbar.php";
    }
    else
    {
      switch ($_SESSION["loggedIn"]["role_id"])
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
  <div class="content-wrapper">
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
                  if(isset($_GET["sort"]) && ($_GET["sort"] == "biegowe" || $_GET["sort"] == "sportowe" || $_GET["sort"] == "turystyczne" || $_GET["sort"] == "codzienne"))
                  {
                    $sql = "SELECT * FROM `products` WHERE `typ` = '$_GET[sort]'";
                  }
                  $result = $conn->query($sql);
                  while ($product = $result->fetch_assoc())
                  {
                    echo <<< GET_PRODUCTS_FROM_DB
                    <div class="col-sm-2">
                        <a href="./product.php?product=$product[product_id]" data-toggle="lightbox" data-title="$product[typ]" data-gallery="gallery">
                                <img src="$product[picture_link]" class="img-fluid mb-2" alt="Zdjęcie produktu $product[product_id]">
                        </a>
                    </div>
GET_PRODUCTS_FROM_DB;
                  }
                  ?>
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#alertModal">
                    Launch demo modal
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../dist/js/adminlte.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../plugins/toastr/toastr.min.js"></script>
<script>
  //Enable tooltips
  $(function () {
    $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" })
  })
</script>
<?php
  //Logout Toast Script
  if (isset($_GET["logout"]))
  {
    echo <<< LOGOUT
    <script>toastr.success('Pomyślnie wylogowano!')</script>
LOGOUT;
  unset($_GET["logout"]);
  }
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


