<?php
require_once __DIR__ . '/config/database.php';
$stmt = $conn->query("SELECT id, title, image, room_number FROM rooms ORDER BY id ASC");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rooms, JSON_PRETTY_PRINT);
?>
