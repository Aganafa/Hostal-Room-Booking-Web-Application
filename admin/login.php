<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if (isAdminLoggedIn()) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: ' . BASE_URL . '/admin/dashboard.php');
            exit();
        } else {
            $error = 'Invalid admin credentials.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hostel Room Booking</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css?v=<?= time() ?>">
</head>
<body style="background-color: #111827; background-image: none;">
    <div class="auth-wrapper">
        <div class="glass-panel auth-card" style="background: rgba(31, 41, 55, 0.8); color: white; border: 1px solid #374151;">
            <h2 style="color: white;">Admin Portal Access</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username" style="color: #D1D5DB;">Username</label>
                    <input type="text" id="username" name="username" class="form-control" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid #4B5563;" required>
                </div>
                <div class="form-group">
                    <label for="password" style="color: #D1D5DB;">Password</label>
                    <input type="password" id="password" name="password" class="form-control" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid #4B5563;" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login to Dashboard</button>
            </form>
            <p style="text-align: center; margin-top: 20px;">
                <a href="<?= BASE_URL ?>/index.php" style="color: #9CA3AF;">&larr; Back to Website</a>
            </p>
        </div>
    </div>
</body>
</html>
