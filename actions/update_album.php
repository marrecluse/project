<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['staff'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['album_id'])) {
    $albumId = (int) $_POST['album_id'];
    $title = trim($_POST['title']);
    $artist_id = (int) $_POST['artist_id'];

    if ($title && $artist_id > 0) {
        try {
            $stmt = $pdo->prepare("UPDATE albums SET Title = ?, ArtistId = ? WHERE AlbumId = ?");
            $stmt->execute([$title, $artist_id, $albumId]);

set_flash('success', '<i class="fas fa-check-circle" style="color: green;"></i> Album updated successfully.');
        } catch (PDOException $e) {
set_flash('error', '<i class="fas fa-times-circle" style="color: red;"></i> Error updating album: ' . htmlspecialchars($e->getMessage()));
        }
    } else {
set_flash('error', '<i class="fas fa-times-circle" style="color: red;"></i> Invalid input for update.');
    }

    header("Location: ../index.php");
    exit;
}
?>
