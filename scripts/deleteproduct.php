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

  $sqlsel = "SELECT tytul FROM products WHERE product_id=$_GET[productid]";
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
  $sqlprod = "DELETE FROM products WHERE product_id = $_GET[productid]";
  $conn->query($sqlprod);
  if ($conn->affected_rows == 1)
  {
    //echo "Rekord usunięty pomyślnie!";
    $_SESSION["success"] = "Pomyślnie usunięto produkt $prod[tytul]";
  }
  else
  {
    $_SESSION["error"] = "Coś poszło nie tak. Skontaktuj się z administratorem.";
  }
?>
