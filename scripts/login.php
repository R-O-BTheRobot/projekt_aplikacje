<?php
/** @var mysqli $conn */
/** @var array $rq_field_err */

header("location: ../pages/login.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  $translation_arr = ["mail" => "E-mail", "pass" => "Hasło"];
  foreach ($_POST as $key => $value)
  {
    if (empty($value))
    {
      $rq_field_err[] = "Pole <b>$translation_arr[$key]</b> musi być wypełnione!";
    }
  }
  $login = $_POST["mail"];
  $pass = $_POST["pass"];

  if (!empty($rq_field_err))
  {
    $_SESSION["error"] = implode("<br>", $rq_field_err);
    echo "<script>history.back();</script>";
    exit();
  }

  require_once "../scripts/dbconnect.php";
  $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
  $stmt->bind_param("s", $login);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows != 0)
  {
    $user = $result->fetch_assoc();
    if (password_verify($pass, $user["password"]))
    {
      $_SESSION["loggedIn"]["firstName"] = $user["firstName"];
      $_SESSION["loggedIn"]["lastName"] = $user["lastName"];
      $_SESSION["loggedIn"]["role_id"] = $user["role_id"];
      session_regenerate_id();
      $_SESSION["loggedIn"]["session_id"] = session_id();
      $_SESSION["success"] = "Pomyślnie zalogowano!";
      header("location: ../pages/index.php");
      exit();
    }
    else
    {
      $_SESSION["error"] = "Błędny login lub hasło!";
      echo "<script>history.back();</script>";
      exit();
    }
  }
  else
  {
    $_SESSION["error"] = "Błędny login lub hasło!";
    echo "<script>history.back();</script>";
    exit();
  }
}
