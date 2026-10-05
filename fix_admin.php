<?php
require_once __DIR__ . '/config/database.php';
$new_hash = password_hash('password123', PASSWORD_DEFAULT);
$conn->query("UPDATE admins SET password = '$new_hash' WHERE username = 'admin'");
echo "Admin password has been reset to: password123";
?>
