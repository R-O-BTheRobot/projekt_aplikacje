<?php
  session_start();
  /** @var mysqli $conn */
  /** @var array $empty_fields */
  /** @var array $filter_err */
  require_once "./dbconnect.php";
  print_r($_POST);

  function sanitizeInput($input):string
  {
    return htmlentities(stripslashes(trim($input)));
  }

  if($_SERVER["REQUEST_METHOD"] == "POST")
  {
    if(!isset($_POST["product_id"]))
    {
      header("location: ../pages/index.php");
      exit();
    }

    $sql = "SELECT product_id FROM products WHERE product_id=$_POST[product_id]";
    $result = $conn->query($sql);
    if ($result->num_rows != 1)
    {
      $_SESSION["error"] = "Identyfikator Produktu jest błędny. Skontaktuj się z administratorem.";
      echo "<script>history.back();</script>";
      exit();
    }

    $translation_arr = ["product_id" => "Identyfikator Produktu", "tytul" => "Nazwa", "opis_short" => "Krótki opis", "opis_long" => "Długi opis", "type" => "Typ", "picture_link" => "Główne zdjęcie", "cena" => "Cena"];

    foreach($_POST as $key => $value)
    {
      if($key != "picture_link")
        if(empty($value))
          $empty_fields[] = "Pole <b>$translation_arr[$key]</b> jest puste.";
    }

    if (!empty($empty_fields))
    {
      $_SESSION["error"] = implode("<br>", $empty_fields);
      print_r($_POST);
      //echo "<script>history.back();</script>";
      exit();
    }

    foreach ($_POST as $key => $value)
    {
      if ($key != "newPass")
        $$key = sanitizeInput($_POST["$key"]);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      $filter_err[] = "Nieprawidłowy adres poczty elektronicznej!";

    if(!empty($_POST["newPass"]))
      if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d\s])\S{8,32}$/', $_POST["newPass"]))
        $filter_err[] = "Hasło nie spełnia wymagań!";

    if (!empty($filter_err))
    {
      $_SESSION["error"] = implode("<br>", $filter_err);
      echo "<script>history.back();</script>";
      exit();
    }

    if(!empty($_POST["newPass"]))
    {
      $pass = password_hash($_POST["newPass"], PASSWORD_ARGON2ID);
      $stmt = $conn->prepare("UPDATE `users` SET `email`= ?, `firstName` = ?, `lastName` = ?, `role_id`=?, `password` = ? WHERE `id` = $_POST[user_ID];");
      $stmt->bind_param('sssis', $email, $firstName, $lastName, $role, $pass);
      $stmt->execute();
    }
    else
    {
      $stmt = $conn->prepare("UPDATE `users` SET `email`= ?, `firstName` = ?, `lastName` = ?, `role_id`=? WHERE `id` = $_POST[user_ID];");
      $stmt->bind_param('sssi', $email, $firstName, $lastName, $role);
      $stmt->execute();
    }
    $_SESSION["success"] = "Prawidłowo zaktualizowano dane użytkownika $firstName $lastName";
    echo "<script>history.back();</script>";
  }


  //Add relevant checks regarding if the data input is correct, especially with the optional new password
  //Check if any changes happened AT ALL, if not then header back to /pages/edituser.php with a
  //$_SESSION["success"] message of "no changes made"
?>
