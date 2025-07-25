<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Invalid album ID</p>";
    exit;
}

$albumId = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT a.Title AS AlbumTitle, ar.Name AS ArtistName
    FROM albums a
    JOIN artists ar ON a.ArtistId = ar.ArtistId
    WHERE a.AlbumId = ?
");
$stmt->execute([$albumId]);
$album = $stmt->fetch();

if (!$album) {
    echo "<p>Album not found.</p>";
    exit;
}

$trackStmt = $pdo->prepare("
    SELECT t.TrackId, t.Name AS TrackName, t.Composer, t.Milliseconds, t.UnitPrice,
           g.Name AS Genre, m.Name AS MediaType
    FROM tracks t
    LEFT JOIN genres g ON t.GenreId = g.GenreId
    LEFT JOIN media_types m ON t.MediaTypeId = m.MediaTypeId
    WHERE t.AlbumId = ?
    ORDER BY t.Name
");

$trackStmt->execute([$albumId]);
$tracks = $trackStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Album Details</title>
    <link rel="stylesheet" href="public/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<video autoplay muted loop id="bg-video">
    <source src="public/music-bg.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>
<body style="font-family: 'Fira Sans', sans-serif;">
    <!--for video-->
<div id="overlay"></div>
<div class="container">
    <h1><i class="fas fa-compact-disc"></i> <?= htmlspecialchars($album['AlbumTitle']) ?></h1>
    <h2>by <?= htmlspecialchars($album['ArtistName']) ?></h2>

    <?php if ($tracks): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Track</th>
                    <th>Composer</th>
                    <th>Duration</th>
                    <th>Genre</th>
                    <th>Media Type</th>
                    <th>Price ($)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tracks as $track): ?>
                    <tr>
                        <td><?= htmlspecialchars($track['TrackName']) ?></td>
                        <td><?= htmlspecialchars($track['Composer'] ?? '—') ?></td>
                        <td><?= round($track['Milliseconds'] / 60000, 2) ?> min</td>
                        <td><?= htmlspecialchars($track['Genre'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($track['MediaType'] ?? '—') ?></td>
                        <td><?= number_format($track['UnitPrice'], 2) ?></td>
              <td style="text-align: center;">
    <div style="display: flex; gap: 12px; justify-content: center;">
  <a href="edit_track_form.php?id=<?= $track['TrackId'] ?>&album_id=<?= $albumId ?>" title="Edit Track">
    <i class="fas fa-pen-to-square" style="font-size: 18px; color: #007BFF;"></i>
</a>

        <a href="actions/delete_track.php?id=<?= $track['TrackId'] ?>" 
           onclick="return confirm('Delete this track?')" 
           title="Delete Track">
            <i class="fas fa-trash" style="font-size: 18px; color: #e74c3c;"></i>
        </a>
    </div>
</td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No tracks found for this album.</p>
    <?php endif; ?>

    <p><a href="index.php"><i class="fas fa-arrow-left"></i> Back to Album List</a></p>
</div>
</body>
</html>
