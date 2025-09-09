<?php

/** @var mysqli $db */
/** @var mysqli $reservations */
session_start();

if (!isset($_SESSION['Login'])) {
    header('location: index.php');
    exit;
}

require_once 'include/database.php';


$firstName = $_SESSION['firstName'];
$email = $_SESSION['email'];
$id = $_SESSION['id'];


$query = " 
SELECT * FROM `users` WHERE `email` = '$email'
";
$result = mysqli_query($db, $query)
or die('Error: ' . mysqli_error($db) . 'with query ' . $query);

$users = [];

$row = mysqli_fetch_assoc($result);

$users = $row;

mysqli_close($db);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<nav>

</nav>
<header>
    <h1> Welkom <?= $firstName ?></h1>
</header>
<main>
    <div class="profileText">
        <h2>Uw gegevens</h2>
        <div>
            <h3>Voornaam:</h3>
            <p>
                <?= $users['first_name'] ?>
            </p>
        </div>
        <div>
            <h3>Achternaam:</h3>
            <p>
                <?= $users['last_name'] ?>
            </p>
        </div>
        <div>
            <h3>Email:</h3>
            <p>
                <?= $users['email'] ?>
            </p>
        </div>
    </div>
</main>
<footer>

</footer>
</body>
</html>

