<?php
require_once 'db.php';

function escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$allowedCategories = ['Hardware', 'Software', 'Network', 'Others'];
$allowedStatuses = ['Open', 'In Progress', 'Closed'];
$errors = [];

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    exit('ID tiket tidak valid.');
}

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = :id");
$stmt->execute(['id' => $id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    http_response_code(404);
    exit('Tiket tidak ditemukan.');
}

$subject = $ticket['subject'];
$category = $ticket['category'];
$description = $ticket['description'];
$status = $ticket['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $category = $_POST['category'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? '';

    if ($subject === '') {
        $errors[] = 'Subject wajib diisi.';
    }

    if (!in_array($category, $allowedCategories, true)) {
        $errors[] = 'Pilih kategori yang valid.';
    }

    if ($description === '') {
        $errors[] = 'Description wajib diisi.';
    }

    if (!in_array($status, $allowedStatuses, true)) {
        $errors[] = 'Pilih status yang valid.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "UPDATE tickets
             SET subject = :subject,
                 category = :category,
                 description = :description,
                 status = :status
             WHERE id = :id"
        );

        $stmt->execute([
            'subject' => $subject,
            'category' => $category,
            'description' => $description,
            'status' => $status,
            'id' => $id
        ]);

        header('Location: index.php?success=updated');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ticket - Helpdesk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Ticket</h1>
    <p class="subtitle">Perbarui informasi tiket yang dipilih.</p>

    <div class="form-card">
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= escape($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="edit.php?id=<?= escape($id) ?>" method="POST">
            <div class="form-group">
                <label for="subject">Subject *</label>
                <input
                    type="text"
                    id="subject"
                    name="subject"
                    maxlength="255"
                    value="<?= escape($subject) ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="category">Category *</label>
                <select id="category" name="category" required>
                    <?php foreach ($allowedCategories as $option): ?>
                        <option
                            value="<?= escape($option) ?>"
                            <?= $category === $option ? 'selected' : '' ?>
                        >
                            <?= escape($option) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    required
                ><?= escape($description) ?></textarea>
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <?php foreach ($allowedStatuses as $option): ?>
                        <option
                            value="<?= escape($option) ?>"
                            <?= $status === $option ? 'selected' : '' ?>
                        >
                            <?= escape($option) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit">Update Ticket</button>
            <a href="index.php" class="button button-secondary">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
