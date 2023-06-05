<?php
/** @var mysqli $conn */
/** @var int $product_id */
/** @var int $size */
  session_start();
  unset($_SESSION["cart"]);
  //header("location: ../pages/index.php");
  print_r($_POST);
  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
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
      //print_r($result_sid->fetch_assoc());
      //print_r($result_pid->fetch_assoc());
      header("location: ../pages/index.php");
      exit();
    }
    foreach ($_POST as $key => $value)  //Change $_POST[name] to $name for simplicity
    {
      $$key = $_POST["$key"];
      echo $$key;
    }

    if (!isset($_SESSION["cart"]))
    {
      $stmt = $conn->prepare("SELECT item_id FROM warehouse WHERE product_id=? AND size=?;");
      $stmt->bind_param("ii", $_POST["product_id"], $_POST["size"]);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows != 0)  //Is it in the warehouse?
      {
        $_SESSION["cart"] = array($product_id => array($size)); //Add to cart
        echo "<br>";
        //print_r($_SESSION["cart"]);
        echo "<br>";
        //print_r($_SESSION["loggedIn"]);
        $_SESSION["success"] = "Produkt został dodany do koszyka!";
        //echo "<script>history.back();</script>";
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
      //check if all the products are in the warehouse maybe?
      echo "";
      //more checks, like if the product is already in the cart, if so then just add the size to the product's table
      //if not, add a new product etc.
    }
  }
?>
