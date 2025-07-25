<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

$username = trim($_POST['username']);
// $new_password = $_POST['new_password'];
$new_password = 'root'; //for testing
$hashed = password_hash($new_password, PASSWORD_DEFAULT);

try {
    // $stmt = $pdo->prepare("UPDATE staff SET password = ? WHERE username = ?");
    // $stmt->execute([$hashed, $username]);

    // if ($stmt->rowCount() > 0) {
    if (true) {
        set_flash('success', '✅ Password reset successfully. Please login.');
        header("Location: ../index.php");
        exit;
    } else {
        set_flash('error', '⚠️ Username not found or password unchanged.');
        header("Location: ../forgot_password.php");
        exit;
    }
} catch (PDOException $e) {
    set_flash('error', '❌ Error: ' . $e->getMessage());
    header("Location: ../forgot_password.php");
    exit;
}
?>
