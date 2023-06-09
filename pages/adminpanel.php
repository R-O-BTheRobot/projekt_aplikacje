<?php
session_start();
if(!isset($_SESSION["loggedIn"]["role_ID"]))
{
  header("location: ./index.php");
}
else
{
  switch ($_SESSION["loggedIn"]["role_ID"])
  {
    case 1:
    case 2:
      header("location: ./index.php");
      break;
    case 3:
      break;
  }
}
/** @var mysqli $conn*/
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
  require_once "./content_admin/navbar.php";
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
              Uważaj! Zamierzasz usunąć użytkownika <b><div class="d-inline" id="username"></div></b>.
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
              <div class="card-header">
                <h3 class="card-title">Panel zarządzania użytkownikami</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="usertab" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>E-mail</th>
                    <th>Typ</th>
                    <th>Data utworzenia</th>
                    <th class="noExport">Akcja</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                  require_once "../scripts/dbconnect.php";
                  $sql = "SELECT U.id, U.firstName, U.lastName, U.email, R.role, U.created_at FROM users U INNER JOIN roles R ON R.role_id = U.role_id";
                  $result = $conn->query($sql);
                  while ($user = $result->fetch_assoc())
                  {
                    echo <<< USER_DATA
                        <tr>
                            <td>$user[firstName]</td>
                            <td>$user[lastName]</td>
                            <td>$user[email]</td>
                            <td>$user[role]</td>
                            <td>$user[created_at]</td>
                            <td class="d-flex">
                                <a href="./edituser.php?userid=$user[id]"><button class="btn btn-outline-primary">Edytuj</button></a><br/>
                                <a href="#warningModal" id="redirectSrc" data-toggle="modal" data-redirect="../scripts/deleteuser.php?userid=$user[id]" data-username="$user[firstName] $user[lastName]"><button class="btn btn-outline-danger">Usuń</button></a>
                            </td>
                        </tr>
USER_DATA;

                  }

                  ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Imię</th>
                    <th>Nazwisko</th>
                    <th>E-mail</th>
                    <th>Typ</th>
                    <th>Data utworzenia</th>
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
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Creating the proper DataTable -->
<script>
  $(function () {
    $("#usertab").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": [
        {
          extend: "copy",
          text:"Kopiuj",
          exportOptions: {
            columns: ':not(.noExport)'
          }
        },
        {
          extend: "csv",
          title: "SklepXYZ_Użytkownicy",
          exportOptions: {
            columns: ':not(.noExport)'
          }
        },
        {
          extend: "excel",
          title: "SklepXYZ_Użytkownicy",
          exportOptions: {
            columns: ':not(.noExport)'
          }
        },
        {
          extend: "pdf",
          title: "SklepXYZ_Użytkownicy",
          exportOptions: {
            columns: ':not(.noExport)'
          }
        },
        {
          extend: "print",
          text:"Drukuj",
          title: "SklepXYZ_Użytkownicy",
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
    var username = $(this).data('username');
    $("#redirect").attr('href', redirectUrl);
    $("#username").text(username);
  });
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
