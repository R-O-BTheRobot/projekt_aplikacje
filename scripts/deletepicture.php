<?php
  session_start();
  /** @var mysqli $conn*/

  require_once "./dbconnect.php";

  if(!isset($_SESSION["loggedIn"]["role_ID"]) || $_SESSION["loggedIn"]["role_ID"] == 1)
  {
    header("location: ../pages/index.php");
    exit();
  }

  if(!isset($_GET["picid"]))
  {
    header("location: ../pages/index.php");
    exit();
  }

  $sqlsel = "SELECT id, picture_link FROM pictures WHERE id=$_GET[picid]";
  $resultsel = $conn->query($sqlsel);

  if ($resultsel->num_rows != 1)
  {
    $_SESSION["error"] = "Coś poszło nie tak. Czy zdjęcie nie zostało już usunięte?";
    echo "<script>history.back()</script>";
    exit();
  }
  else
  {
    $prod = $resultsel->fetch_assoc();
  }


  $sqlpic = "DELETE FROM pictures WHERE id = $_GET[picid]";
  $conn->query($sqlpic);
  if ($conn->affected_rows == 1)
  {
    $_SESSION["success"] = "Zdjęcie zostało usunięte!";
    unlink(realpath($prod["picture_link"]));
    echo "<script>history.back()</script>";
    exit();
  }
  else
  {
    $_SESSION["error"] = "Coś poszło nie tak. Skontaktuj się z administratorem.";
    echo "<script>history.back()</script>";
    exit();
  }
?>
