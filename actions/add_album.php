<?php
session_start();
require_once '../config/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['staff'])) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['album']);              // from name="album"
    $artist_name = trim($_POST['artist']);       // from dropdown
    $track_names = $_POST['tracks'] ?? [];       // array of tracks

    if ($title && $artist_name && is_array($track_names) && count($track_names) > 0) {
        try {
            // 1. Get or Insert Artist
            $stmt = $pdo->prepare("SELECT ArtistId FROM artists WHERE Name = ?");
            $stmt->execute([$artist_name]);
            $artist = $stmt->fetch();

            if ($artist) {
                $artistId = $artist['ArtistId'];
            } else {
                $stmt = $pdo->prepare("INSERT INTO artists (Name) VALUES (?)");
                $stmt->execute([$artist_name]);
                $artistId = $pdo->lastInsertId();
            }

            // 2. Insert Album
            $stmt = $pdo->prepare("INSERT INTO albums (Title, ArtistId) VALUES (?, ?)");
            $stmt->execute([$title, $artistId]);
            $albumId = $pdo->lastInsertId();

            // 3. Insert Tracks
            $trackStmt = $pdo->prepare("INSERT INTO tracks (Name, AlbumId, MediaTypeId, GenreId, Composer, Milliseconds, Bytes, UnitPrice)
                                        VALUES (?, ?, 1, 1, NULL, 200000, 0, 0.99)");

            foreach ($track_names as $track) {
                $track = trim($track);
                if ($track !== '') {
                    $trackStmt->execute([$track, $albumId]);
                }
            }

            set_flash('success', '<i class="fas fa-check-circle" style="color: green;"></i> Album and tracks added.');
        } catch (PDOException $e) {
            set_flash('error', '<i class="fas fa-times-circle" style="color: red;"></i> ' . htmlspecialchars($e->getMessage()));
        }
    } else {
        set_flash('error', '<i class="fas fa-exclamation-circle" style="color: orange;"></i> Please provide valid album, artist, and at least one track.');
    }

    header("Location: ../index.php");
    exit;
}
?>
