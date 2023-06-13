<?php
session_start();
/** @var mysqli $conn*/

require_once "./dbconnect.php";

if($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION["loggedIn"]["role_ID"]) || $_SESSION["loggedIn"]["role_ID"] == 1) //Check if the request came through POST and not GET and if the correct user type is accessing the script
{
  header("location: ../pages/index.php");
  exit();
}

$rq_fields = ["product_id", "size"];
$translation_arr = ["product_id" => "Identyfikator Protuktu", "size" => "Rozmiar"];

foreach($rq_fields as $value) //Checking for all the required values
{
  if(empty($_POST[$value]))
    $empty_fields[] = "Pole <b>$translation_arr[$value]</b> jest puste.";
}

if (!empty($empty_fields))  //Throw an error if any are missing
{
  $_SESSION["error"] = implode("<br>", $empty_fields);
  echo "<script>history.back();</script>";
  exit();
}

$sqlprod = "SELECT product_id FROM products WHERE product_id=$_POST[product_id]";
$resultprod = $conn->query($sqlprod);

if ($resultprod->num_rows != 1) //Check if the Product ID exists
{
  $_SESSION["error"] = "Coś poszło nie tak. ID produktu jest błędne.";
  echo "<script>history.back()</script>";
  exit();
}

$sqlsz = "SELECT size_id FROM sizes WHERE size_id=$_POST[size]";
$resultsz = $conn->query($sqlsz);

if ($resultsz->num_rows != 1) //Check if the Size ID exists
{
  $_SESSION["error"] = "Coś poszło nie tak. Rozmiar jest błędny.";
  echo "<script>history.back()</script>";
  exit();
}

$stmt = $conn->prepare("INSERT INTO `warehouse` (`product_id`, `size`) VALUES (?, ?)");
$stmt->bind_param('is', $_POST["product_id"], $_POST["size"]);
$stmt->execute();

if ($stmt->affected_rows == 1)  //Check if SQL executed properly and affected 1 row
{
  $_SESSION["success"] = "Zdjęcie zostało dodane!"; //Announce success and return to the previous page
  echo "<script>history.back()</script>";
  exit();
}
else  //Throw an error
{
  $_SESSION["error"] = "Wystąpił błąd. Skontaktuj się z administratorem.";
  echo "<script>history.back()</script>";
  exit();
}

?>
