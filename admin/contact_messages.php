<?php
require_once __DIR__ . '/includes/admin_header.php';

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    if ($stmt->execute([$delete_id])) {
        $success = "Message deleted successfully.";
    }
}

$messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Contact Messages</h2>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="glass-panel" style="padding: 20px; background: white;">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $msg): ?>
                    <tr>
                        <td>#<?= $msg['id'] ?></td>
                        <td><?= htmlspecialchars($msg['name']) ?></td>
                        <td><a href="mailto:<?= htmlspecialchars($msg['email']) ?>"><?= htmlspecialchars($msg['email']) ?></a></td>
                        <td><strong><?= htmlspecialchars($msg['subject']) ?></strong></td>
                        <td><div style="max-height: 100px; overflow-y: auto; max-width: 300px;"><?= nl2br(htmlspecialchars($msg['message'])) ?></div></td>
                        <td><?= date('M d, Y H:i', strtotime($msg['created_at'])) ?></td>
                        <td>
                            <a href="?delete_id=<?= $msg['id'] ?>" class="btn btn-danger btn-delete" style="padding: 4px 8px; font-size: 0.8rem; border-radius: 4px;" onclick="return confirm('Delete this message?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($messages)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No messages found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
