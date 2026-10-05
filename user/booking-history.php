<?php
require_once __DIR__ . '/../includes/header.php';
requireUserLogin();

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle Cancellation
if (isset($_GET['cancel_id'])) {
    $cancel_id = $_GET['cancel_id'];
    // Verify booking belongs to user and is pending
    $stmt = $conn->prepare("SELECT booking_status FROM bookings WHERE id = ? AND user_id = ?");
    $stmt->execute([$cancel_id, $user_id]);
    $booking = $stmt->fetch();

    if ($booking && $booking['booking_status'] == 'Pending') {
        $stmt = $conn->prepare("UPDATE bookings SET booking_status = 'Cancelled' WHERE id = ?");
        if ($stmt->execute([$cancel_id])) {
            $success = "Booking cancelled successfully.";
        } else {
            $error = "Failed to cancel booking.";
        }
    } else {
        $error = "Invalid booking or cannot be cancelled.";
    }
}

// Fetch all bookings for the user
$stmt = $conn->prepare("SELECT b.*, r.title, r.room_number, r.image, p.payment_method FROM bookings b 
                        JOIN rooms r ON b.room_id = r.id 
                        LEFT JOIN payments p ON b.id = p.booking_id
                        WHERE b.user_id = ? ORDER BY b.created_at DESC");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>

<div class="container dashboard-layout">
    <?php require_once __DIR__ . '/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <h2 style="margin-bottom: 20px;">Booking History</h2>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php foreach ($bookings as $booking): ?>
            <div class="glass-panel" style="padding: 20px; margin-bottom: 20px; display: flex; gap: 20px; align-items: center;">
                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($booking['image']) ?>" alt="Room Image" style="width: 150px; height: 100px; object-fit: cover; border-radius: 8px;" onerror="this.src='https://via.placeholder.com/150x100?text=Room'">
                
                <div style="flex-grow: 1;">
                    <h3 style="margin-bottom: 5px;"><?= htmlspecialchars($booking['title']) ?> (<?= htmlspecialchars($booking['room_number']) ?>)</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 10px;">
                        <strong>Check-in:</strong> <?= date('M d, Y', strtotime($booking['check_in'])) ?> | 
                        <strong>Check-out:</strong> <?= date('M d, Y', strtotime($booking['check_out'])) ?>
                    </p>
                    <p style="margin-bottom: 5px;">
                        <strong>Total Price:</strong> <span style="color: var(--primary-color); font-weight: bold;">Rs. <?= number_format($booking['total_price'], 2) ?></span>
                    </p>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <?php 
                            $bStatusClass = 'badge-pending';
                            if ($booking['booking_status'] == 'Approved') $bStatusClass = 'badge-approved';
                            if ($booking['booking_status'] == 'Rejected' || $booking['booking_status'] == 'Cancelled') $bStatusClass = 'badge-rejected';
                            
                            $pStatusClass = 'badge-pending';
                            if ($booking['payment_status'] == 'Paid') $pStatusClass = 'badge-approved';
                            if ($booking['payment_status'] == 'Failed') $pStatusClass = 'badge-rejected';
                        ?>
                        <span class="badge <?= $bStatusClass ?>">Booking: <?= $booking['booking_status'] ?></span>
                        <span class="badge <?= $pStatusClass ?>">Payment: <?= $booking['payment_status'] ?></span>
                    </div>
                </div>

                <div style="text-align: right; min-width: 120px;">
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 15px;">Booked on:<br><?= date('M d, Y', strtotime($booking['created_at'])) ?></p>
                    <?php if ($booking['booking_status'] == 'Pending'): ?>
                        <a href="?cancel_id=<?= $booking['id'] ?>" class="btn btn-danger btn-delete" style="padding: 6px 12px; font-size: 0.9rem;">Cancel</a>
                    <?php endif; ?>
                    <?php if ($booking['payment_status'] == 'Pending' && $booking['booking_status'] != 'Cancelled' && $booking['booking_status'] != 'Rejected'): ?>
                        <!-- A placeholder for payment processing in a real app -->
                        <button class="btn btn-primary" style="padding: 6px 12px; font-size: 0.9rem; margin-top: 5px;" onclick="alert('Payment gateway integration placeholder.')">Pay Now</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($bookings)): ?>
            <div class="glass-panel" style="padding: 40px; text-align: center;">
                <p>You have no bookings yet.</p>
                <a href="<?= BASE_URL ?>/rooms.php" class="btn btn-primary" style="margin-top: 20px;">Browse Rooms</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
