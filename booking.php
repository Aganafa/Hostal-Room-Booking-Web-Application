<?php
require_once __DIR__ . '/includes/header.php';
requireUserLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['room_id'])) {
    $user_id = $_SESSION['user_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $total_price = $_POST['total_price'];

    if (empty($check_in) || empty($check_out) || $total_price <= 0) {
        $error = 'Invalid booking details. Please select valid dates.';
    } else {
        // Simple check if dates are valid
        if (strtotime($check_in) >= strtotime($check_out)) {
            $error = 'Check-out date must be after check-in date.';
        } else {
            // Check if room is available in those dates (simple version: just check if it's currently marked as Available)
            // A more complex system would check existing bookings overlapping dates
            $stmt = $conn->prepare("SELECT availability_status FROM rooms WHERE id = ?");
            $stmt->execute([$room_id]);
            $room = $stmt->fetch();
            
            if ($room && $room['availability_status'] == 'Available') {
                $conn->beginTransaction();
                try {
                    // Insert Booking
                    $stmt = $conn->prepare("INSERT INTO bookings (user_id, room_id, check_in, check_out, total_price, booking_status, payment_status) VALUES (?, ?, ?, ?, ?, 'Pending', 'Pending')");
                    $stmt->execute([$user_id, $room_id, $check_in, $check_out, $total_price]);
                    $booking_id = $conn->lastInsertId();

                    // Insert Payment Record (Pending)
                    $payment_method = 'Online'; // Defaulting for now
                    $stmt = $conn->prepare("INSERT INTO payments (booking_id, payment_method, amount, payment_status) VALUES (?, ?, ?, 'Pending')");
                    $stmt->execute([$booking_id, $payment_method, $total_price]);

                    $conn->commit();
                    $success = 'Booking request submitted successfully! Please proceed to payment or wait for admin approval.';
                } catch (Exception $e) {
                    $conn->rollBack();
                    $error = 'Failed to process booking. Please try again.';
                }
            } else {
                $error = 'Room is not available.';
            }
        }
    }
} else {
    header('Location: ' . BASE_URL . '/rooms.php');
    exit();
}
?>

<div class="container" style="max-width: 600px; margin-top: 50px;">
    <div class="glass-panel" style="padding: 40px; text-align: center;">
        <?php if ($error): ?>
            <i class="fas fa-exclamation-circle" style="font-size: 4rem; color: var(--danger); margin-bottom: 20px;"></i>
            <h2 style="color: var(--danger); margin-bottom: 20px;">Booking Failed</h2>
            <p style="margin-bottom: 20px;"><?= htmlspecialchars($error) ?></p>
            <a href="javascript:history.back()" class="btn btn-primary">Go Back</a>
        <?php endif; ?>

        <?php if ($success): ?>
            <i class="fas fa-check-circle" style="font-size: 4rem; color: var(--success); margin-bottom: 20px;"></i>
            <h2 style="color: var(--success); margin-bottom: 20px;">Booking Successful!</h2>
            <p style="margin-bottom: 20px;"><?= htmlspecialchars($success) ?></p>
            <p style="color: var(--text-muted); margin-bottom: 30px;">You can view and manage your bookings in your dashboard.</p>
            <a href="<?= BASE_URL ?>/user/booking-history.php" class="btn btn-primary">View My Bookings</a>
            <a href="<?= BASE_URL ?>/index.php" class="btn btn-secondary">Return Home</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
