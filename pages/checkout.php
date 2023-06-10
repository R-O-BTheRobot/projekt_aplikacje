<?php
/**
 * @var mysqli $conn
 */
session_start();
$price = 0;
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
<body class="hold-transition">

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
            <!-- Main content -->
            <div class="invoice p-3 mb-3">
              <!-- title row -->
              <?php
                if(!isset($_SESSION["cart"]))
                {
                  echo "<div class=row><h3>Twój koszyk jest pusty!</h3></div>";
                }
                else
                {
                  echo <<< TAB_STRT
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
TAB_STRT;
                  //print_r($_SESSION["cart"]);
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
                      $price = $price + ($product["cena"]*$num);
                      echo <<< CART_DATA
                            <tr>
                              <td>$product[tytul]</td>
                              <td>$product[size]</td>
                              <td>$product[opis_short]</td>
                              <td>$product[cena] zł</td>
                              <td class="d-flex align-content-center">
                                <a href="../scripts/delcart.php?product_id=$product_id&size=$size">
                                  <button class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-minus"></i>
                                  </button>
                                </a>
                                <input class="form-control-sm col-2 text-center" type="text" disabled value="$num">
                                <a href="../scripts/addcart.php?product_id=$product_id&size=$size">
                                  <button class="btn btn-outline-secondary btn-sm">
                                    <i class="fa fa-plus"></i>
                                  </button>
                                </a>
                                <div class="col-1"></div>
                                <a href="../scripts/delcart.php?product_id=$product_id&size=$size">
                                  <button class="btn btn-outline-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                  </button>
                                </a>
                              </td>
                            </tr>
CART_DATA;
                      //echo "$product[tytul] - selected: $num, warehouse: $result->num_rows";
                    }
                  }
                  echo <<< TAB_FIN
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
TAB_FIN;
                }
              ?>
              <!-- /.row -->

              <div class="row">
                <!-- accepted payments column -->
                <div class="col-6">
                  <p class="lead">Dostępne metody płatności:</p>
                  <img src="../dist/img/credit/visa.png" alt="Visa">
                  <img src="../dist/img/credit/mastercard.png" alt="Mastercard">
                  <img src="../dist/img/credit/paypal2.png" alt="Paypal">
                </div>
                <!-- /.col -->
                <div class="col-6">
                  <?php
                  $vat = $price-($price/1.23);
                  $delivery = 15.00;
                  if($price>=350.00)
                  {
                    $delivery = 0.00;
                  }
                  $delprice = $price + $delivery;
                  $price = number_format((float)$price, 2, '.', '');
                  $vat = number_format((float)$vat, 2, '.', '');
                  $delivery = number_format((float)$delivery, 2, '.', '');
                  $delprice = number_format((float)$delprice, 2, '.', '');
                  if(isset($_SESSION["cart"]))
                    echo <<< SUBTOTAL
                  <p class="lead">Podsumowanie:</p>
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <th style="width:50%">Wartość produktów:</th>
                        <td>$price zł</td>
                      </tr>
                      <tr>
                        <th>Podatek VAT:</th>
                        <td>$vat zł</td>
                      </tr>
                      <tr>
                        <th>Koszt dostawy:</th>
                        <td>$delivery zł</td>
                      </tr>
                      <tr>
                        <th>Razem:</th>
                        <td>$delprice zł</td>
                      </tr>
                    </table>
                  </div>
SUBTOTAL;

                  ?>
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

              <div class="row">
                <div class="col-12">
                  <?php
                  if(!isset($_SESSION["cart"]))
                    echo <<< PAY_BUTTON
                    <button type="button" disabled class="btn btn-success float-right"><i class="far fa-credit-card"></i>
                      Prejdź do płatności
                    </button>
PAY_BUTTON;
                  elseif(!isset($_SESSION["loggedIn"]))
                    echo <<< PAY_BUTTON
                    <button type="button" disabled class="btn btn-success float-right"><i class="far fa-credit-card"></i>
                      Prejdź do płatności
                    </button>
PAY_BUTTON;
                  else
                  {
                    echo <<< PAY_BUTTON
                    <a href="../scripts/pay.php">
                      <button type="button" disabled class="btn btn-success float-right"><i class="far fa-credit-card"></i>
                        Prejdź do płatności
                      </button>
                    </a>
PAY_BUTTON;

                  }
                  //if `activated` == 0
                  //else button with a redirect link to a page which will delete
                  //all selected items from the warehouse db
                  ?>
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
