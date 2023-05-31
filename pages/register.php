<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Registration Page (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <?php
  if (isset($_SESSION["error"])){
    echo <<< ERROR
        <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-ban"></i> Uwaga!</h5>
    $_SESSION[error]
  </div>
ERROR;
    unset($_SESSION["error"]);
  }

  if (isset($_SESSION["success"])){
    echo <<< ERROR
        <div class="alert alert-success alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-ban"></i> Uwaga!</h5>
    $_SESSION[success]
  </div>
ERROR;
    unset($_SESSION["success"]);
  }
  ?>





  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="./" class="h1"><b>Sklep</b>XYZ</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Załóż konto </p>

      <form action="../scripts/register.php" method="post">



        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Podaj email" name="email1">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Podaj hasło" name="pass1">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Powtórz hasło" name="pass2">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div>
          <p style="font-size:12px; color:grey;">


            Minimum 8 znaków, w tym jedna wielka i mała litera oraz cyfra. Ten sam znak nie może powtarzać się więcej niż 3 razy
          </p>
        </div>







        <div class="row">
          <div class="col-15">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
                Przeczytałem(am) i zrozumiałem(am) informacje dotyczące korzystania z moich danych osobowych wyjaśnione w <a href="#">Polityce Prywatności </a>
              </label>
            </div>
          </div>

          <!-- /.col -->
          <!--<div class="social-auth-links text-center">
            <button type="submit" class="btn btn-primary btn-block">Załóż konto</button>
          </div>
           /.col -->
        </div>
      </form>

      <div class="social-auth-links text-center">
        <button type="submit" class="btn btn-primary btn-block">Załóż konto</button>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i>
          Sign up using Google+
        </a>
      </div>

      <a href="./" class="text-center">Mam konto</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>