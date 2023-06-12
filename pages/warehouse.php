<?php
session_start();
/** @var mysqli $conn*/

if(!isset($_GET["productid"]) || !is_numeric($_GET["productid"]))
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

$sql = "SELECT product_id, tytul FROM products WHERE product_id=$_GET[productid];";
$result = $conn->query($sql);
$product = $result->fetch_assoc();
if ($result->num_rows == 0)
{
  header("location: ./index.php");
}

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  echo "<title>SklepXYZ | Stan magazynowy - $product[tytul]</title>";
  ?>
  <link rel="icon" type="image/x-icon" href="../dist/img/favicon.png">

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
      <!--Modal Popup Form-->
      <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content bg-default">
            <form action="../scripts/addwh.php" method="POST" enctype="multipart/form-data">
              <?php
              echo "<input type='hidden' name='product_id' value=$_GET[productid]>";
              ?>
              <div class="modal-header">
                <h5 class="modal-title" id="warningModalLabel">Dodaj nowy rozmiar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <?php
                echo "<select title='size' name='size' class='form-control'>";
                $sql = "SELECT size_id, size FROM sizes";
                $result = $conn->query($sql);
                while ($size = $result->fetch_assoc())
                {
                  echo "<option value='$size[size_id]'>$size[size]</option>";
                }
                echo "</select>";
                ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                <button type="submit" class="btn btn-primary">Dodaj</button>
              </div>
            </form>
          </div>
        </div>
      </div>

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
              Uważaj! Zamierzasz usunąć rozmiar <b><div class="d-inline" id="size"></div></b> produktu <b><div class="d-inline" id="productname"></div></b> z magazynu.
              Kontynuować?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-dismiss="modal">Anuluj</button>
              <a id="redirect" href="#"><button type="button" class="btn btn-danger">Usuń</button></a>
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
                <a href="#formModal" data-toggle="modal" class="ml-auto">
                  <button class="btn btn-success"><i class="fa fa-xs fa-plus"></i> Dodaj nowy rozmiar</button>
                </a>
              </div>
              <div class="d-flex">

              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="usertab" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Rozmiar</th>
                    <th>Ilość</th>
                    <th class="noExport">Akcja</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  require_once "../scripts/dbconnect.php";
                  $sql = "SELECT W.product_id, P.tytul, S.size, S.size_id, W.count FROM `warehouse` W INNER JOIN sizes S ON S.size_id=W.size INNER JOIN products P ON P.product_id = W.product_id WHERE W.product_id=$_GET[productid]";
                  $result = $conn->query($sql);
                  while ($warehouse = $result->fetch_assoc())
                  {
                    echo <<< USER_DATA
                        <tr id="$warehouse[size_id]">
                            <td>
                              <form action="../scripts/editwh.php" method="POST">
                              <input type='hidden' name='product_id' value=$_GET[productid]>
                              <input type='hidden' name='size' value=$warehouse[size_id]>
                                $warehouse[size]
                            </td>
                            <td><input type="number" class="form-control count" disabled name="count" value="$warehouse[count]"></td>
                            <td class="d=flex">
                                <button type="button" class="btn btn-outline-primary updatebtn" data-id="$warehouse[size_id]">Zmień wartości</button>
                                <a href="#warningModal" id="redirectSrc" data-toggle="modal" data-redirect="../scripts/deletewh.php?productid=$warehouse[product_id]&size=$warehouse[size_id]" data-size="$warehouse[size]" data-productname="$warehouse[tytul]"><button class="btn btn-outline-danger">Usuń</button></a>
                              </form>
                            </td>
                        </tr>
USER_DATA;

                  }

                  ?>
                  </tbody>
                  <tfoot>
                  <tr>
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

  <?php
  require_once "./footer.php";
  ?>

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
    var size = $(this).data('size');
    var productname = $(this).data('productname');
    $("#redirect").attr('href', redirectUrl);
    $("#size").text(size);
    $("#productname").text(productname);
  });
</script>
<script>
  $(document).on("mouseup", ".updatebtn", function () {
    var wh_id = $(this).data('id');
    var sbtn=$(".submitbtn");
    var ubtn=$("#"+wh_id+" .updatebtn")
    sbtn.attr('type', 'button');
    sbtn.text("Zmień wartości");
    sbtn.addClass("updatebtn");
    sbtn.removeClass("submitbtn");
    $(".count").prop('disabled', true)
    setTimeout(function ()
    {
      $("#"+wh_id+" .count").prop('disabled', false);
      ubtn.attr('type', 'submit');
      ubtn.text("Aktualizuj");
      ubtn.addClass("submitbtn");
      ubtn.removeClass("updatebtn");
    }, 10)
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
