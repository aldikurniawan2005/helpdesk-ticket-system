<?php

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header('Location: index.php?error=invalid_id');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM tickets WHERE id = :id");
$stmt->execute(['id' => $id]);

if ($stmt->rowCount() > 0) {
    header('Location: index.php?success=deleted');
    exit;
}

header('Location: index.php?error=not_found');
exit;
