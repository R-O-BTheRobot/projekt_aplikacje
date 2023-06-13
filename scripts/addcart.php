<?php
/** @var mysqli $conn */
/** @var int $product_id */
/** @var int $size */
session_start();

$required_fields = ["size", "product_id"];
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
$stmt_pid = $conn->prepare("SELECT product_id FROM products WHERE product_id=?");
$stmt_pid->bind_param("i", $product_id);
$stmt_pid->execute();
$result_pid = $stmt_pid->get_result();

if($result_sid->num_rows == 0 || $result_pid->num_rows == 0)  //Do the required fields make sense?
{
  $_SESSION["error"] = "Wystąpił błąd. Skontaktuj się z administratorem";
  header("location: ../pages/index.php");
  exit();
}


if (!isset($_SESSION["cart"]))  //Check if the cart variable is set
{
  $stmt = $conn->prepare("SELECT item_id FROM warehouse WHERE product_id=? AND size=?;");
  $stmt->bind_param("ii", $product_id, $size);
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows != 0)  //Is it in the warehouse?
  {
    $_SESSION["cart"][$product_id][] = $size; //Add to cart
    echo "<br>";
    echo "<br>";
    $_SESSION["success"] = "Produkt został dodany do koszyka!";
    echo "<script>history.back();</script>";
    exit();
  }
  else  //Throw an error
  {
    $_SESSION["error"] = "Wybranego produktu nie ma już w magazynie. Przepraszamy za niedogodności.";
    echo "<script>history.back();</script>";
    exit();
  }
}
else  //If cart var is set
{
  $stmt = $conn->prepare("SELECT count FROM warehouse WHERE product_id=? AND size=?;");
  $stmt->bind_param("ii", $product_id, $size);
  $stmt->execute();
  $result = $stmt->get_result()->fetch_assoc();
  if(isset($_SESSION["cart"][$product_id])) //Is there another product of the same type in the cart?
  {
    $count = array_count_values($_SESSION["cart"][$product_id]);  //Count how many of each size are there
    if(isset($count[$size]))  //Check if there's a product of the same type and size in the cart already
    {
      if($result["count"] != 0 && $count[$size]+1 <= $result["count"])  //Check if the count isn't 0 and if adding another one won't go over the warehouse limits
      {
        if(isset($_SESSION["cart"][$product_id])) //This check appears redundant. Will likely be removed in a future revision
        {
          $_SESSION["cart"][$product_id][] = $size;
          print_r($_SESSION["cart"][$product_id]);
        }
        else
        {
          $_SESSION["cart"][$product_id][] = $size;
          print_r(array($product_id => array($size)));
          print_r($_SESSION["cart"]);
          print_r($_SESSION["cart"][$product_id]);
        }
        $_SESSION["success"] = "Produkt został dodany do koszyka!"; //Announce success and return to the previous page
        echo "<script>history.back();</script>";
        exit();
      }
      else  //Throw an error
      {
        $_SESSION["error"] = "Brak dodatkowych sztuk w magazynie. Przepraszamy za niedogodności.";
        echo "<script>history.back();</script>";
        exit();
      }
    }
    else  //If there is a product of the same type and size
    {
      if($result["count"] != 0) //Check if the size is available in the warehouse
      {
        if(isset($_SESSION["cart"][$product_id])) //The redundant check appears to be copied in here too. Will likely be removed in the future
        {
          $_SESSION["cart"][$product_id][] = $size;
          print_r($_SESSION["cart"][$product_id]);
        }
        else
        {
          $_SESSION["cart"][$product_id][] = $size;
          print_r(array($product_id => array($size)));
          print_r($_SESSION["cart"]);
          print_r($_SESSION["cart"][$product_id]);
        }
        $_SESSION["success"] = "Produkt został dodany do koszyka!"; //Announce success and return to the previous page
        echo "<script>history.back();</script>";
        exit();
      }
      else  //Throw an error
      {
        $_SESSION["error"] = "Wybranego produktu nie ma już w magazynie. Przepraszamy za niedogodności.";
        echo "<script>history.back();</script>";
        exit();
      }
    }
  }
  else  //If there's no product of the same type in the cart
  {
    if($result["count"] != 0) //Check if the size is available in the warehouse
    {
      if(isset($_SESSION["cart"][$product_id])) //Once again, the seemingly redundant check appears here too
      {
        $_SESSION["cart"][$product_id][] = $size;
        print_r($_SESSION["cart"][$product_id]);
      }
      else
      {
        $_SESSION["cart"][$product_id][] = $size;
        print_r(array($product_id => array($size)));
        print_r($_SESSION["cart"]);
        print_r($_SESSION["cart"][$product_id]);
      }
      $_SESSION["success"] = "Produkt został dodany do koszyka!"; //Announce success and return to the previous pag
      echo "<script>history.back();</script>";
      exit();
    }
    else  //Throw an error
    {
      $_SESSION["error"] = "Wybranego produktu nie ma już w magazynie. Przepraszamy za niedogodności.";
      echo "<script>history.back();</script>";
      exit();
    }
  }
}
//header("location: ../pages/index.php");
?>
