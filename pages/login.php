<?php
session_start();
/** @var mysqli $conn*/
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
  <title>Sklep XYZ | Login</title>
  <link rel="icon" type="image/x-icon" href="../dist/img/favicon.png">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <!-- Modal Popup Conditional -->
  <?php
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
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="./index.php" class="h1"><b>Sklep</b>XYZ</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Zaloguj się lub załóż konto</p>

      <form action="../scripts/login.php" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="E-mail" name="mail">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Hasło" name="pass">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="social-auth-links text-center mt-2 mb-3">
          <button type="submit" class="btn btn-primary btn-block">Zaloguj się</button>
        </div>
      </form>

      <p class="mb-0">
        Nie masz konta? <a href="register.php" class="text-center">Zarejestruj się!</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
<!-- Modal Popup Script -->
<?php
if (isset($_SESSION["error"]))
{
  echo <<< MODALJS
<script>
  $(window).on('load', function() {
    $('#alertModal').modal('show')
  })
</script>
MODALJS;
}
unset($_SESSION["error"]);
?>
</body>
</html>
