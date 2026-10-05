<?php
require_once __DIR__ . '/includes/header.php';

// Fetch all rooms
$stmt = $conn->query("SELECT * FROM rooms ORDER BY id DESC");
$rooms = $stmt->fetchAll();
?>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="color: var(--primary-color);">Our Rooms</h1>
        <div style="width: 300px;">
            <input type="text" id="search_rooms" class="form-control" placeholder="Search rooms...">
        </div>
    </div>

    <div class="grid" id="rooms_container">
        <?php foreach ($rooms as $room): ?>
            <div class="glass-panel room-card">
                <div class="room-img-container">
                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($room['image']) ?>" alt="<?= htmlspecialchars($room['title']) ?>" onerror="this.src='https://via.placeholder.com/400x200?text=Room+Image'">
                    <?php if ($room['availability_status'] === 'Available'): ?>
                        <span style="position: absolute; top: 10px; right: 10px; background: var(--success); color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">Available</span>
                    <?php else: ?>
                        <span style="position: absolute; top: 10px; right: 10px; background: var(--danger); color: white; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">Unavailable</span>
                    <?php endif; ?>
                </div>
                <div class="room-details">
                    <h3 class="room-title"><?= htmlspecialchars($room['title']) ?></h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 10px;"><i class="fas fa-bed"></i> Type: <?= htmlspecialchars($room['type']) ?> | Room No: <?= htmlspecialchars($room['room_number']) ?></p>
                    <p class="room-price">Rs. <?= number_format($room['price'], 2) ?> <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: normal;">/ night</span></p>
                    <p style="color: var(--text-muted); margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= htmlspecialchars($room['description']) ?>
                    </p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="badge badge-pending" style="background: rgba(79, 70, 229, 0.1); color: var(--primary-color);"><i class="fas fa-users"></i> <?= $room['capacity'] ?> Person(s)</span>
                        <a href="<?= BASE_URL ?>/room-details.php?id=<?= $room['id'] ?>" class="btn btn-primary" style="padding: 8px 15px;">Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($rooms)): ?>
            <p>No rooms available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
