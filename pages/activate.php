<?php
  /**@var mysqli $conn*/
  //header("location: ./index.php");

  if(isset($_GET["id"]))
  {
    require_once "../scripts/dbconnect.php";
    $sql = "SELECT id, activated FROM users WHERE password LIKE '%$_GET[id]%'";
    $result = $conn->query($sql);
    if ($result->num_rows==1)
    {
      $id = $result->fetch_assoc()["id"];
      $stmt = $conn->prepare("UPDATE users SET activated=1 WHERE id=?");
      $stmt->execute($id);
      if ($stmt->affected_rows == 1)
      {
        header("location: ./index.php?activated=1");
      }
      else
      {
        echo "There was an error.";
        exit();
      }
    }
  }
?>
