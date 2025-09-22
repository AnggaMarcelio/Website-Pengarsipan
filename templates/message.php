<?php
if (isset($_SESSION['message'])) {
    $message_class = isset($_SESSION['is_error']) && $_SESSION['is_error'] ? 'error' : 'success';
    echo "<div class='message-box $message_class'>{$_SESSION['message']}</div>";
    // Hapus pesan setelah ditampilkan
    unset($_SESSION['message']);
    unset($_SESSION['is_error']);
}
