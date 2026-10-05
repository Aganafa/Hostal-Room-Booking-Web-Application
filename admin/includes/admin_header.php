<?php
// admin/includes/admin_header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAdminLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hostel Room Booking</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #F9FAFB; background-image: none; }
        .admin-layout { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 260px; background: #111827; color: white; flex-shrink: 0; display: flex; flex-direction: column; }
        .admin-sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid #374151; font-size: 1.2rem; font-weight: bold; }
        .admin-nav { flex-grow: 1; padding: 20px 0; }
        .admin-nav a { display: block; padding: 12px 20px; color: #D1D5DB; transition: all 0.3s; }
        .admin-nav a:hover, .admin-nav a.active { background: #1F2937; color: white; border-left: 4px solid var(--primary-color); }
        .admin-nav i { width: 25px; text-align: center; margin-right: 10px; }
        .admin-main { flex-grow: 1; display: flex; flex-direction: column; }
        .admin-topbar { height: 70px; background: white; border-bottom: 1px solid #E5E7EB; display: flex; align-items: center; justify-content: space-between; padding: 0 30px; }
        .admin-content { padding: 30px; flex-grow: 1; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php require_once __DIR__ . '/admin_sidebar.php'; ?>
        <div class="admin-main">
            <div class="admin-topbar">
                <h2 style="font-size: 1.25rem;">Admin Panel</h2>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <span style="font-weight: 500;">Hello, <?= htmlspecialchars($_SESSION['admin_username']) ?></span>
                    <a href="<?= BASE_URL ?>/admin/logout.php" class="btn btn-danger" style="padding: 6px 15px;">Logout</a>
                </div>
            </div>
            <div class="admin-content">
