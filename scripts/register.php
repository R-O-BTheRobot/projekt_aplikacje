<?php
function sanitizeInput($input){
  $input = htmlentities(stripslashes(trim($input)));
  return $input;
}



if ($_SERVER["REQUEST_METHOD"] == "POST"){


  session_start();

  $required_fields = ["firstName", "lastName", "email1",  "pass1","pass2"];

  $errors = [];

  foreach ($required_fields as $value){
    if (empty($_POST[$value])){
      //$_SESSION["error"] = "Wypełnij wszystkie pola!";
      $errors[] = "Pole <b>$value</b> jest wymagane";
      //echo "<script>history.back();</script>";
      //exit();
    }
  }

  if (!empty($errors)){
    //print_r($errors);
    $_SESSION["error"] = implode("<br>", $errors);
    echo "<script>history.back();</script>";
    exit();
  }


    if (!filter_var($_POST["email1"], FILTER_VALIDATE_EMAIL)){
      $errors[] = "To nie jest adres poczty elektronicznej!";
    }





    if (!filter_var($_POST["email1"], FILTER_VALIDATE_EMAIL)){
      $errors[] = "To nie jest adres poczty elektronicznej!";
    }
  }

  if ($_POST["pass1"] != $_POST["pass2"])
    $errors[] = "Hasła są różne!";



  if (!isset($_POST["terms"]))
    $errors[] = "Zaznacz regulamin!";

  if (!empty($errors)){
    $_SESSION["error"] = implode("<br>", $errors);
    echo "<script>history.back();</script>";
    exit();
  }

  if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\w\d\s])\S{8,}$/', $_POST["pass1"])) {
    $_SESSION["error"] = "Hasło nie spełnia wymagań!";
    echo "<script>history.back();</script>";
    exit();
  }

  foreach ($_POST as $key => $value){
    if ($key != "pass1" && $key != "pass2")
      $$key = sanitizeInput($_POST["$key"]);
  }

  require_once "./connect.php";
  $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
  $stmt->bind_param('s', $_POST["email1"]);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows != 0){
    $_SESSION["error"] = "Podany email jest zajęty!";
    echo "<script>history.back();</script>";
    exit();
  }


  $stmt = $conn->prepare("INSERT INTO `users` (`email`, `firstName`, `lastName`, `password`) VALUES (?, ?, ?, ?);");

  $pass = password_hash($_POST["pass1"], PASSWORD_ARGON2ID);




  $stmt->bind_param('ssss', $email1,  $firstName, $lastName, $pass);

  $stmt->execute();

  if ($stmt->affected_rows == 1){
    $_SESSION["success"] = "Prawidowo dodano użytkownika $_POST[firstName] $_POST[lastName]";
    header("location: ../pages");
    exit();
  }else{
    $_SESSION["error"] = "Nie dodano użytkownika";

}

header("location: ../pages/register.php");
