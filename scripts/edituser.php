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
    if(!isset($_POST["user_ID"]))
    {
      header("location: ../pages/index.php");
      exit();
    }

    $sql = "SELECT id FROM users WHERE id=$_POST[user_ID]";
    $result = $conn->query($sql);
    if ($result->num_rows != 1)
    {
      $_SESSION["error"] = "Identyfikator Użytkownika jest błędny. Skontaktuj się z administratorem.";
      echo "<script>history.back();</script>";
      exit();
    }

    $translation_arr = ["user_ID" => "Identyfikator Użytkownika", "firstName" => "Imię", "lastName" => "Nazwisko", "email" => "E-mail", "role" => "Typ"];

    foreach($_POST as $key => $value)
    {
      if($key != "newPass")
        if(empty($value))
          $empty_fields[] = "Pole <b>$translation_arr[$key]</b> jest puste.";
    }

    if (!empty($empty_fields))
    {
      $_SESSION["error"] = implode("<br>", $empty_fields);
      echo "<script>history.back();</script>";
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
