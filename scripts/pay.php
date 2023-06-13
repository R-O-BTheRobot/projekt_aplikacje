<?php
/** @var mysqli $conn */
/** @var int $product_id */
/** @var int $size */
session_start();

$cart_err = 0;

require_once "./dbconnect.php";

foreach($_SESSION["cart"] as $product_id => $product_arr)
{
  foreach(array_count_values($_SESSION["cart"][$product_id]) as $size_id => $num)
  {
    //Do the present fields make sense?
    $stmt_sid = $conn->prepare("SELECT size FROM sizes WHERE size_id=?");
    $stmt_sid->bind_param("i", $size_id);
    $stmt_sid->execute();
    $result_sid = $stmt_sid->get_result();

    $stmt_pid = $conn->prepare("SELECT tytul FROM products WHERE product_id=?");
    $stmt_pid->bind_param("i", $product_id);
    $stmt_pid->execute();
    $result_pid = $stmt_pid->get_result();

    if($result_sid->num_rows == 0)
    {
      $cart_err = 1; //No such sizes are sold anymore (unlikely)
      foreach($_SESSION["cart"][$product_id] as $key => $value) {
        if ($value == $size_id)
        {
          if(count($_SESSION["cart"][$product_id]) == 1)
          {
            if(count($_SESSION["cart"]) == 1)
            {
              unset($_SESSION["cart"]);
              break;
            }
            else
              unset($_SESSION["cart"][$product_id][$key]);
          }
          else
            unset($_SESSION["cart"][$product_id][$key]);
        }
      }
    }
    elseif($result_pid->num_rows == 0)
    {
      $cart_err = 1; //No such products are sold anymore
      if(count($_SESSION["cart"]) == 1)
        unset($_SESSION["cart"]);
      else
        unset($_SESSION["cart"][$product_id]);
    }
    else
    {
      $stmt = $conn->prepare("SELECT count FROM warehouse WHERE product_id=? AND size=?");
      $stmt->bind_param("ii", $product_id, $size_id);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows != 0)
        $count = $result->fetch_assoc()["count"];

      if($result->num_rows == 0) //Product of this size no longer sold
      {
        $cart_err = 1;
        foreach($_SESSION["cart"][$product_id] as $key => $value) {
          if ($value == $size_id)
          {
            if(count($_SESSION["cart"][$product_id]) == 1)
            {
              if(count($_SESSION["cart"]) == 1)
              {
                unset($_SESSION["cart"]);
                break;
              }
              else
                unset($_SESSION["cart"][$product_id][$key]);
            }
            else
              unset($_SESSION["cart"][$product_id][$key]);
          }
        }
      }
      elseif($count == 0) //No more product of this size
      {
        $cart_err = 1;
        foreach($_SESSION["cart"][$product_id] as $key => $value) {
          if ($value == $size_id)
          {
            if(count($_SESSION["cart"][$product_id]) == 1)
            {
              if(count($_SESSION["cart"]) == 1)
              {
                unset($_SESSION["cart"]);
                break;
              }
              else
                unset($_SESSION["cart"][$product_id]);
            }
            else
              unset($_SESSION["cart"][$product_id][$key]);
          }
        }
      }
      elseif($count < $num)
      {
        $cart_err = 1;
        $diff = $num - $count;
        foreach($_SESSION["cart"][$product_id] as $key => $value)
        {
          if ($value == $size_id)
          {
            unset($_SESSION["cart"][$product_id][$key]);
            $diff--;
          }
          if ($diff==0)
            break;
        }
      }
      else
      {
        //echo "$product_id - OK<br>";
      }
    }
  }
}

if ($cart_err!=0)
{
  $_SESSION["error"] = "Niestety, niektóre produkty z twojego koszyka są już niedostępne.<br>"
    . "Zaktualizowaliśmy twój koszyk. Potwierdź nowe zamówienie.";
  echo "Cart error<br>";
  //print_r($_SESSION["cart"]);
  exit();
}
else
{
  foreach($_SESSION["cart"] as $product_id => $product_arr)
  {
    foreach(array_count_values($_SESSION["cart"][$product_id]) as $size_id => $num)
    {
      $stmt = $conn->prepare("SELECT item_id, count FROM warehouse WHERE product_id=? AND size=?");
      $stmt->bind_param("ii", $product_id, $size_id);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows != 0)
      {
        $wh = $result->fetch_assoc();
        $count = $wh["count"];
        $item_id = $wh["item_id"];
      }
      $ncount = $count-$num;
      //echo "$product_id - $count, $num, $ncount<br>";
      $stmt = $conn->prepare("UPDATE `warehouse` SET `count` = ? WHERE `warehouse`.`item_id` = ?");
      $stmt->bind_param("ii", $ncount, $item_id);
      $stmt->execute();
      if($stmt->affected_rows==0)
      {
        echo "Couldn't edit DB...";
        echo "<br>$item_id, $ncount";
        exit();
      }
    }
  }
  unset($_SESSION["cart"]);
  header("location: ../pages/thankyou.php");
  echo "All OK!";
  exit();
}




//header("location: ../pages/index.php");
?>
