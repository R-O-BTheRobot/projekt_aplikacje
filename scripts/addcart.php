<?php
/** @var mysqli $conn */
/** @var int $product_id */
/** @var int $size */
session_start();
//unset($_SESSION["cart"]);
//print_r($_POST);
$required_fields = ["size", "product_id"];
$translation_arr = ["size" => "Rozmiar", "product_id" => "Identyfikator Produktu"];

foreach ($required_fields as $value)  //Have all the required fields made it?
{
  if (empty($_POST[$value])) {
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
$stmt_sid = $conn->prepare("SELECT size_id FROM sizes WHERE size_id=?");
$stmt_sid->bind_param("i", $_POST["size"]);
$stmt_sid->execute();
$result_sid = $stmt_sid->get_result();
$stmt_pid = $conn->prepare("SELECT product_id FROM products WHERE product_id=?");
$stmt_pid->bind_param("i", $_POST["product_id"]);
$stmt_pid->execute();
$result_pid = $stmt_pid->get_result();

if($result_sid->num_rows == 0 || $result_pid->num_rows == 0)  //Do the required fields make sense?
{
  $_SESSION["error"] = "Wystąpił błąd. Skontaktuj się z administratorem";
  header("location: ../pages/index.php");
  exit();
}
foreach ($_POST as $key => $value)  //Change $_POST[name] to $name for simplicity
{
  $$key = $_POST["$key"];
  //echo $$key;
}

if (!isset($_SESSION["cart"]))
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
  else
  {
    $_SESSION["error"] = "Wybranego produktu nie ma już w magazynie. Przepraszamy za niedogodności.";
    echo "<script>history.back();</script>";
    exit();
  }
}
else
{
  $stmt = $conn->prepare("SELECT item_id FROM warehouse WHERE product_id=? AND size=?;");
  $stmt->bind_param("ii", $product_id, $size);
  $stmt->execute();
  $result = $stmt->get_result();
  if(isset($_SESSION["cart"][$product_id]))
  {
    $count = array_count_values($_SESSION["cart"][$product_id]);
    if(isset($count[$size]))
    {
      if($result->num_rows != 0 && $count[$size]+1 <= $result->num_rows)
      {
        if(isset($_SESSION["cart"][$product_id]))
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
        $_SESSION["success"] = "Produkt został dodany do koszyka!";
        exit();
      }
      else
      {
        $_SESSION["error"] = "Brak dodatkowych sztuk w magazynie. Przepraszamy za niedogodności.";
        echo "<script>history.back();</script>";
        exit();
      }
    }
    else
    {
      if($result->num_rows != 0)
      {
        if(isset($_SESSION["cart"][$product_id]))
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
        $_SESSION["success"] = "Produkt został dodany do koszyka!";
        exit();
      }
      else
      {
        $_SESSION["error"] = "Wybranego produktu nie ma już w magazynie. Przepraszamy za niedogodności.";
        echo "<script>history.back();</script>";
        exit();
      }
    }
  }
  else
  {
    if($result->num_rows != 0)
    {
      if(isset($_SESSION["cart"][$product_id]))
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
      $_SESSION["success"] = "Produkt został dodany do koszyka!";
      exit();
    }
    else
    {
      $_SESSION["error"] = "Wybranego produktu nie ma już w magazynie. Przepraszamy za niedogodności.";
      echo "<script>history.back();</script>";
      exit();
    }
  }
}
//header("location: ../pages/index.php");
?>
