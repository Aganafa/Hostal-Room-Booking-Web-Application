<?php
require_once __DIR__ . '/includes/admin_header.php';

// Fetch Statistics
$total_rooms = $conn->query("SELECT count(*) FROM rooms")->fetchColumn();
$total_users = $conn->query("SELECT count(*) FROM users")->fetchColumn();
$total_bookings = $conn->query("SELECT count(*) FROM bookings")->fetchColumn();
$total_revenue = $conn->query("SELECT sum(amount) FROM payments WHERE payment_status = 'Paid'")->fetchColumn() ?: 0;

$recent_bookings = $conn->query("SELECT b.*, u.name as user_name, r.room_number FROM bookings b 
                                JOIN users u ON b.user_id = u.id 
                                JOIN rooms r ON b.room_id = r.id 
                                ORDER BY b.created_at DESC LIMIT 5")->fetchAll();
?>

<div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 30px;">
    <div class="glass-panel" style="padding: 20px; background: white;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Total Rooms</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--text-main); margin-top: 5px;"><?= $total_rooms ?></p>
            </div>
            <div style="background: rgba(79, 70, 229, 0.1); padding: 15px; border-radius: 50%;">
                <i class="fas fa-door-open" style="font-size: 1.5rem; color: var(--primary-color);"></i>
            </div>
        </div>
    </div>
    
    <div class="glass-panel" style="padding: 20px; background: white;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Total Users</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--text-main); margin-top: 5px;"><?= $total_users ?></p>
            </div>
            <div style="background: rgba(16, 185, 129, 0.1); padding: 15px; border-radius: 50%;">
                <i class="fas fa-users" style="font-size: 1.5rem; color: var(--success);"></i>
            </div>
        </div>
    </div>

    <div class="glass-panel" style="padding: 20px; background: white;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Total Bookings</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--text-main); margin-top: 5px;"><?= $total_bookings ?></p>
            </div>
            <div style="background: rgba(245, 158, 11, 0.1); padding: 15px; border-radius: 50%;">
                <i class="fas fa-calendar-check" style="font-size: 1.5rem; color: var(--warning);"></i>
            </div>
        </div>
    </div>

    <div class="glass-panel" style="padding: 20px; background: white;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Revenue</h3>
                <p style="font-size: 2rem; font-weight: bold; color: var(--text-main); margin-top: 5px;">Rs. <?= number_format($total_revenue, 2) ?></p>
            </div>
            <div style="background: rgba(239, 68, 68, 0.1); padding: 15px; border-radius: 50%;">
                <i class="fas fa-money-bill-wave" style="font-size: 1.5rem; color: var(--danger);"></i>
            </div>
        </div>
    </div>
</div>

<div class="glass-panel" style="padding: 20px; background: white;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Recent Bookings</h3>
        <a href="<?= BASE_URL ?>/admin/manage_bookings.php" class="btn btn-secondary" style="padding: 5px 15px; font-size: 0.9rem;">View All</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Room</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_bookings as $booking): ?>
                    <tr>
                        <td>#<?= $booking['id'] ?></td>
                        <td><?= htmlspecialchars($booking['user_name']) ?></td>
                        <td><?= htmlspecialchars($booking['room_number']) ?></td>
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
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($recent_bookings)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No recent bookings.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
