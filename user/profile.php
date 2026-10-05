<?php
require_once __DIR__ . '/../includes/header.php';
requireUserLogin();

$user = getCurrentUser($conn);
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    if (empty($name) || empty($phone)) {
        $error = 'Name and Phone are required.';
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
        if ($stmt->execute([$name, $phone, $user['id']])) {
            $success = 'Profile updated successfully.';
            $user['name'] = $name;
            $user['phone'] = $phone;
            $_SESSION['user_name'] = $name;
        } else {
            $error = 'Failed to update profile.';
        }
    }
}
?>

<div class="container dashboard-layout">
    <?php require_once __DIR__ . '/sidebar.php'; ?>
    
    <div class="dashboard-content">
        <h2 style="margin-bottom: 20px;">My Profile</h2>
        
        <div class="glass-panel" style="padding: 30px; max-width: 600px;">
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address (Cannot be changed)</label>
                    <input type="email" id="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled style="background: #E5E7EB;">
                </div>
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
