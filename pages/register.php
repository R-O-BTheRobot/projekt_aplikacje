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

  <!-- TOS Modal Popup -->
  <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Polityka Prywatności Sklepu XYZ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>1) Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempor nisi id massa porttitor, sit amet posuere nisi malesuada. Fusce in ultricies odio. Maecenas congue vel nisi congue fringilla. Nulla magna lectus, convallis sed molestie eu, posuere nec risus. Nullam consequat vestibulum neque, at congue nisl lacinia non. Pellentesque quis neque lacinia, fermentum quam non, iaculis augue. Quisque rhoncus elementum lacus in aliquet. Aliquam euismod lacus nec congue imperdiet. Vivamus id risus tortor. Proin tristique sem erat, at gravida leo consectetur ac. Proin ultrices lectus eget felis lobortis, a mattis magna commodo. Pellentesque ligula massa, vestibulum sit amet ex sed, aliquam ultrices dolor. Ut nibh nulla, finibus vitae lorem eu, feugiat rhoncus enim. </p>
          <p>2) Suspendisse aliquam porttitor nibh, at mollis erat pulvinar eu. Ut quam ipsum, sodales eget convallis non, venenatis nec libero. Praesent sollicitudin arcu libero, ac fermentum leo ultricies sed. Sed metus purus, tempor non rhoncus nec, rutrum ac elit. Aenean tincidunt, ipsum a interdum fermentum, felis nisi pulvinar dui, quis dictum neque diam eu leo. Curabitur sed felis auctor, pharetra nisi a, congue elit. Nulla facilisi. Suspendisse placerat diam a est suscipit condimentum. Nullam cursus, ante pharetra suscipit bibendum, orci magna sodales arcu, sit amet tristique risus leo sed nibh. Quisque feugiat, libero ut viverra aliquet, felis lorem cursus enim, nec aliquam turpis nunc sit amet magna. Mauris posuere ultricies varius. Integer iaculis id dui vel suscipit. Morbi vitae fringilla felis, sit amet vestibulum lorem. Duis non mauris libero. Curabitur vel lacus euismod, lobortis risus at, ornare dui. </p>
          <p>3) Nullam laoreet nibh ut mi euismod eleifend. Aliquam a mattis dolor. Quisque dictum varius mollis. Donec felis augue, aliquam at dui ac, faucibus ultrices elit. Maecenas lobortis, odio et dictum pretium, odio arcu consectetur tortor, et sollicitudin turpis tortor non arcu. Etiam vel venenatis metus. Mauris efficitur, mauris eu interdum sodales, felis urna gravida justo, ut pharetra nulla nunc id urna. Phasellus in orci ullamcorper, sodales lacus eget, sagittis erat. Cras placerat urna ex, et malesuada augue blandit non. Morbi varius, justo eget semper sollicitudin, elit odio viverra quam, sed ultricies orci augue et ex. Phasellus tempus feugiat risus, id dictum orci rhoncus vel. Nam lorem libero, gravida vel nibh sit amet, vestibulum auctor purus. Ut placerat felis ut magna tempus venenatis. Vivamus nibh ligula, consequat vel ligula a, tincidunt aliquam eros. </p>
          <p>4) Quisque lobortis ultrices ante, in consectetur ante vulputate vel. Integer vel varius ex. Maecenas at metus eros. Integer convallis dolor vel elit ultricies, a fermentum leo vehicula. Aliquam ut nisl non neque gravida tempor in ut eros. Ut ornare neque nibh, a iaculis orci condimentum et. In hac habitasse platea dictumst. </p>
          <p>5) Vivamus ultricies quam mi, ac faucibus metus blandit vel. Donec massa diam, pharetra sit amet volutpat at, condimentum nec odio. Cras fringilla erat nunc, eget consectetur sapien facilisis nec. Quisque blandit nibh sed ex imperdiet sagittis. Fusce ac scelerisque massa. Vestibulum nec mattis dolor. In convallis iaculis gravida. Integer purus massa, maximus vitae diam nec, ultrices suscipit leo. Aenean et nulla ex. Nunc non elit nec justo vestibulum semper ac non nunc. Aenean viverra, felis vitae bibendum eleifend, risus risus rutrum lectus, vel dignissim erat tortor malesuada elit. Sed pharetra lacinia ex sit amet tempus. Curabitur ante enim, aliquet vel porta sit amet, viverra in turpis. Quisque commodo vitae erat a rutrum. Nunc quis magna sollicitudin, suscipit magna nec, viverra arcu. Morbi tristique dapibus dolor in semper. </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Zamknij</button>
        </div>
      </div>
    </div>
  </div>

  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="./" class="h1"><b>Sklep</b>XYZ</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Załóż konto </p>

      <form action="../scripts/register.php" method="post">


        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Imię" name="firstName">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Nazwisko" name="lastName">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="E-mail" name="mail">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Hasło" name="pass1">
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
          <p class="text-muted text-xs">
            Min. 8 znaków, maks. 32 znaki, w tym jedna wielka i mała litera, cyfra i znak specjalny.
          </p>
        </div>

        <div class="row">
          <div class="col-15">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
                Przeczytałem(am) i zrozumiałem(am) informacje dotyczące korzystania z moich danych osobowych wyjaśnione w <a href="#" data-toggle="modal" data-target="#infoModal">Polityce Prywatności </a>
              </label>
            </div>
          </div>
        </div>
        <div class="social-auth-links text-center">
          <button type="submit" class="btn btn-primary btn-block">Załóż konto</button>
        </div>
      </form>

      <a href="./login.php" class="text-center">Mam konto</a>
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
