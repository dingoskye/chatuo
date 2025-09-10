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
    <link rel="stylesheet" href="css/style.css">
    <title>Homepage Electroworld</title>
</head>
<body>
<nav>
    <img src="images/logo.png" alt="logo electroworld">
    <a href="">Categorieën</a>
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
    <h1>Electroworld</h1>
    <p>Uw speciaalzaak in Electronica</p>
</header>
<main>
        <section>
            <h2>Huishouden</h2>
            <div class="categorie-container">
                <article class="product-card">
                    <img src="images/laundrylion.webp" alt="Laundry lion">
                    <h3>Wasmachines</h3>
                    <p>Bekijk ons aanbod wasmachines.</p>
                </article>
                <article class="product-card">
                    <img src="images/clean_square_-_b2s_offer_henry_wash_50_off_1.png" alt="Henry stofzuiger">
                    <h3>Stofzuigers</h3>
                    <p>Krachtige en betrouwbare stofzuigers.</p>
                </article>
            </div>
        </section>
        <section>
            <h2>Computer</h2>
            <div class="categorie-container">
                <article class="product-card">
                    <img src="images/seniorentablet.png" alt="Seniorentablet">
                    <h3>Tablets</h3>
                    <p>Tablets voor jong en oud.</p>
                </article>
                <article class="product-card">
                    <img src="images/01.-MSI-Vector-16-HX-A14VHG-671NL.png" alt="MSI laptop">
                    <h3>Laptops</h3>
                    <p>Vind de perfecte laptop voor jou.</p>
                </article>
            </div>
        </section>
</main>
<footer>
    <p>©Electroworld</p>
</footer>
</body>
</html>