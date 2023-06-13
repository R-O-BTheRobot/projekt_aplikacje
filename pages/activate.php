<?php
  /**@var mysqli $conn*/
  session_start();

  if(isset($_GET["id"]))
  {
    require_once "../scripts/dbconnect.php";
    $sql = "SELECT id, activated FROM users WHERE password LIKE '%$_GET[id]%'";
    $result = $conn->query($sql);
    if ($result->num_rows==1)
    {
      $id = $result->fetch_assoc()["id"];
      $stmt = $conn->prepare("UPDATE users SET activated=1 WHERE id=?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      if ($stmt->affected_rows == 1)
      {
        header("location: ./index.php?activated=1");
        exit();
      }
      else
      {
        header("location: ./index.php");
        $_SESSION["error"] = "Coś poszło nie tak. Skontaktuj się z administratorem";
        exit();
      }
    }
  }
  if(isset($_POST["activate_id"]) && $_SESSION["loggedIn"]["role_ID"]==3)
  {
    require_once "../scripts/dbconnect.php";
    $sql = "SELECT id, activated FROM users WHERE password LIKE '%$_POST[activate_id]%'";
    $result = $conn->query($sql);
    if ($result->num_rows==1)
    {
      $id = $result->fetch_assoc()["id"];
      $stmt = $conn->prepare("UPDATE users SET activated=1 WHERE id=?");
      $stmt->bind_param("i", $id);
      $stmt->execute();
      if ($stmt->affected_rows == 1)
      {
        echo "<script>history.back()</script>";
        exit();
      }
      else
      {
        $_SESSION["error"] = "Coś poszło nie tak. Skontaktuj się z administratorem";
        echo "<script>history.back()</script>";
        exit();
      }
    }
  }
  header("location: ./index.php");
?>
