<?php
  $conn = mysqli_connect("localhost", "root", "admin", "sklep_xyz", "3306");
  echo "db" . $conn->connect_errno;
?>
