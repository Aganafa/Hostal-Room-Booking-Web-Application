<?php
require_once __DIR__ . '/includes/admin_header.php';

$success = '';
$error = '';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    // Delete image if not default
    $stmt = $conn->prepare("SELECT image FROM rooms WHERE id = ?");
    $stmt->execute([$delete_id]);
    $room = $stmt->fetch();
    if ($room && $room['image'] != 'default_room.jpg') {
        $img_path = __DIR__ . '/../uploads/' . $room['image'];
        if (file_exists($img_path)) {
            unlink($img_path);
        }
    }
    
    $stmt = $conn->prepare("DELETE FROM rooms WHERE id = ?");
    if ($stmt->execute([$delete_id])) {
        $success = "Room deleted successfully.";
    } else {
        $error = "Failed to delete room.";
    }
}

$rooms = $conn->query("SELECT * FROM rooms ORDER BY id DESC")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Manage Rooms</h2>
    <a href="<?= BASE_URL ?>/admin/add_room.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Room</a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="glass-panel" style="padding: 20px; background: white;">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Room No</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($room['image']) ?>" alt="Room" style="width: 80px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="this.src='https://via.placeholder.com/80x50?text=IMG'"></td>
                        <td><?= htmlspecialchars($room['room_number']) ?></td>
                        <td><?= htmlspecialchars($room['title']) ?></td>
                        <td><?= htmlspecialchars($room['type']) ?></td>
                        <td>Rs. <?= number_format($room['price'], 2) ?></td>
                        <td>
                            <?php if ($room['availability_status'] == 'Available'): ?>
                                <span class="badge badge-approved">The Available</span>
                            <?php else: ?>
                                <span class="badge badge-rejected">The Unavailable</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/edit_room.php?id=<?= $room['id'] ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.8rem; margin-right: 5px;"><i class="fas fa-edit"></i> Edit</a>
                            <a href="?delete_id=<?= $room['id'] ?>" class="btn btn-danger btn-delete" style="padding: 5px 10px; font-size: 0.8rem;" onclick="return confirm('Are you sure you want to delete this room?');"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($rooms)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No rooms found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
