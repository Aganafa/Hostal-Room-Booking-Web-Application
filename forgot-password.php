<?php
require_once __DIR__ . '/includes/header.php';

if (isUserLoggedIn()) {
    header('Location: ' . BASE_URL . '/user/dashboard.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'];

    if (empty($email) || empty($new_password)) {
        $error = 'Please provide both email and a new password.';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            if ($update->execute([$hashed_password, $email])) {
                $success = 'Password reset successfully. You can now <a href="'.BASE_URL.'/login.php">login</a> with your new password.';
            } else {
                $error = 'Failed to reset password. Please try again.';
            }
        } else {
            $error = 'Email address not found.';
        }
    }
}
?>

<div class="auth-wrapper">
    <div class="glass-panel auth-card">
        <h2>Reset Password</h2>
        <p style="text-align: center; margin-bottom: 20px; color: var(--text-muted);">Enter your registered email and a new password.</p>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php else: ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Registered Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Reset Password</button>
            </form>
        <?php endif; ?>
        <p style="text-align: center; margin-top: 20px;">
            <a href="<?= BASE_URL ?>/login.php">Back to Login</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
