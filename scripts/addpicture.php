<?php
session_start();
/** @var mysqli $conn*/

require_once "./dbconnect.php";

if($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["product_id"]))
{
  header("location: ../pages/index.php");
  echo "<script>history.back()</script>";
  exit();
}

$sqlsel = "SELECT tytul FROM products WHERE product_id=$_POST[product_id]";
$resultsel = $conn->query($sqlsel);

if ($resultsel->num_rows != 1)
{
  $_SESSION["error"] = "Coś poszło nie tak. ID produktu jest błędne.";
  echo "<script>history.back()</script>";
  exit();
}
else
{
  $prod = $resultsel->fetch_assoc();
}

if(empty($_FILES["secondaryPicture"]["name"]) && empty($_FILES['secondaryPicture']['tmp_name']))
{
  $_SESSION["error"] = "Nie wybrano żadnego zdjęcia!";
  echo "<script>history.back()</script>";
  exit();
}

$target_dir = "../dist/upload/";
$target_file = $target_dir . basename($_FILES["secondaryPicture"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["secondaryPicture"]["tmp_name"]);
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
if ($_FILES["secondaryPicture"]["size"] > 500000) {
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
  if (move_uploaded_file($_FILES["secondaryPicture"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["secondaryPicture"]["name"])). " has been uploaded.";
  } else {
    $file_err[] = "Błąd przesyłania pliku.";
  }
}

if (!empty($file_err))
{
  $_SESSION["error"] = implode("<br>", $file_err);
  echo "<script>history.back()</script>";
  exit();
}

$stmt = $conn->prepare("INSERT INTO `pictures` (`id`, `product_id`, `picture_link`) VALUES (NULL, ?, ?)");
$stmt->bind_param('is', $_POST["product_id"], $target_file);
$stmt->execute();

if ($stmt->affected_rows == 1)
{
  $_SESSION["success"] = "Zdjęcie zostało dodane!";
  echo "<script>history.back()</script>";
  exit();
}
else
{
  $_SESSION["error"] = "Wystąpił błąd. Skontaktuj się z administratorem.";
  echo "<script>history.back()</script>";
  exit();
}

?>
