<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Room Booking</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="<?= BASE_URL ?>/index.php" class="navbar-brand">
                <i class="fas fa-bed"></i> HostelBook
            </a>
            <div class="nav-links">
                <a href="<?= BASE_URL ?>/index.php">Home</a>
                <a href="<?= BASE_URL ?>/rooms.php">Rooms</a>
                <a href="<?= BASE_URL ?>/about.php">About</a>
                <a href="<?= BASE_URL ?>/contact.php">Contact</a>
                <a href="<?= BASE_URL ?>/faq.php">FAQ</a>
                <?php if (isUserLoggedIn()): ?>
                    <a href="<?= BASE_URL ?>/user/dashboard.php">Dashboard</a>
                    <a href="<?= BASE_URL ?>/logout.php" class="btn btn-danger">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login.php" class="btn btn-secondary">Login</a>
                    <a href="<?= BASE_URL ?>/register.php" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <main>
        <div class="container">
