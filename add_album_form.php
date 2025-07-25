<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isset($_SESSION['staff'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->query("SELECT ArtistId, Name FROM artists ORDER BY Name");
$artists = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add New Album</title>
    <link rel="stylesheet" href="public/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <script>
        function addTrackField() {
            const container = document.getElementById("trackFields");
            const index = container.children.length + 1;
            const div = document.createElement("div");
            div.className = "track-group";
            div.innerHTML = `<label>Track ${index}:</label>
                <input type="text" name="tracks[]" required>
                <button class="aesthetic-button" type="button" onclick="removeTrackField(this)"> <i class="fa fa-times" aria-hidden="true"></i></button>`;
            container.appendChild(div);
        }

        function removeTrackField(btn) {
            btn.parentElement.remove();
        }

        window.onload = function () {
            addTrackField(); // Load one track by default
        };
    </script>
</head>
<video autoplay muted loop id="bg-video">
    <source src="public/music-bg.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>
<body>
      
<!--for video-->
<div id="overlay"></div>
<div class="container">
    <h2>Add New Album</h2>

    <?php display_flash(); ?>

    <form action="actions/add_album.php" method="post">
        <label>Album Title:</label><br>
        <input type="text" name="album" required><br>

        <label>Artist:</label><br>
        <select name="artist" required>
            <option value="">--Select Artist--</option>
            <?php foreach ($artists as $artist): ?>
                <option value="<?= $artist['Name'] ?>"><?= htmlspecialchars($artist['Name']) ?></option>
            <?php endforeach; ?>
        </select><br>

        <div id="trackFields"></div>
<button class="aesthetic-button" type="button" onclick="addTrackField()">
  <i class="fa fa-plus" aria-hidden="true"></i> Add Track
</button><br><br>

        <input type="submit" value="Add Album">
    </form>
</div>
</body>
</html>
