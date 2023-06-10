<?php
/** @var mysqli $conn */
/** @var string $mail */
/** @var string $firstName */
/** @var string $lastName */
/** @var array $rq_field_err */
/** @var array $filter_err */
/** @var PHPMailer $phpmailer */

header("location: ../pages/register.php");
$_SESSION["error"] = "Nie dodano użytkownika. Skontaktuj się z administratorem.";

function sanitizeInput($input):string
{
  return htmlentities(stripslashes(trim($input)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  session_start();

  $required_fields = ["firstName", "lastName", "mail", "pass1", "pass2"];

  $translation_arr = ["firstName" => "Imię", "lastName" => "Nazwisko", "mail" => "E-mail", "pass1" => "Hasło", "pass2" => "Powtórz Hasło"];

  foreach ($_POST as $key => $value)
  {
    if ($key != "pass1" && $key != "pass2")
      $$key = sanitizeInput($_POST["$key"]);
  }

  foreach ($required_fields as $value)
  {
    if (empty($_POST[$value])) {
      $rq_field_err[] = "Pole <b>$translation_arr[$value]</b> jest wymagane";
    }
  }

  if (!empty($rq_field_err))
  {
    $_SESSION["error"] = implode("<br>", $rq_field_err);
    echo "<script>history.back();</script>";
    exit();
  }

  if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
    $filter_err[] = "Nieprawidłowy adres poczty elektronicznej!";

  if (!isset($_POST["terms"]))
    $filter_err[] = "Zaakceptuj politykę prywatności!";

  if ($_POST["pass1"] != $_POST["pass2"])
    $filter_err[] = "Hasła są różne!";

  //Regex for checking password security, based off of https://github.com/szyper's REGEX code, but improved to accept _ as a special character and limited to 32 chars
  if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d\s])\S{8,32}$/', $_POST["pass1"]))
    $filter_err[] = "Hasło nie spełnia wymagań!";

  require_once "./dbconnect.php";
  $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
  $stmt->bind_param('s', $_mail);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows != 0)
  {
    $filter_err[] = "Podany email jest zajęty!";
  }

  if (!empty($filter_err))
  {
    $_SESSION["error"] = implode("<br>", $filter_err);
    echo "<script>history.back();</script>";
    exit();
  }

  $stmt = $conn->prepare("INSERT INTO `users` (`email`, `firstName`, `lastName`, `password`) VALUES (?, ?, ?, ?);");
  $pass = password_hash($_POST["pass1"], PASSWORD_ARGON2ID);
  $activate_id = substr($pass, 31, 10);
  $stmt->bind_param('ssss', $mail, $firstName, $lastName, $pass);
  $stmt->execute();

  if ($stmt->affected_rows == 1)
  {
    require_once "../scripts/mail.php";
    try {
      //Recipients
      $phpmailer->setFrom('no-reply@robtherobot.space', 'No-reply: SklepXYZ');
      $phpmailer->addAddress($mail);

      //Content
      $phpmailer->isHTML(true);                                  //Set email format to HTML
      $phpmailer->Subject = "Aktywuj swoje konto w SklepieXYZ";
      $phpmailer->Body    = "Poniżej znajdziesz link aktywacyjny dla swojego konta:<br>"
        ."<a href='https://phpfinal.robtherobot.space/pages/activate.php?id=$activate_id'>Klik!</a><br>"
        ."Życzymy miłych zakupów! <br>Zespół SklepuXYZ";
      $phpmailer->AltBody = "Poniżej znajdziesz link aktywacyjny dla swojego konta: https://phpfinal.robtherobot.space/pages/activate.php?id=$activate_id";

      $phpmailer->send();
      $_SESSION["success"] = "Prawidowo dodano użytkownika $_POST[firstName] $_POST[lastName]!<br>"
        . "Wysłaliśmy email weryfikacyjny na twój adres email!";
      unset($_SESSION["error"]);
    } catch (Exception $e) {
      $_SESSION["error"] = "Nie wysłano wiadomości email przez błąd: {$phpmailer->ErrorInfo}";
    }
    header("location: ../pages");
    exit();
  }
  else
  {
    $_SESSION["error"] = "Nie dodano użytkownika. Skontaktuj się z administratorem.";
  }
}
