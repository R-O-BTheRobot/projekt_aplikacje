<?php
session_start();
/** @var mysqli $conn*/

require_once "./dbconnect.php";

if($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION["loggedIn"]["role_ID"]) || $_SESSION["loggedIn"]["role_ID"] == 1)
{
  header("location: ../pages/index.php");
  exit();
}

$rq_fields = ["product_id", "size", "count"];
$translation_arr = ["product_id" => "Identyfikator Protuktu", "size" => "Rozmiar", "count" => "Ilość"];

foreach($rq_fields as $value)
{
  if(strlen($_POST[$value]) == 0)
    $empty_fields[] = "Pole <b>$translation_arr[$value]</b> jest puste. strlen($_POST[$value])";
}

if (!empty($empty_fields))
{
  $_SESSION["error"] = implode("<br>", $empty_fields);
  echo "<script>history.back();</script>";
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
