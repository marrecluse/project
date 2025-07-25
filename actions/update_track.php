<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['track_id'];
    // $albumId = (int)$_POST['album_id']; // get album ID from form
    $name = trim($_POST['name']);
    $albumId = (int)$_POST['album_id'];
    $composer = trim($_POST['composer']);
    $milliseconds = (int)$_POST['milliseconds'];
    $price = (float)$_POST['price'];

    try {
        $stmt = $pdo->prepare("
            UPDATE tracks SET Name = ?, Composer = ?, Milliseconds = ?, UnitPrice = ?
            WHERE TrackId = ?
        ");
        $stmt->execute([$name, $composer, $milliseconds, $price, $id]);

header("Location: ../album_details.php?id=" . $albumId);
        exit;
    } catch (PDOException $e) {
        echo "❌ Update failed: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
