<?php
/** @var mysqli $conn */
/** @var int $product_id */
/** @var int $size */
session_start();
//unset($_SESSION["cart"]);
//print_r($_POST);
$required_fields = ["product_id", "size"];
$translation_arr = ["size" => "Rozmiar", "product_id" => "Identyfikator Produktu"];

if($_SERVER["REQUEST_METHOD"]=="POST")
{
  foreach ($_POST as $key => $value)  //Change $_POST[name] to $name for simplicity
  {
    $$key = $_POST["$key"];
    //echo $$key;
  }
}
elseif($_SERVER["REQUEST_METHOD"]=="GET")
{
  foreach ($_GET as $key => $value)  //Change $_GET[name] to $name for simplicity
  {
    $$key = $_GET["$key"];
    //echo $$key;
  }
}

foreach ($required_fields as $value)  //Have all the required fields made it?
{
  if (empty($$value)) {
    $rq_field_err[] = "Pole <b>$translation_arr[$value]</b> jest wymagane";
  }
}

if (!empty($rq_field_err))
{
  $_SESSION["error"] = implode("<br>", $rq_field_err);
  echo "<script>history.back();</script>";
  exit();
}

require_once "./dbconnect.php";
//Do the required fields make sense?
$stmt_sid = $conn->prepare("SELECT size_id FROM sizes WHERE size_id=?");
$stmt_sid->bind_param("i", $size);
$stmt_sid->execute();
$result_sid = $stmt_sid->get_result();
if($result_sid->num_rows == 0)
{
  $_SESSION["error"] = "Wystąpił błąd. Skontaktuj się z administratorem";
  header("location: ../pages/index.php");
  exit();
}

$stmt_pid = $conn->prepare("SELECT product_id FROM products WHERE product_id=?");
$stmt_pid->bind_param("i", $product_id);
$stmt_pid->execute();
$result_pid = $stmt_pid->get_result();
if($result_pid->num_rows == 0)
{
  $_SESSION["error"] = "Wystąpił błąd. Skontaktuj się z administratorem";
  header("location: ../pages/index.php");
  exit();
}


if (!isset($_SESSION["cart"]))
{
  header("location: ../pages/index.php");
  exit();
}
else
{
  if(isset($_GET["del"]) || count($_SESSION["cart"][$product_id]) == 1)
  {
    if(count($_SESSION["cart"]) == 1)
      unset($_SESSION["cart"]);
    else
      unset($_SESSION["cart"][$product_id]);
  }
  else
  {
    foreach($_SESSION["cart"][$product_id] as $key => $value) {
      if ($value == $size)
      {
        unset($_SESSION["cart"][$product_id][$key]);
        break;
      }
    }
  }
  $_SESSION["success"] = "Produkt został usunięty z koszyka!";
  echo "<script>history.back();</script>";
  exit();
}
//header("location: ../pages/index.php");
?>
