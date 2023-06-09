<?php
  session_start();
  /** @var mysqli $conn*/
  header("location: ../pages/modpanel.php");

  require_once "./dbconnect.php";

  if(!isset($_GET["productid"]))
  {
    header("location: ../pages/index.php");
    exit();
  }

  $sqlsel = "SELECT tytul, picture_link FROM products WHERE product_id=$_GET[productid]";
  $resultsel = $conn->query($sqlsel);

  if ($resultsel->num_rows != 1)
  {
    $_SESSION["error"] = "Coś poszło nie tak. Czy produkt nie został już usunięty?";
    exit();
  }
  else
  {
    $prod = $resultsel->fetch_assoc();
  }

  $sqlwh = "DELETE FROM warehouse WHERE product_id = $_GET[productid]";
  $conn->query($sqlwh);
  $sqlpic = "DELETE FROM pictures WHERE product_id = $_GET[productid]";
  $conn->query($sqlpic);
  $sqlprod = "DELETE FROM products WHERE product_id = $_GET[productid]";
  $conn->query($sqlprod);
  if ($conn->affected_rows == 1)
  {
    $_SESSION["success"] = "Pomyślnie usunięto produkt $prod[tytul]";
    unlink(realpath($prod["picture_link"]));
  }
  else
  {
    $_SESSION["error"] = "Coś poszło nie tak. Skontaktuj się z administratorem.";
  }
?>
