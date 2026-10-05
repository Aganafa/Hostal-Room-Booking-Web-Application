<?php
require_once __DIR__ . '/includes/admin_header.php';

if (!isset($_GET['id'])) {
    header('Location: ' . BASE_URL . '/admin/manage_rooms.php');
    exit();
}

$room_id = $_GET['id'];
$success = '';
$error = '';
$upload_dir = __DIR__ . '/../uploads/';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_number = trim($_POST['room_number']);
    $title = trim($_POST['title']);
    $type = trim($_POST['type']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $capacity = $_POST['capacity'];
    $facilities = trim($_POST['facilities']);
    $availability_status = $_POST['availability_status'];

    if (empty($room_number) || empty($title) || empty($price) || empty($capacity)) {
        $error = "Room Number, Title, Price, and Capacity are required.";
    } else {
        $image_query = "";
        $params = [$room_number, $title, $type, $description, $price, $capacity, $availability_status, $facilities];

        // Handle Image Upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
            $file_name = $_FILES['image']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            if (in_array($file_ext, $allowed_ext)) {
                $new_name = uniqid('room_') . '.' . $file_ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_name)) {
                    $image_query = ", image = ?";
                    $params[] = $new_name;
                    
                    // Optionally delete old image here if it's not default_room.jpg
                } else {
                    $error = "Failed to upload new image.";
                }
            } else {
                $error = "Invalid image format. Allowed: jpg, jpeg, png, webp";
            }
        }

        if (empty($error)) {
            try {
                $params[] = $room_id; // For the WHERE clause
                $stmt = $conn->prepare("UPDATE rooms SET room_number = ?, title = ?, type = ?, description = ?, price = ?, capacity = ?, availability_status = ?, facilities = ? $image_query WHERE id = ?");
                $stmt->execute($params);
                $success = "Room updated successfully.";
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

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch();

if (!$room) {
    echo "Room not found.";
    exit();
}
?>

<div style="margin-bottom: 20px;">
    <h2><a href="<?= BASE_URL ?>/admin/manage_rooms.php" style="color: var(--text-muted);"><i class="fas fa-arrow-left"></i> Manage Rooms</a> / Edit Room #<?= $room['id'] ?></h2>
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
                <input type="text" id="room_number" name="room_number" class="form-control" value="<?= htmlspecialchars($room['room_number']) ?>" required>
            </div>
            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($room['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Room Type *</label>
                <select id="type" name="type" class="form-control" required>
                    <option value="Single" <?= $room['type'] == 'Single' ? 'selected' : '' ?>>Single</option>
                    <option value="Double" <?= $room['type'] == 'Double' ? 'selected' : '' ?>>Double</option>
                    <option value="Dormitory" <?= $room['type'] == 'Dormitory' ? 'selected' : '' ?>>Dormitory</option>
                    <option value="Suite" <?= $room['type'] == 'Suite' ? 'selected' : '' ?>>Suite</option>
                </select>
            </div>
            <div class="form-group">
                <label for="price">Price (per night) *</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" value="<?= htmlspecialchars($room['price']) ?>" required>
            </div>
            <div class="form-group">
                <label for="capacity">Capacity (Persons) *</label>
                <input type="number" id="capacity" name="capacity" class="form-control" value="<?= htmlspecialchars($room['capacity']) ?>" required>
            </div>
            <div class="form-group">
                <label for="availability_status">Availability Status</label>
                <select id="availability_status" name="availability_status" class="form-control">
                    <option value="Available" <?= $room['availability_status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                    <option value="Unavailable" <?= $room['availability_status'] == 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="facilities">Facilities</label>
            <input type="text" id="facilities" name="facilities" class="form-control" value="<?= htmlspecialchars($room['facilities']) ?>">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?= htmlspecialchars($room['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="image">Room Image (Leave empty to keep current)</label>
            <div style="margin-bottom: 10px;">
                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($room['image']) ?>" alt="Current Image" style="height: 100px; border-radius: 4px;">
            </div>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Update Room</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
