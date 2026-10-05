<?php
require_once __DIR__ . '/includes/admin_header.php';

$success = '';
$error = '';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'approve') {
        $stmt = $conn->prepare("UPDATE bookings SET booking_status = 'Approved' WHERE id = ?");
        if ($stmt->execute([$id])) $success = "Booking approved.";
    } elseif ($action == 'reject') {
        $stmt = $conn->prepare("UPDATE bookings SET booking_status = 'Rejected' WHERE id = ?");
        if ($stmt->execute([$id])) $success = "Booking rejected.";
    }
}

$bookings = $conn->query("SELECT b.*, u.name as user_name, u.email, r.room_number FROM bookings b 
                          JOIN users u ON b.user_id = u.id 
                          JOIN rooms r ON b.room_id = r.id 
                          ORDER BY b.created_at DESC")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manage Bookings</h2>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="glass-panel" style="padding: 20px; background: white;">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Room</th>
                    <th>Dates</th>
                    <th>Amount</th>
                    <th>Booking Status</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td>#<?= $booking['id'] ?></td>
                        <td>
                            <?= htmlspecialchars($booking['user_name']) ?><br>
                            <small style="color: var(--text-muted);"><?= htmlspecialchars($booking['email']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($booking['room_number']) ?></td>
                        <td>
                            <small>
                                In: <?= date('M d, Y', strtotime($booking['check_in'])) ?><br>
                                Out: <?= date('M d, Y', strtotime($booking['check_out'])) ?>
                            </small>
                        </td>
                        <td>Rs. <?= number_format($booking['total_price'], 2) ?></td>
                        <td>
                            <?php 
                                $statusClass = 'badge-pending';
                                if ($booking['booking_status'] == 'Approved') $statusClass = 'badge-approved';
                                if ($booking['booking_status'] == 'Rejected' || $booking['booking_status'] == 'Cancelled') $statusClass = 'badge-rejected';
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= $booking['booking_status'] ?></span>
                        </td>
                        <td>
                            <span class="badge <?= $booking['payment_status'] == 'Paid' ? 'badge-approved' : 'badge-pending' ?>"><?= $booking['payment_status'] ?></span>
                        </td>
                        <td>
                            <?php if ($booking['booking_status'] == 'Pending'): ?>
                                <a href="?action=approve&id=<?= $booking['id'] ?>" class="btn btn-success" style="padding: 4px 8px; font-size: 0.8rem; background: var(--success); color: white; border-radius: 4px; display: inline-block; margin-bottom: 5px;" onclick="return confirm('Approve this booking?');">Approve</a>
                                <a href="?action=reject&id=<?= $booking['id'] ?>" class="btn btn-danger" style="padding: 4px 8px; font-size: 0.8rem; border-radius: 4px; display: inline-block;" onclick="return confirm('Reject this booking?');">Reject</a>
                            <?php else: ?>
                                <span style="font-size: 0.8rem; color: var(--text-muted);">Resolved</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
