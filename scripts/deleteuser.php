<?php
  session_start();
  /** @var mysqli $conn*/
  header("location: ../pages/adminpanel.php");

  require_once "./dbconnect.php";

  if(!isset($_SESSION["loggedIn"]["role_ID"]) || $_SESSION["loggedIn"]["role_ID"] != 3) //Is the correct user type accessing this script?
  {
    header("location: ../pages/index.php");
    exit();
  }

  if(!isset($_GET["userid"])) //Check if the User ID has been passed
  {
    header("location: ../pages/index.php");
    exit();
  }

  $sqlsel = "SELECT firstName, lastName FROM users WHERE id=$_GET[userid]";
  $resultsel = $conn->query($sqlsel);

  if ($resultsel->num_rows == 1)  //Check if the User ID exists
  {
    foreach ($user = $resultsel->fetch_assoc() as $key => $value)
    {
      $$key = $user[$key];
    }
  }
  else
  {
    $_SESSION["error"] = "Coś poszło nie tak. Czy użytkownik nie został już usunięty?";
    exit();
  }

  if($_SESSION["loggedIn"]["user_ID"] == $_GET["userid"]) //Check if the currently logged in user isn't trying to delete themselves
  {
    $_SESSION["error"] = "Nie możesz usunąć swojego konta!";
    exit();
  }

  $sql = "DELETE FROM users WHERE id = $_GET[userid]";
  $conn->query($sql);
  if ($conn->affected_rows == 1)  //Check if SQL executed properly and affected 1 row
  {
    $_SESSION["success"] = "Pomyślnie usunięto użytkownika $firstName $lastName"; //Announce success
  }
  else  //Throw an error
  {
    $_SESSION["error"] = "Coś poszło nie tak. Skontaktuj się z administratorem.";
  }
?>
