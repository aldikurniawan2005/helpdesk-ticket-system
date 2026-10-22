<?php
require_once 'db.php';

function escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$allowedCategories = ['Hardware', 'Software', 'Network', 'Others'];

$errors = [];
$subject = '';
$category = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject'] ?? '');
    $category = $_POST['category'] ?? '';
    $description = trim($_POST['description'] ?? '');

    if ($subject === '') {
        $errors[] = 'Subject wajib diisi.';
    }

    if (!in_array($category, $allowedCategories, true)) {
        $errors[] = 'Pilih kategori yang valid.';
    }

    if ($description === '') {
        $errors[] = 'Description wajib diisi.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "INSERT INTO tickets (subject, category, description)
             VALUES (:subject, :category, :description)"
        );

        $stmt->execute([
            'subject' => $subject,
            'category' => $category,
            'description' => $description
        ]);

        header('Location: index.php?success=created');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ticket - Helpdesk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Create New Ticket</h1>
    <p class="subtitle">Isi formulir untuk membuat tiket bantuan baru.</p>

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

        <form action="create.php" method="POST">
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
                    <option value="">-- Pilih Category --</option>
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

            <button type="submit">Submit Ticket</button>
            <a href="index.php" class="button button-secondary">Cancel</a>
        </form>
    </div>
</div>
</body>
</html>
