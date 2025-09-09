<?php
require __DIR__ . '/widget.php';
session_start();

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
    <?php if (isset($_SESSION['Login'])) { ?>
        <a href="profile.php">Profiel</a>
    <?php } ?>
    <?php if (isset($_SESSION['Login'])) { ?>
        <a href="logout.php">Logout</a>
    <?php } else { ?>
        <a href="login.php">Login</a>
    <?php } ?>
</nav>
<header>

</header>
<main>
    
</main>
<footer>

</footer>
</body>
</html>