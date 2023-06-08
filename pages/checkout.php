<?php
/**
 * @var mysqli $conn
 */
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Invoice</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">

<div class="wrapper">
  <!-- Navbar -->
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
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php
  require_once "./aside.php";
  ?>

  <!-- Content Wrapper. Contains page content -->
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
            <div class="callout callout-danger">
              <h5><i class="fas fa-exclamation-triangle"></i> Uwaga!</h5>
              Twoje zamówienie wykracza poza stany magazynowe. Nie będziemy mogli dostarczyć ci następujących produktów:
              [Product] Rozmiar: [Size].
              Ilość w koszyku: [CartAmount]
              Stan magazynowy: [DBAmount]
            </div>

            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <div class="row">
                <h3>Twoje zamówienie:</h3>
              </div>
              <!-- Table row -->
              <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table table-striped">
                    <thead>
                    <tr>
                      <th>Produkt</th>
                      <th>Rozmiar</th>
                      <th>Krótki opis</th>
                      <th>Cena</th>
                      <th>Ilość</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                      print_r($_SESSION["cart"]);
                      require_once "../scripts/dbconnect.php";

                      foreach($_SESSION["cart"] as $product_id => $val)
                      {
                        foreach(array_count_values($_SESSION["cart"][$product_id]) as $size => $num)
                        {
                          $stmt = $conn->prepare("SELECT P.tytul, P.opis_short, P.cena, S.size FROM `warehouse` W INNER JOIN products P ON W.product_id = P.product_id INNER JOIN sizes S ON W.size = S.size_id WHERE W.product_id = ? AND W.size = ?");
                          $stmt->bind_param("ii", $product_id, $size);
                          $stmt->execute();
                          $result = $stmt->get_result();
                          $product = $result->fetch_assoc();
                          echo <<< CART_DATA
                            <tr>
                              <td>$product[tytul]</td>
                              <td>$product[size]</td>
                              <td>$product[opis_short]</td>
                              <td>$product[cena]</td>
                              <td class="d-flex align-content-center">
                                <button class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <input class="form-control-sm col-2 text-center" type="text" disabled value="$num">
                                <button class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-plus"></i>
                                </button>
                                <div class="col-1"></div>
                                <button class="btn btn-outline-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </button>
                              </td>
                            </tr>
CART_DATA;
                            echo "$product[tytul] - selected: $num, warehouse: $result->num_rows";
                        }
                      }
                      //Quantity I imagine as [-][quantity (noneditable)][+] [trash can]
                      //- will go to delcart.php and remove a single item, + will go to addcart and add
                    ?>
                    <!--<tr>
                      <td>1</td>
                      <td>Call of Duty</td>
                      <td>455-981-221</td>
                      <td>El snort testosterone trophy driving gloves handsome</td>
                      <td>$64.50</td>
                    </tr>-->
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Metody Płatności:</p>
                  <img src="../dist/img/credit/visa.png" alt="Visa">
                  <img src="../dist/img/credit/mastercard.png" alt="Mastercard">
                  <img src="../dist/img/credit/paypal2.png" alt="Paypal">
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <p class="lead">Podsumowanie:</p>

                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Wartość produktów:</th>
                        <td>[sum]</td>
                      </tr>
                      <tr>
                        <th>Podatek VAT:</th>
                        <td>[some function to calc 23% VAT idk]</td>
                      </tr>
                      <tr>
                        <th>Koszt dostawy:</th>
                        <td>[some fixed cost/0 based on order amount]</td>
                      </tr>
                      <tr>
                        <th>Razem:</th>
                        <td>[total]</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <div class="col-12">
                  <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i>
                    Prejdź do płatności
                  </button>
                </div>
              </div>
            </div>
            <!-- /.invoice -->
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer no-print">
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

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
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
