<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="admin-sidebar">
    <div class="admin-sidebar-header">
        <i class="fas fa-bed" style="color: var(--primary-color);"></i> HostelBook Admin
    </div>
    <div class="admin-nav">
        <a href="<?= BASE_URL ?>/admin/dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="<?= BASE_URL ?>/admin/manage_rooms.php" class="<?= in_array($current_page, ['manage_rooms.php', 'add_room.php', 'edit_room.php']) ? 'active' : '' ?>">
            <i class="fas fa-door-open"></i> Manage Rooms
        </a>
        <a href="<?= BASE_URL ?>/admin/manage_bookings.php" class="<?= $current_page == 'manage_bookings.php' ? 'active' : '' ?>">
            <i class="fas fa-calendar-check"></i> Manage Bookings
        </a>
        <a href="<?= BASE_URL ?>/admin/manage_users.php" class="<?= $current_page == 'manage_users.php' ? 'active' : '' ?>">
            <i class="fas fa-users"></i> Manage Users
        </a>
        <a href="<?= BASE_URL ?>/admin/manage_payments.php" class="<?= $current_page == 'manage_payments.php' ? 'active' : '' ?>">
            <i class="fas fa-money-bill-wave"></i> Payments
        </a>
        <a href="<?= BASE_URL ?>/admin/contact_messages.php" class="<?= $current_page == 'contact_messages.php' ? 'active' : '' ?>">
            <i class="fas fa-envelope"></i> Contact Messages
        </a>
        <a href="<?= BASE_URL ?>/admin/reports.php" class="<?= $current_page == 'reports.php' ? 'active' : '' ?>">
            <i class="fas fa-chart-pie"></i> Reports
        </a>
        <a href="<?= BASE_URL ?>/index.php" target="_blank" style="margin-top: 30px; border-top: 1px solid #374151;">
            <i class="fas fa-external-link-alt"></i> View Site
        </a>
    </div>
</div>
