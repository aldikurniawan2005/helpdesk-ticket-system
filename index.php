<?php
require_once 'db.php';

function escape($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare(
        "SELECT * FROM tickets
         WHERE subject LIKE :search
         ORDER BY created_at DESC, id DESC"
    );
    $stmt->execute(['search' => '%' . $search . '%']);
} else {
    $stmt = $pdo->query(
        "SELECT * FROM tickets ORDER BY created_at DESC, id DESC"
    );
}

$tickets = $stmt->fetchAll();
$totalTickets = count($tickets);

$messages = [
    'created' => 'Ticket berhasil dibuat.',
    'updated' => 'Ticket berhasil diperbarui.',
    'deleted' => 'Ticket berhasil dihapus.'
];

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk Ticket System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <h1>Helpdesk Ticket System</h1>
    <p class="subtitle">Kelola dan pantau laporan bantuan teknis.</p>

    <?php if (isset($messages[$success])): ?>
        <div class="success-message">
            <?= escape($messages[$success]) ?>
        </div>
    <?php endif; ?>

    <?php if ($error === 'invalid_id'): ?>
        <div class="error-message">ID tiket tidak valid.</div>
    <?php elseif ($error === 'not_found'): ?>
        <div class="error-message">Tiket tidak ditemukan.</div>
    <?php endif; ?>

    <div class="summary-card">
        <p><?= $search !== '' ? 'Hasil Pencarian' : 'Total Tickets' ?></p>
        <strong><?= $totalTickets ?></strong>
    </div>

    <form action="index.php" method="GET" class="search-form">
        <input
            type="text"
            name="search"
            placeholder="Cari berdasarkan subject..."
            value="<?= escape($search) ?>"
            aria-label="Cari berdasarkan subject"
        >
        <button type="submit">Search</button>

        <?php if ($search !== ''): ?>
            <a href="index.php" class="button button-secondary">Clear</a>
        <?php endif; ?>
    </form>

    <p>
        <a href="create.php" class="button button-create">
            + Create Ticket
        </a>
    </p>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($tickets)): ?>
                <tr>
                    <td colspan="6">
                        <?= $search !== ''
                            ? 'Tidak ada tiket yang cocok dengan pencarian.'
                            : 'Belum ada tiket. Silakan buat tiket baru.' ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php $number = 1; ?>
                <?php foreach ($tickets as $ticket): ?>
                    <?php
                    $statusClass = 'status-open';

                    if ($ticket['status'] === 'In Progress') {
                        $statusClass = 'status-progress';
                    } elseif ($ticket['status'] === 'Closed') {
                        $statusClass = 'status-closed';
                    }
                    ?>
                    <tr>
                        <td><?= $number++ ?></td>
                        <td><?= escape($ticket['subject']) ?></td>
                        <td><?= escape($ticket['category']) ?></td>
                        <td>
                            <span class="status <?= escape($statusClass) ?>">
                                <?= escape($ticket['status']) ?>
                            </span>
                        </td>
                        <td><?= escape($ticket['created_at']) ?></td>
                        <td>
                            <div class="action-buttons">
                                <a
                                    href="edit.php?id=<?= escape($ticket['id']) ?>"
                                    class="button button-edit"
                                >Edit</a>

                                <form
                                    action="delete.php"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus tiket ini?');"
                                >
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= escape($ticket['id']) ?>"
                                    >
                                    <button
                                        type="submit"
                                        class="button-delete"
                                    >Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
