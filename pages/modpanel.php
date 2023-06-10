<?php
session_start();
/** @var mysqli $conn*/

if(!isset($_SESSION["loggedIn"]["role_ID"]))
{
  header("location: ./index.php");
}
else
{
  switch ($_SESSION["loggedIn"]["role_ID"])
  {
    case 1:
      header("location: ./index.php");
      break;
    case 2:
      $navbar="./content_mod/navbar.php";
      break;
    case 3:
      $navbar="./content_admin/navbar.php";
      break;
  }
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
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | DataTables</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-collapse">
<div class="wrapper">

  <?php
  require_once $navbar;
  ?>

  <?php
  require_once "./aside.php";
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <!--Modal Popup Warning-->
      <div class="modal fade" id="warningModal" tabindex="-1" role="dialog" aria-labelledby="warningModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content bg-warning">
            <div class="modal-header">
              <h5 class="modal-title" id="warningModalLabel">Uważaj!</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Uważaj! Zamierzasz usunąć przedmiot <b><div class="d-inline" id="productname"></div></b>.
              Kontynuować?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Anuluj</button>
              <a id="redirect" href="#"><button type="button" class="btn btn-secondary">Usuń</button></a>
            </div>
          </div>
        </div>
      </div>

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
            <div class="card">
              <div class="card-header d-flex">
                <h3 class="card-title">Panel zarządzania produktami</h3>
                <a href="./addproduct.php" class="ml-auto">
                  <button class="btn btn-success"><i class="fa fa-xs fa-plus"></i> Dodaj nowy produkt</button>
                </a>
              </div>
              <div class="d-flex">

              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="usertab" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Nazwa</th>
                    <th class="noExport">Główne zdjęcie</th>
                    <th>Krótki opis</th>
                    <th>Długi opis</th>
                    <th>Typ</th>
                    <th>Cena</th>
                    <th class="noExport">Akcja</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  require_once "../scripts/dbconnect.php";
                  $sql = "SELECT P.tytul, P.product_id, P.picture_link, P.opis_short, P.opis_long, P.cena, T.type FROM `products` P INNER JOIN `type` T ON P.type_id = T.type_id";
                  $result = $conn->query($sql);
                  while ($product = $result->fetch_assoc())
                  {
                    echo <<< USER_DATA
                        <tr>
                            <td>$product[tytul]</td>
                            <td>
                              <a href="$product[picture_link]" data-toggle="lightbox" data-gallery="gallery">
                                <img src="$product[picture_link]" alt="$product[opis_short]" width="150">
                              </a>
                            </td>
                            <td>$product[opis_short]</td>
                            <td>$product[opis_long]</td>
                            <td>$product[type]</td>
                            <td>$product[cena]</td>
                            <td>
                                <a href="./warehouse.php?productid=$product[product_id]"><button class="btn btn-outline-primary btn-block">Stan magazynowy</button></a>
                                <a href="./addpicture.php?productid=$product[product_id]"><button class="btn btn-outline-primary btn-block">Dodaj zdjęcia</button></a>
                                <a href="./editproduct.php?productid=$product[product_id]"><button class="btn btn-outline-primary btn-block">Edytuj</button></a>
                                <a href="#warningModal" id="redirectSrc" data-toggle="modal" data-redirect="../scripts/deleteproduct.php?productid=$product[product_id]" data-productname="$product[tytul]"><button class="btn btn-outline-danger btn-block">Usuń</button></a>
                            </td>
                        </tr>
USER_DATA;

                  }

                  ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Nazwa</th>
                    <th>Główne zdjęcie</th>
                    <th>Krótki opis</th>
                    <th>Długi opis</th>
                    <th>Typ</th>
                    <th>Cena</th>
                    <th>Akcja</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
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

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Ekko Lightbox -->
<script src="../plugins/ekko-lightbox/ekko-lightbox.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Creating the proper DataTable -->
<script>
  $(function () {
    $("#usertab").DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "columnDefs": [
        {"className": "dt-center", "targets": "_all"}
      ],
      "buttons": [
        {
          extend: "copy",
          text:"Kopiuj",
          title: "SklepXYZ_Produkty",
          exportOptions: {
            columns: ':not(.noExport)'
          }
        },
        {
          extend: "csv",
          title: "SklepXYZ_Produkty",
          exportOptions: {
            columns: ':not(.noExport)'
          }
        },
        {
          extend: "excel",
          title: "SklepXYZ_Produkty",
          exportOptions: {
            columns: ':not(.noExport)'
          }
        },
        {
          extend: "pdf",
          title: "SklepXYZ_Produkty",
          exportOptions: {
            columns: ':not(.noExport)'
          }
        },
        {
          extend: "print",
          text:"Drukuj",
          title: "SklepXYZ_Produkty",
          exportOptions: {
            columns: ':not(.noExport)'
          }
        },
        {
          extend: "colvis",
          text:"Widoczność kolumn"
        }]
    }).buttons().container().appendTo('#usertab_wrapper .col-md-6:eq(0)');
  });
</script>
<script>
  $(document).on("click", "#redirectSrc", function () {
    var redirectUrl = $(this).data('redirect');
    var productname = $(this).data('productname');
    $("#redirect").attr('href', redirectUrl);
    $("#productname").text(productname);
  });
</script>
<script>
  //Enable tooltips
  $(function () {
    $('[data-toggle="tooltip"]').tooltip({ trigger: "hover" })
  })
</script>
<script>
  $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: false,
        showArrows: false
      });
    });
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
