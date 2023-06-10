<?php
session_start();
/** @var mysqli $conn */
/** @var array $empty_fields */
/** @var array $filter_err */
require_once "./dbconnect.php";
//print_r($_POST);

function sanitizeInput($input):string
{
  return htmlentities(stripslashes(trim($input)));
}

if(!isset($_SESSION["loggedIn"]["role_ID"]) || $_SESSION["loggedIn"]["role_ID"] == 1)
{
  header("location: ../pages/index.php");
  exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST")
{
  $rq_fields = ["tytul", "picture_link", "opis_short", "opis_long", "type", "cena"];
  $translation_arr = ["product_id" => "Identyfikator Produktu", "tytul" => "Nazwa", "opis_short" => "Krótki opis", "opis_long" => "Długi opis", "type" => "Typ", "picture_link" => "Główne zdjęcie", "cena" => "Cena"];

  foreach($rq_fields as $value)
  {
    if($value == "picture_link")
    {
      if(empty($_FILES["picture_link"]["name"]) && empty($_FILES['picture_link']['tmp_name']))
        $empty_fields[] = "Pole <b>$translation_arr[$value]</b> jest puste.";
    }
    else
      if(empty($_POST[$value]))
        $empty_fields[] = "Pole <b>$translation_arr[$value]</b> jest puste.";
  }

  if (!empty($empty_fields))
  {
    $_SESSION["error"] = implode("<br>", $empty_fields);
    echo "<script>history.back();</script>";
    exit();
  }

  foreach ($_POST as $key => $value)
  {
    $$key = sanitizeInput($_POST["$key"]);
  }

  $target_dir = "../dist/upload/";
  $target_file = $target_dir . basename($_FILES["picture_link"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["picture_link"]["tmp_name"]);
    if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } else {
      $file_err[] = "Plik nie jest plikiem obrazu!";
      $uploadOk = 0;
    }
  }

  // Check if file already exists
  if (file_exists($target_file)) {
    $file_err[] = "Plik już istnieje!";
    $uploadOk = 0;
  }

  // Check file size
  if ($_FILES["picture_link"]["size"] > 500000) {
    $file_err[] = "Plik jest zbyt duży!";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "webp" ) {
    $file_err[] = "Plik powinien mieć rozszerzenie JPG, JPEG, PNG bądź WEBP!";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["picture_link"]["tmp_name"], $target_file)) {
      echo "The file ". htmlspecialchars( basename( $_FILES["picture_link"]["name"])). " has been uploaded.";
    } else {
      $file_err[] = "Błąd przesyłania pliku.";
    }
  }

  if (!empty($file_err))
  {
    $_SESSION["error"] = implode("<br>", $file_err);
    echo "<script>history.back();</script>";
    exit();
  }

  $stmt = $conn->prepare("INSERT INTO `products` (`product_id`, `tytul`, `picture_link`, `type_id`, `opis_short`, `cena`, `opis_long`) VALUES (NULL, ?, ?, ?, ?, ?, ?)");
  $stmt->bind_param('ssisds', $tytul, $target_file, $type, $opis_short, $cena, $opis_long);
  $stmt->execute();

  if ($stmt->affected_rows == 1)
  {
    $_SESSION["success"] = "Dodano produkt $tytul!";
  }
  else
  {
    $_SESSION["error"] = "Coś poszło nie tak. Skontaktuj się z administratorem.";
  }
  header("location: ../pages/addproduct.php");
  exit();
}
header("location: ../pages/index.php");
?>
