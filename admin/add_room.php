<?php
require_once __DIR__ . '/includes/admin_header.php';

$success = '';
$error = '';

// Create uploads directory if it doesn't exist
$upload_dir = __DIR__ . '/../uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = trim($_POST['room_number']);
    $title = trim($_POST['title']);
    $type = trim($_POST['type']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $facilities = trim($_POST['facilities']);
    $availability_status = $_POST['availability_status'];
    $image_name = 'default_room.jpg';

    if (empty($room_number) || empty($title) || empty($price) || empty($capacity)) {
        $error = "Room Number, Title, Price, and Capacity are required.";
    } else {
        // Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
            $file_name = $_FILES['image']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            if (in_array($file_ext, $allowed_ext)) {
                $new_name = uniqid('room_') . '.' . $file_ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_name)) {
                    $image_name = $new_name;
                } else {
                    $error = "Failed to upload image.";
                }
            } else {
                $error = "Invalid image format. Allowed: jpg, jpeg, png, webp";
            }
        }

        if (empty($error)) {
            try {
                $stmt = $conn->prepare("INSERT INTO rooms (room_number, title, type, description, price, capacity, image, availability_status, facilities) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$room_number, $title, $type, $description, $price, $capacity, $image_name, $availability_status, $facilities]);
                $success = "Room added successfully.";
            } catch (PDOException $e) {
                if ($e->errorInfo[1] == 1062) {
                    $error = "Room number already exists.";
                } else {
                    $error = "Database error: " . $e->getMessage();
                }
            }
        }
    }
}
?>

<div style="margin-bottom: 20px;">
    <h2><a href="<?= BASE_URL ?>/admin/manage_rooms.php" style="color: var(--text-muted);"><i class="fas fa-arrow-left"></i> Manage Rooms</a> / Add Room</h2>
</div>

<div class="glass-panel" style="padding: 30px; background: white; max-width: 800px;">
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="room_number">Room Number *</label>
                <input type="text" id="room_number" name="room_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="type">Room Type *</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="Single">Single</option>
                    <option value="Double">Double</option>
                    <option value="Dormitory">Dormitory</option>
                    <option value="Suite">Suite</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price (per night) *</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity (Persons) *</label>
                <input type="number" id="capacity" name="capacity" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="availability_status">Availability Status</label>
                <select id="availability_status" name="availability_status" class="form-control">
                    <option value="Available">Available</option>
                    <option value="Unavailable">Unavailable</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="facilities">Facilities (comma separated, e.g., WiFi, AC, Balcony)</label>
            <input type="text" id="facilities" name="facilities" class="form-control">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label for="image">Room Image</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Save Room</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
