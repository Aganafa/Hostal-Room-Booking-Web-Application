<?php
require_once __DIR__ . '/includes/header.php';

if (!isset($_GET['id'])) {
    header('Location: ' . BASE_URL . '/rooms.php');
    exit();
}

$room_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch();

if (!$room) {
    echo "<div class='container'><div class='alert alert-danger'>Room not found.</div></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit();
}

$facilities = explode(',', $room['facilities']);
?>

<div class="container">
    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 40px; margin-top: 20px;">
        <!-- Left Column: Details -->
        <div>
            <div class="glass-panel" style="padding: 20px; margin-bottom: 30px;">
                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($room['image']) ?>" alt="<?= htmlspecialchars($room['title']) ?>" style="width: 100%; border-radius: 12px; height: 400px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/800x400?text=Room+Image'">
            </div>
            
            <h1 style="color: var(--primary-color); margin-bottom: 10px;"><?= htmlspecialchars($room['title']) ?></h1>
            <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 20px;">
                <span class="badge badge-pending" style="background: rgba(79, 70, 229, 0.1); color: var(--primary-color); font-size: 1rem;"><i class="fas fa-hashtag"></i> Room No: <?= htmlspecialchars($room['room_number']) ?></span>
                <span class="badge badge-pending" style="background: rgba(16, 185, 129, 0.1); color: var(--success); font-size: 1rem; margin-left: 10px;"><i class="fas fa-bed"></i> Type: <?= htmlspecialchars($room['type']) ?></span>
            </p>

            <h3 style="margin-bottom: 15px;">Description</h3>
            <p style="color: var(--text-muted); line-height: 1.8; margin-bottom: 30px;"><?= nl2br(htmlspecialchars($room['description'])) ?></p>

            <h3 style="margin-bottom: 15px;">Facilities</h3>
            <ul style="display: flex; flex-wrap: wrap; gap: 10px;">
                <?php foreach ($facilities as $facility): ?>
                    <li style="background: rgba(255,255,255,0.8); padding: 8px 15px; border-radius: 20px; border: 1px solid var(--glass-border);"><i class="fas fa-check" style="color: var(--success); margin-right: 5px;"></i> <?= htmlspecialchars(trim($facility)) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Right Column: Booking Form -->
        <div>
            <div class="glass-panel" style="padding: 30px; position: sticky; top: 100px;">
                <h2 style="margin-bottom: 20px;">Book this Room</h2>
                <div style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin-bottom: 20px;" id="room_price" data-price="<?= $room['price'] ?>">
                    Rs. <?= number_format($room['price'], 2) ?> <span style="font-size: 1rem; color: var(--text-muted); font-weight: normal;">/ night</span>
                </div>

                <?php if ($room['availability_status'] !== 'Available'): ?>
                    <div class="alert alert-danger" style="text-align: center;">This room is currently unavailable for booking.</div>
                <?php else: ?>
                    <?php if (!isUserLoggedIn()): ?>
                        <div class="alert alert-warning" style="text-align: center; background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D;">
                            Please <a href="<?= BASE_URL ?>/login.php" style="text-decoration: underline;">login</a> to book this room.
                        </div>
                    <?php else: ?>
                        <form method="POST" action="<?= BASE_URL ?>/booking.php">
                            <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                            <div class="form-group">
                                <label for="check_in">Check-in Date</label>
                                <input type="date" id="check_in" name="check_in" class="form-control" required min="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="form-group">
                                <label for="check_out">Check-out Date</label>
                                <input type="date" id="check_out" name="check_out" class="form-control" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; margin-bottom: 20px; padding-top: 15px; border-top: 1px solid var(--glass-border);">
                                <span style="font-weight: 600;">Total Price:</span>
                                <span id="total_price_display" style="font-weight: bold; font-size: 1.5rem; color: var(--success);">$0.00</span>
                                <input type="hidden" id="total_price" name="total_price" value="0">
                            </div>
                            <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 12px;">Proceed to Book</button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
