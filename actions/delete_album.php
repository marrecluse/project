<?php
session_start();
if (!isset($_SESSION['staff'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../config/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $albumId = $_GET['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM albums WHERE AlbumId = ?");
        $stmt->execute([$albumId]);

        if ($stmt->rowCount() > 0) {
            // Success
            header("Location: ../index.php?deleted=1");
        } else {
            // Album not found
            header("Location: ../index.php?deleted=0");
        }
    } catch (PDOException $e) {
        echo "❌ Error deleting album: " . $e->getMessage();
    }

} else {
    // Invalid ID
    header("Location: ../index.php");
}
exit;
?>
