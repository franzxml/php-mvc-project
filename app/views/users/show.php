<?php ob_start(); ?>
<h1>Detail User</h1>
<p><strong>ID:</strong> <?= $user['id'] ?></p>
<p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
<a href="/users">Kembali</a>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layouts/main.php'; ?>