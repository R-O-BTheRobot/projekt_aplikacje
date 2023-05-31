<?php
session_start();
//print_r($_POST);
if ($_SERVER["REQUEST_METHOD"] == "POST"){
  // print_r($_SERVER["REQUEST_METHOD"]);
  $errors = [];
  foreach ($_POST as $key => $value){
    if (empty($value)){
      $errors[] = "Pole <b>$key</b> musi być wypełnione";
    }
  }

  $login = $_POST["email"];
  $pass = $_POST["pass"];



  if (!empty($errors)){
    $error_message = implode("<br>", $errors);
    header("location: ../pages/login.php?error=".urlencode($error_message));
    exit();
  }

  require_once "./connect.php";
  $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
  $stmt->bind_param("s", $login);
  $stmt->execute();
  $result = $stmt->get_result();
  //echo $result->num_rows;

  if ($result->num_rows != 0){
    $user = $result->fetch_assoc();
    //print_r($user);
    //echo $pass;
//		echo $user["password"];
    if (password_verify($pass, $user["password"])){
      $_SESSION["logged"]["firstName"] = $user["firstName"];
      $_SESSION["logged"]["lastName"] = $user["lastName"];
      $_SESSION["logged"]["role_id"] = $user["role_id"];
      //echo session_id();
      session_regenerate_id();
      $_SESSION["logged"]["session_id"] = session_id();
      //print_r($_SESSION["logged"]);
      header("location: ../pages/logged.php"); //index2.html

    }else{
      $_SESSION["error"] = "Błędny login lub hasło!";
      echo "<script>history.back();</script>";
      exit();
    }
  }else{
    $_SESSION["error"] = "Błędny login lub hasło!";
    echo "<script>history.back();</script>";
    exit();
  }

  exit();

}else{
  echo "<script>history.back();</script>";
}
