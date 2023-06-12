<?php
/**
 * @var mysqli $conn
 */
  session_start();
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
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sklep XYZ | Strona główna</title>
  <link rel="icon" type="image/x-icon" href="../dist/img/favicon.png">
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
    if(!isset($_SESSION["loggedIn"]["role_ID"]))
    {
      require_once "./content_none/navbar.php";
      //print_r($_SESSION["loggedIn"]);
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
              <button type="button" class="btn btn-primary" id="primary" data-dismiss="modal">OK!</button>
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
                  $sql = "SELECT P.product_id, P.picture_link, T.type FROM `products` P INNER JOIN `type` T ON P.type_id = T.type_id";
                  if(isset($_GET["sort"]) && ($_GET["sort"] == "biegowe" || $_GET["sort"] == "sportowe" || $_GET["sort"] == "turystyczne" || $_GET["sort"] == "codzienne"))
                  {
                    $sql = "SELECT P.product_id, P.picture_link, T.type FROM `products` P INNER JOIN `type` T ON P.type_id = T.type_id WHERE T.`type`='$_GET[sort]'";
                  }
                  $result = $conn->query($sql);
                  while ($product = $result->fetch_assoc())
                  {
                    echo <<< GET_PRODUCTS_FROM_DB
                    <div class="col-sm-2">
                        <a href="./product.php?product=$product[product_id]" data-toggle="lightbox" data-title="$product[type]" data-gallery="gallery">
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
  <?php
  require_once "./footer.php";
  ?>
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
  if (isset($_GET["activated"]))
  {
    echo <<< ACTIVATED
    <script>toastr.success('Twoje konto zostało aktywowane!')</script>
ACTIVATED;
  unset($_GET["activate"]);
  }
  //Modal Popup Script
  if (isset($_SESSION["success"]) || isset($_SESSION["error"]))
  {
    echo <<< MODALJS
<script>
  $(window).on('load', function() {
    $('#alertModal').modal('show');
  })
</script>
MODALJS;
  }
  unset($_SESSION["success"]);
  unset($_SESSION["error"]);
?>
</body>
</html>


