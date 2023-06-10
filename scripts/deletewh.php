<?php
session_start();
/** @var mysqli $conn*/

require_once "./dbconnect.php";

//print_r($_GET);
//exit();

if(!isset($_SESSION["loggedIn"]["role_ID"]) || $_SESSION["loggedIn"]["role_ID"] == 1)
{
  header("location: ../pages/index.php");
  exit();
}

if(!isset($_GET["productid"]) || !isset($_GET["size"]))
{
  header("location: ../pages/index.php");
  exit();
}

$sqlsel = "SELECT item_id, product_id, size FROM warehouse WHERE product_id=$_GET[productid] AND size=$_GET[size]";
$resultsel = $conn->query($sqlsel);

if ($resultsel->num_rows != 1)
{
  $_SESSION["error"] = "Coś poszło nie tak. Czy użytkownik nie został już usunięty?";
  echo "<script>history.back()</script>";
  exit();
}
else
{
  $sql = "SELECT S.size FROM warehouse W INNER JOIN sizes S ON S.size_id = W.size WHERE W.product_id=$_GET[productid] AND W.size=$_GET[size]";
  $result = $conn->query($sql);
  $wh = $result->fetch_assoc();
}

$sql = "DELETE FROM warehouse WHERE product_id=$_GET[productid] AND size=$_GET[size]";
$conn->query($sql);
if ($conn->affected_rows == 1)
{
  //echo "Rekord usunięty pomyślnie!";
  $_SESSION["success"] = "Pomyślnie usunięto rozmiar $wh[size]";
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
