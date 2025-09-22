<?php
require_once 'php/config.php';
// Proteksi Halaman
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Ambil semua pengguna yang statusnya 'menunggu'
$stmt = $pdo->prepare("SELECT id, nama_lengkap, username FROM users WHERE status = 'menunggu'");
$stmt->execute();
$pending_users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengguna - BSB ARC</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'templates/admin_header.php'; ?>
    <div class="container">
        <main>
            <h2>Persetujuan Pengguna Baru</h2>
            <?php include 'templates/message.php'; ?>
            <table>
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pending_users)): ?>
                        <tr>
                            <td colspan="3" style="text-align:center;">Tidak ada pengguna yang menunggu persetujuan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pending_users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td>
                                    <form action="php/admin_actions.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="approve_user">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="btn btn-success">Setujui</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>

</html>