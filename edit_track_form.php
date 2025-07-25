<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Invalid track ID.</p>";
    exit;
}

$trackId = (int)$_GET['id'];
$albumId = isset($_GET['album_id']) ? (int)$_GET['album_id'] : 0;
$stmt = $pdo->prepare("
    SELECT TrackId, Name, Composer, Milliseconds, UnitPrice, AlbumId
    FROM tracks
    WHERE TrackId = ?
");
$stmt->execute([$trackId]);
$track = $stmt->fetch();

if (!$track) {
    echo "<p>Track not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Track</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<body>
<div class="container">
    <h1>Edit Track</h1>

    <form method="post" action="actions/update_track.php">
        <input type="hidden" name="track_id" value="<?= $track['TrackId'] ?>">
<input type="hidden" name="album_id" value="<?= $albumId ?>">

        <label>Track Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($track['Name']) ?>" required><br><br>

        <label>Composer:</label><br>
        <input type="text" name="composer" value="<?= htmlspecialchars($track['Composer']) ?>"><br><br>

        <label>Duration (ms):</label><br>
        <input type="number" name="milliseconds" value="<?= $track['Milliseconds'] ?>" required><br><br>

        <label>Price ($):</label><br>
        <input type="text" name="price" value="<?= $track['UnitPrice'] ?>" required><br><br>

        <button class="aesthetic-button" type="submit">Update Track</button>
    </form>

    <p><a href="album_details.php?id=<?= $track['AlbumId'] ?>">← Back to Album</a></p>
</div>
</body>
</html>
