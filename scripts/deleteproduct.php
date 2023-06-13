<?php
  session_start();
  /** @var mysqli $conn*/
  header("location: ../pages/modpanel.php");

  require_once "./dbconnect.php";

  if(!isset($_SESSION["loggedIn"]["role_ID"]) || $_SESSION["loggedIn"]["role_ID"] == 1) //Is the correct user type accessing this script?
  {
    header("location: ../pages/index.php");
    exit();
  }

  if(!isset($_GET["productid"]))  //Check if the Product ID has been passed
  {
    header("location: ../pages/index.php");
    exit();
  }

  $sqlsel = "SELECT tytul, picture_link FROM products WHERE product_id=$_GET[productid]";
  $resultsel = $conn->query($sqlsel);

  if ($resultsel->num_rows != 1)  //Check if Product ID exists
  {
    $_SESSION["error"] = "Coś poszło nie tak. Czy produkt nie został już usunięty?";
    exit();
  }
  else
  {
    $prod = $resultsel->fetch_assoc();
  }

  $sqlwh = "DELETE FROM warehouse WHERE product_id = $_GET[productid]"; //Delete all warehouse items with the given Product ID
  $conn->query($sqlwh);
  $sqlpic = "DELETE FROM pictures WHERE product_id = $_GET[productid]"; //Delete all pictures with the given Product ID
  $conn->query($sqlpic);
  $sqlprod = "DELETE FROM products WHERE product_id = $_GET[productid]";  //Delete the product itself
  $conn->query($sqlprod);
  if ($conn->affected_rows == 1)  //Check if SQL executed properly and affected 1 row
  {
    $_SESSION["success"] = "Pomyślnie usunięto produkt $prod[tytul]"; //Announce success and return to the previous page
    unlink(realpath($prod["picture_link"]));
  }
  else  //Throw an error
  {
    $_SESSION["error"] = "Coś poszło nie tak. Skontaktuj się z administratorem.";
  }
?>
