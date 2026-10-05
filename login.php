<?php
require_once __DIR__ . '/includes/header.php';

if (isUserLoggedIn()) {
    header('Location: ' . BASE_URL . '/user/dashboard.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: ' . BASE_URL . '/user/dashboard.php');
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>

<div class="auth-wrapper">
    <div class="glass-panel auth-card">
        <h2>User Login</h2>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
        <p style="text-align: center; margin-top: 20px;">
            Don't have an account? <a href="<?= BASE_URL ?>/register.php">Register here</a>
        </p>
        <p style="text-align: center; margin-top: 10px;">
            <a href="<?= BASE_URL ?>/forgot-password.php">Forgot Password?</a>
        </p>
        <p style="text-align: center; margin-top: 10px;">
            <a href="<?= BASE_URL ?>/admin/login.php" style="font-size: 0.9em; color: var(--text-muted);">Admin Login</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
