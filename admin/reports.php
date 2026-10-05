<?php
require_once __DIR__ . '/includes/admin_header.php';

// Get monthly revenue for the current year
$year = date('Y');
$stmt = $conn->prepare("
    SELECT MONTH(created_at) as month, SUM(amount) as revenue 
    FROM payments 
    WHERE YEAR(created_at) = ? AND payment_status = 'Paid' 
    GROUP BY MONTH(created_at)
");
$stmt->execute([$year]);
$monthly_revenue = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Get popular rooms
$popular_rooms = $conn->query("
    SELECT r.title, r.room_number, COUNT(b.id) as bookings_count 
    FROM rooms r 
    LEFT JOIN bookings b ON r.id = b.room_id 
    GROUP BY r.id 
    ORDER BY bookings_count DESC 
    LIMIT 5
")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Reports & Analytics</h2>
</div>

<div class="grid" style="grid-template-columns: 1fr 1fr; gap: 30px;">
    
    <div class="glass-panel" style="padding: 20px; background: white;">
        <h3 style="margin-bottom: 20px; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px;">Monthly Revenue (<?= $year ?>)</h3>
        <table style="margin-top: 0;">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                $total_year = 0;
                foreach ($months as $index => $monthName): 
                    $m = $index + 1;
                    $rev = isset($monthly_revenue[$m]) ? $monthly_revenue[$m] : 0;
                    $total_year += $rev;
                ?>
                    <tr>
                        <td><?= $monthName ?></td>
                        <td>Rs. <?= number_format($rev, 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th style="color: var(--success); font-size: 1.1rem;">Rs. <?= number_format($total_year, 2) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="glass-panel" style="padding: 20px; background: white;">
        <h3 style="margin-bottom: 20px; border-bottom: 1px solid var(--glass-border); padding-bottom: 10px;">Most Popular Rooms</h3>
        <table style="margin-top: 0;">
            <thead>
                <tr>
                    <th>Room</th>
                    <th>Total Bookings</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($popular_rooms as $room): ?>
                    <tr>
                        <td><?= htmlspecialchars($room['title']) ?> (<?= htmlspecialchars($room['room_number']) ?>)</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="flex-grow: 1; background: #E5E7EB; height: 10px; border-radius: 5px;">
                                    <div style="width: <?= min(100, $room['bookings_count'] * 10) ?>%; background: var(--primary-color); height: 100%; border-radius: 5px;"></div>
                                </div>
                                <span><?= $room['bookings_count'] ?></span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($popular_rooms)): ?>
                    <tr>
                        <td colspan="2" style="text-align: center;">No booking data available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
