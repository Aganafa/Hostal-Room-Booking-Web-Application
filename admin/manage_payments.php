<?php
require_once __DIR__ . '/includes/admin_header.php';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'mark_paid') {
        $stmt = $conn->prepare("UPDATE payments SET payment_status = 'Paid', transaction_id = ? WHERE id = ?");
        $transaction_id = 'TXN' . strtoupper(uniqid());
        if ($stmt->execute([$transaction_id, $id])) {
            // Also update booking status to Approved if we want, or leave it independent
            $success = "Payment marked as Paid.";
        }
    } elseif ($action == 'mark_failed') {
        $stmt = $conn->prepare("UPDATE payments SET payment_status = 'Failed' WHERE id = ?");
        if ($stmt->execute([$id])) $success = "Payment marked as Failed.";
    }
}

$payments = $conn->query("SELECT p.*, b.id as booking_ref, u.name as user_name FROM payments p 
                          JOIN bookings b ON p.booking_id = b.id
                          JOIN users u ON b.user_id = u.id
                          ORDER BY p.created_at DESC")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manage Payments</h2>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="glass-panel" style="padding: 20px; background: white;">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Booking Ref</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Transaction ID</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td>#<?= $payment['id'] ?></td>
                        <td>#<?= $payment['booking_ref'] ?></td>
                        <td><?= htmlspecialchars($payment['user_name']) ?></td>
                        <td>Rs. <?= number_format($payment['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($payment['payment_method']) ?></td>
                        <td><?= htmlspecialchars($payment['transaction_id'] ?? 'N/A') ?></td>
                        <td>
                            <?php 
                                $statusClass = 'badge-pending';
                                if ($payment['payment_status'] == 'Paid') $statusClass = 'badge-approved';
                                if ($payment['payment_status'] == 'Failed') $statusClass = 'badge-rejected';
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= $payment['payment_status'] ?></span>
                        </td>
                        <td>
                            <?php if ($payment['payment_status'] == 'Pending'): ?>
                                <a href="?action=mark_paid&id=<?= $payment['id'] ?>" class="btn btn-success" style="padding: 4px 8px; font-size: 0.8rem; background: var(--success); color: white; border-radius: 4px;" onclick="return confirm('Mark as Paid?');">Mark Paid</a>
                                <a href="?action=mark_failed&id=<?= $payment['id'] ?>" class="btn btn-danger" style="padding: 4px 8px; font-size: 0.8rem; border-radius: 4px;" onclick="return confirm('Mark as Failed?');">Mark Failed</a>
                            <?php else: ?>
                                <span style="font-size: 0.8rem; color: var(--text-muted);">Completed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($payments)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center;">No payments found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
