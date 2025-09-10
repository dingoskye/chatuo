<?php
/** @var mysqli $db */
require_once 'include/database.php';
session_start();
$login = false;


if (isset($_SESSION['Login'])) {
    header('location: logout.php');
    exit;
}

if (isset($_POST['submit'])) {

    $firstName = mysqli_escape_string($db, $_POST['firstName']);
    $lastName = mysqli_escape_string($db, $_POST['lastName']);
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = mysqli_escape_string($db, $_POST['password']);
    $dubblePassword = mysqli_escape_string($db, $_POST['passwordCheck']);

    $errors = [];
    if (strlen($firstName) > 50) {
        $errors['firstName'] = 'Uw voornaam mag niet meer dan 50 letters';
    }
    if ($firstName == '') {
        $errors['firstName'] = 'Uw voornaam is verplicht';
    }
    if ($lastName == '') {
        $errors['lastName'] = 'Uw achternaam is verplicht';
    }
    if (strlen($lastName) > 50) {
        $errors['lastName'] = 'Uw achternaam mag niet meer dan 50 letters';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Dit email bestaat niet';
    }
    if ($email == '') {
        $errors['email'] = 'Uw email is verplicht';
    }
    if ($dubblePassword == '') {
        $errors['passwordCheck'] = 'U moet uw wachtwoord opnieuw invullen!';
    }
    if ($password !== $dubblePassword) {
        $errors['dubblePassword'] = 'Uw wachtwoord komt niet overeen!';
    }
    if ($password == '') {
        $errors['password'] = 'Uw wachtwoord is verplicht';
    }
    if (empty($_POST['conditions'])) {
        $errors['conditions'] = 'U moet de voorwaardes accepteren!! ';
    }

    $sql = " SELECT `email`  FROM users WHERE `email` = '$email'";
    $result = mysqli_query($db, $sql)
    or die('Error ' . mysqli_error($db) . 'with query ' . $sql);
    if (mysqli_num_rows($result) > 0) {
        $errors['dubbleMail'] = 'Dit email is al gebruikt';
    }

    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $query = "
    INSERT INTO `users`(`first_name`, `last_name`, `email`, `password`)
    VALUES ('$firstName','$lastName','$email', '$password', 0)
    ";
        $result = mysqli_query($db, $query)
        or die('Error ' . mysqli_error($db) . 'with query ' . $query);

        header('location: login.php');
        exit;
    };
}

mysqli_close($db);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registreer</title>
</head>
<body>
<nav>

</nav>
<header>
    <h1>Registreer</h1>
</header>
<main>
    <form action="" method="post">
        <div class="container">
            <label for="firstName">Voornaam</label>
        </div>
        <input id="firstName" type="text" name="firstName" value="<?= htmlentities($firstName ?? '') ?>">
        <p class="error">
            <?= $errors['firstName'] ?? '' ?>
        </p>
        <div class="container">
            <label for="lastName">Achternaam</label>
        </div>
        <input id="lastName" type="text" name="lastName" value="<?= htmlentities($lastName ?? '') ?>">
        <p class="error">
            <?= $errors['lastName'] ?? '' ?>
        </p>
        <div class="container">
            <label for="email">E-mail</label>
        </div>
        <input id="email" type="email" name="email" value="<?= htmlentities($email ?? '') ?>">
        <p class="error">
            <?= $errors['email'] ?? '' ?>
            <?= $errors['dubbleMail'] ?? '' ?>
        </p>
        <div class="container">
            <label for="password">Wachtwoord</label>
        </div>
        <input id="password" type="password" name="password">
        <p class="error">
            <?= $errors['password'] ?? '' ?>
        </p>
        <div class="container">
            <label for="passwordCheck">Herhaal wachtwoord</label>
        </div>
        <input id="passwordCheck" type="password" name="passwordCheck">

        <p class="error">
            <?= $errors['dubblePassword'] ?? '' ?>

            <?= $errors['passwordCheck'] ?? '' ?>
        </p>
        <div class="container">
            <input id="conditions" type="checkbox" name="conditions">

            <label for="conditions">Ik accepteer de voorwaarden</label>
        </div>
        <p class="error">
            <?= $errors['conditions'] ?? '' ?>
        </p>
        <div class="registerStyle">
            <button class="button" type="submit" name="submit">Registreren</button>
        </div>
    </form>
</main>
<footer>
    <p>©Electroworld</p>
</footer>
</body>
</html>
