<?php
session_start();
/** @var mysqli $conn*/

require_once "./dbconnect.php";

if($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION["loggedIn"]["role_ID"]) || $_SESSION["loggedIn"]["role_ID"] == 1)
{
  header("location: ../pages/index.php");
  exit();
}

if(!isset($_POST["product_id"]) || !isset($_POST["size"]) || !isset($_POST["count"]))
{
  $_SESSION["error"] = "Coś poszło nie tak. Skontaktuj się z administratorem.";
  echo "<script>history.back()</script>";
  exit();
}

$sqlwh = "SELECT item_id FROM warehouse WHERE product_id=$_POST[product_id] AND size=$_POST[size]";
$resultwh = $conn->query($sqlwh);

if ($resultwh->num_rows != 1)
{
  $_SESSION["error"] = "Coś poszło nie tak. Skontaktuj się z administratorem.";
  echo "<script>history.back()</script>";
  exit();
}
else
{
  $item_id = $resultwh->fetch_assoc()["item_id"];
}

$stmt = $conn->prepare("UPDATE `warehouse` SET `count` = ? WHERE `warehouse`.`item_id` = ?");
$stmt->bind_param('ii', $_POST["count"], $item_id);
$stmt->execute();

if ($stmt->affected_rows == 1)
{
  $_SESSION["success"] = "Przedmioty zostały dodane!";
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
