<?php
session_start();
if (isset($_SESSION["logged"]) && session_status() == 2 && session_id() == $_SESSION["logged"]["session_id"]){
  $_SESSION["error"] = "Zaloguj się!";
  echo "<script>history.back();</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in (v2)</title>

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
  <?php
  if (isset($_GET["error"])){
    echo <<< ERROR
        <div class="callout callout-danger">
                  <h5>Błąd!</h5>
                  <p>$_GET[error]</p>
        </div>
ERROR;
    unset($_GET["error"]);
  }

  if (isset($_GET["logout"])){
    echo <<< ERROR
        <div class="callout callout-info">
                  <h5>Info!</h5>
                  <p>Prawidłowo wylogowano użytkownika</p>
        </div>
ERROR;
    unset($_GET["logout"]);
  }

  if (isset($_SESSION["success"])){
    echo <<< ERROR
        <div class="callout callout-success">
                  <h5>Gratulacje!</h5>
                  <p>$_SESSION[success]</p>
        </div>
ERROR;
    unset($_SESSION["success"]);
  }

  if (isset($_SESSION["error"])){
    echo <<< ERROR
        <div class="callout callout-danger">
                  <h5>Błąd!</h5>
                  <p>$_SESSION[error]</p>
        </div>
ERROR;
    unset($_SESSION["error"]);
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
          <input type="email" class="form-control" placeholder="E-mail" name="email">
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
        <div class="row">
          <div class="col-7">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Nie wylogowuj
              </label>
            </div>
          </div>
          <!-- /.col -->

          <!-- /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center mt-2 mb-3">
        <button type="submit" class="btn btn-primary btn-block">Zaloguj się</button>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div>
      <!-- /.social-auth-links -->

      <p class="mb-1">
        <a href="forgot-password.html">Nie pamiętasz hasła?</a>
      </p>
      <p class="mb-0">
        Nie masz konta?<a href="register.php" class="text-center">Zarejestruj się</a>
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
</body>
</html>
