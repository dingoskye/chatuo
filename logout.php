<?php
/** @var mysqli $db */
session_start();
if (isset($_SESSION['Login'])) {
    require_once 'include/database.php';
    session_destroy();
    header('location: index.php');
    exit;
} else {
    header('location: index.php');
    exit;
}
?>