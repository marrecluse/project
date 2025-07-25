<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['staff']) || !isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$albumId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM albums WHERE AlbumId = ?");
$stmt->execute([$albumId]);
$album = $stmt->fetch();

if (!$album) {
    set_flash('error', 'Album not found.');
    header("Location: index.php");
    exit;
}

$artists = $pdo->query("SELECT ArtistId, Name FROM artists ORDER BY Name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Album</title>
    <link rel="stylesheet" href="public/style.css">
</head>
<video autoplay muted loop id="bg-video">
    <source src="public/music-bg.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>
<body>
    <!--for video-->
<div id="overlay"></div>
<div class="container">
    <h2>Edit Album</h2>
    <form action="actions/update_album.php" method="post">
        <input type="hidden" name="album_id" value="<?= $album['AlbumId'] ?>">

        <label>Album Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($album['Title']) ?>" required><br>

        <label>Artist:</label>
        <select name="artist_id" required>
            <?php foreach ($artists as $artist): ?>
                <option value="<?= $artist['ArtistId'] ?>" <?= $album['ArtistId'] == $artist['ArtistId'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($artist['Name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <input type="submit" value="Update Album">
    </form>
</div>
</body>
</html>
