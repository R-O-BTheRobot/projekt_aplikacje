<?php
/** @var mysqli $conn */
/** @var int $product_id */
/** @var int $size */
session_start();

$required_fields = ["product_id", "size"];
$translation_arr = ["size" => "Rozmiar", "product_id" => "Identyfikator Produktu"];

if($_SERVER["REQUEST_METHOD"]=="POST")  //Check if the request came through POST and not GET
{
  foreach ($_POST as $key => $value)  //Change $_POST[name] to $name for simplicity
  {
    $$key = $_POST["$key"];
    //echo $$key;
  }
}
elseif($_SERVER["REQUEST_METHOD"]=="GET") //Check if the request came through GET and not POST
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

require_once "./dbconnect.php"; //Loading up all the queries to check if the required fields make sense
$stmt_sid = $conn->prepare("SELECT size_id FROM sizes WHERE size_id=?");
$stmt_sid->bind_param("i", $size);
$stmt_sid->execute();
$result_sid = $stmt_sid->get_result();
if($result_sid->num_rows == 0)  //Check if the Size ID exists
{
  $_SESSION["error"] = "Wystąpił błąd. Skontaktuj się z administratorem";
  header("location: ../pages/index.php");
  exit();
}

$stmt_pid = $conn->prepare("SELECT product_id FROM products WHERE product_id=?");
$stmt_pid->bind_param("i", $product_id);
$stmt_pid->execute();
$result_pid = $stmt_pid->get_result();
if($result_pid->num_rows == 0)  //Check if the Product ID exists
{
  $_SESSION["error"] = "Wystąpił błąd. Skontaktuj się z administratorem";
  header("location: ../pages/index.php");
  exit();
}


if (!isset($_SESSION["cart"]))  //Check if the cart variable is set
{
  header("location: ../pages/index.php");
  exit();
}
else  //If cart var is set
{
  if(count($_SESSION["cart"][$product_id]) == 1)  //Check if there's only 1 size of the product left in the cart
  {
    if(count($_SESSION["cart"]) == 1) //Check if it's the only product in the cart
      unset($_SESSION["cart"]);
    else
      unset($_SESSION["cart"][$product_id]);
  }
  elseif(isset($_GET["del"])) //Check if the additional $_GET["del"] variable has been passed from /pages/checkout.php, which is supposed to delete the whole size of the product
  {
    foreach($_SESSION["cart"][$product_id] as $key => $value)
    {
      if ($value == $size)  //Check if the size is the one supposed to be deleted
      {
        if(count($_SESSION["cart"][$product_id]) == 1)  //Check if there's only 1 size of the product left in the cart
        {
          if(count($_SESSION["cart"]) == 1) //Check if it's the only product in the cart
          {
            unset($_SESSION["cart"]);
            break;
          }
        }
        else
          unset($_SESSION["cart"][$product_id][$key]);
      }
    }
  }
  else  //If there's more than one size of the product left in the cart and $_GET["del"] hasn't been passed
  {
    foreach($_SESSION["cart"][$product_id] as $key => $value)
    {
      if ($value == $size)  //Check if the size is the one supposed to be deleted
      {
        unset($_SESSION["cart"][$product_id][$key]);
        break;
      }
    }
  }
  $_SESSION["success"] = "Produkt został usunięty z koszyka!";  //Announce success and return to the previous page
  echo "<script>history.back();</script>";
  exit();
}
//header("location: ../pages/index.php");
?>
