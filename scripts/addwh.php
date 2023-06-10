<?php
session_start();
/** @var mysqli $conn*/

require_once "./dbconnect.php";

if($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["product_id"]) || !isset($_POST["size"]) || !isset($_SESSION["loggedIn"]["role_ID"]) || $_SESSION["loggedIn"]["role_ID"] == 1)
{
  header("location: ../pages/index.php");
  exit();
}

$sqlprod = "SELECT product_id FROM products WHERE product_id=$_POST[product_id]";
$resultprod = $conn->query($sqlprod);

if ($resultprod->num_rows != 1)
{
  $_SESSION["error"] = "Coś poszło nie tak. ID produktu jest błędne.";
  echo "<script>history.back()</script>";
  exit();
}

$sqlsz = "SELECT size_id FROM sizes WHERE size_id=$_POST[size]";
$resultsz = $conn->query($sqlsz);

if ($resultsz->num_rows != 1)
{
  $_SESSION["error"] = "Coś poszło nie tak. Rozmiar jest błędny.";
  echo "<script>history.back()</script>";
  exit();
}

$stmt = $conn->prepare("INSERT INTO `warehouse` (`product_id`, `size`) VALUES (?, ?)");
$stmt->bind_param('is', $_POST["product_id"], $_POST["size"]);
$stmt->execute();

if ($stmt->affected_rows == 1)
{
  $_SESSION["success"] = "Zdjęcie zostało dodane!";
  echo "<script>history.back()</script>";
  exit();
}
else
{
  $_SESSION["error"] = "Wystąpił błąd. Skontaktuj się z administratorem.";
  echo "<script>history.back()</script>";
  exit();
}

?>
