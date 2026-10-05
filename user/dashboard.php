<?php
require_once __DIR__ . '/../includes/header.php';
requireUserLogin();

$user_id = $_SESSION['user_id'];

// Get recent bookings
$stmt = $conn->prepare("SELECT b.*, r.title, r.room_number FROM bookings b JOIN rooms r ON b.room_id = r.id WHERE b.user_id = ? ORDER BY b.created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_bookings = $stmt->fetchAll();

// Get counts
$stmt = $conn->prepare("SELECT count(*) as total FROM bookings WHERE user_id = ?");
$stmt->execute([$user_id]);
$total_bookings = $stmt->fetch()['total'];

$stmt = $conn->prepare("SELECT count(*) as active FROM bookings WHERE user_id = ? AND booking_status IN ('Pending', 'Approved') AND check_out >= CURDATE()");
$stmt->execute([$user_id]);
$active_bookings = $stmt->fetch()['active'];

$user = getCurrentUser($conn);
?>

<div class="container dashboard-layout">
    <?php require_once __DIR__ . '/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <h2 style="margin-bottom: 20px;">Welcome back, <?= htmlspecialchars($user['name']) ?>!</h2>
        
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 30px;">
            <div class="glass-panel" style="padding: 20px; text-align: center; border-left: 4px solid var(--primary-color);">
                <h3 style="color: var(--text-muted); font-size: 1rem; margin-bottom: 10px;">Total Bookings</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--text-main);"><?= $total_bookings ?></p>
            </div>
            <div class="glass-panel" style="padding: 20px; text-align: center; border-left: 4px solid var(--success);">
                <h3 style="color: var(--text-muted); font-size: 1rem; margin-bottom: 10px;">Active Stays</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--text-main);"><?= $active_bookings ?></p>
            </div>
        </div>

        <div class="glass-panel" style="padding: 20px;">
            <h3 style="margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid var(--glass-border);">Recent Bookings</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_bookings as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['title']) ?> (<?= htmlspecialchars($booking['room_number']) ?>)</td>
                                <td><?= date('M d, Y', strtotime($booking['check_in'])) ?></td>
                                <td><?= date('M d, Y', strtotime($booking['check_out'])) ?></td>
                                <td>
                                    <?php 
                                        $statusClass = 'badge-pending';
                                        if ($booking['booking_status'] == 'Approved') $statusClass = 'badge-approved';
                                        if ($booking['booking_status'] == 'Rejected' || $booking['booking_status'] == 'Cancelled') $statusClass = 'badge-rejected';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= $booking['booking_status'] ?></span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/user/booking-history.php" class="btn btn-secondary" style="padding: 5px 20px; font-size: 0.8rem;">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($recent_bookings)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center;">No bookings found. <a href="<?= BASE_URL ?>/rooms.php">Book a room now</a>.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
