<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar glass-panel" style="padding: 20px;">
    <h3 style="margin-bottom: 20px; color: var(--primary-color); border-bottom: 1px solid var(--glass-border); padding-bottom: 10px;">User Menu</h3>
    <ul>
        <li><a href="<?= BASE_URL ?>/user/dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>"><i class="fas fa-home" style="margin-right: 10px;"></i> Dashboard</a></li>
        <li><a href="<?= BASE_URL ?>/user/profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>"><i class="fas fa-user" style="margin-right: 10px;"></i> My Profile</a></li>
        <li><a href="<?= BASE_URL ?>/user/booking-history.php" class="<?= $current_page == 'booking-history.php' ? 'active' : '' ?>"><i class="fas fa-list-alt" style="margin-right: 10px;"></i> Booking History</a></li>
        <li><a href="<?= BASE_URL ?>/user/change-password.php" class="<?= $current_page == 'change-password.php' ? 'active' : '' ?>"><i class="fas fa-key" style="margin-right: 10px;"></i> Change Password</a></li>
        <li><a href="<?= BASE_URL ?>/logout.php" style="color: var(--danger);"><i class="fas fa-sign-out-alt" style="margin-right: 10px;"></i> Logout</a></li>
    </ul>
</div>
