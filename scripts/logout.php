<?php
session_start();
//echo session_status(); //2
session_destroy();
//echo $_SESSION["logged"]["firstName"];
//echo session_status(); //1
header("location: ../pages/index.php?logout=1");
