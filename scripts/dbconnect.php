<?php
  $conn = mysqli_connect("127.0.0.1", "root", "admin", "sklep_xyz", "3306");
  echo "db" . $conn->connect_errno;
?>
