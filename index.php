<?php
require_once __DIR__ . '/includes/header.php';

// Fetch a few available rooms for the homepage
$stmt = $conn->query("SELECT * FROM rooms WHERE availability_status = 'Available' LIMIT 3");
$featured_rooms = $stmt->fetchAll();
?>

<!-- Hero Section -->
<section style="text-align: center; padding: 60px 20px; background: rgba(79, 70, 229, 0.05); border-radius: 16px; margin-bottom: 40px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h1 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 20px;">Find Your Perfect Stay</h1>
    <p style="font-size: 1.2rem; color: var(--text-muted); margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto;">Book comfortable, affordable, and secure hostel rooms online in just a few clicks.</p>
    <a href="<?= BASE_URL ?>/rooms.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 15px 30px;">Explore Rooms</a>
</section>

<!-- Features Section -->
<section style="margin-bottom: 60px;">
    <h2 style="text-align: center; margin-bottom: 40px;">Why Choose Us?</h2>
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); text-align: center;">
        <div class="glass-panel" style="padding: 30px;">
            <i class="fas fa-bolt" style="font-size: 2.5rem; color: var(--warning); margin-bottom: 20px;"></i>
            <h3>Fast Booking</h3>
            <p style="color: var(--text-muted); margin-top: 10px;">Instantly check availability and book your room without any hassle.</p>
        </div>
        <div class="glass-panel" style="padding: 30px;">
            <i class="fas fa-shield-alt" style="font-size: 2.5rem; color: var(--success); margin-bottom: 20px;"></i>
            <h3>Secure Payments</h3>
            <p style="color: var(--text-muted); margin-top: 10px;">Your transactions are secure with our robust payment tracking system.</p>
        </div>
        <div class="glass-panel" style="padding: 30px;">
            <i class="fas fa-wifi" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 20px;"></i>
            <h3>Great Facilities</h3>
            <p style="color: var(--text-muted); margin-top: 10px;">Enjoy free WiFi, clean spaces, and much more during your stay.</p>
        </div>
    </div>
</section>

<!-- Featured Rooms Section -->
<section style="margin-bottom: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Featured Rooms</h2>
        <a href="<?= BASE_URL ?>/rooms.php" class="btn btn-secondary">View All</a>
    </div>
    <div class="grid">
        <?php foreach ($featured_rooms as $room): ?>
            <div class="glass-panel room-card">
                <div class="room-img-container">
                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($room['image']) ?>" alt="<?= htmlspecialchars($room['title']) ?>" onerror="this.src='https://via.placeholder.com/400x200?text=Room+Image'">
                </div>
                <div class="room-details">
                    <h3 class="room-title"><?= htmlspecialchars($room['title']) ?></h3>
                    <p class="room-price">Rs. <?= number_format($room['price'], 2) ?> <span style="font-size: 0.9rem; color: var(--text-muted); font-weight: normal;">/ night</span></p>
                    <p style="color: var(--text-muted); margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        <?= htmlspecialchars($room['description']) ?>
                    </p>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="badge badge-approved"><i class="fas fa-user"></i> <?= $room['capacity'] ?> Person(s)</span>
                        <a href="<?= BASE_URL ?>/room-details.php?id=<?= $room['id'] ?>" class="btn btn-primary" style="padding: 8px 15px;">Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($featured_rooms)): ?>
            <p>No featured rooms available at the moment.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
