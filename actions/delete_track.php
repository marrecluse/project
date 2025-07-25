<?php
session_start();
require_once '../config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Invalid track ID.</p>";
    exit;
}

$trackId = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM tracks WHERE TrackId = ?");
    $stmt->execute([$trackId]);

    echo "<p>Track deleted successfully. <a href='../index.php'>Back to Albums</a></p>";
} catch (PDOException $e) {
    echo "❌ Delete failed: " . $e->getMessage();
}
