<?php
session_start();
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Chinook Album Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="public/style.css">
    <link rel="icon" href="public/favicon.png" type="image/png">
    <script src="public/script.js" defer></script></head>
<video autoplay muted loop id="bg-video">
    <source src="public/music-bg.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<body>
  
<!--for video-->
<div id="overlay"></div>

<!-- for snackbar -->
<div id="snackbar"></div>

  <div class="container">
    <h1>Chinook Album Management System</h1>

    <?php if (!isset($_SESSION['staff'])): ?>
      <button type="button" onclick="alert('Username: root \n Password: root')" title="Show test credentials">
              <i class="fas fa-info-circle" style="font-size: 18px; color: #007BFF;"></i>
</button>
      <h2>Staff Login</h2>
      <form action="actions/login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Login">
      </form>
      <p><a href="forgot_password.php">Forgot password?</a></p>


    <?php else:
        display_flash(); ?>
        <p style="display: flex; justify-content: space-between; align-items: center;">
    <span>Welcome, <strong><?= htmlspecialchars($_SESSION['staff']) ?></strong></span>
            <a href="logout.php" title="Logout">
                <i class="fas fa-sign-out-alt" style="font-size: 20px;"></i>
            </a>
        </p>

      <h2>Manage Albums</h2>
        <a href="add_album_form.php"><i class="fas fa-plus-circle"></i> Add New Album</a>
      <?php
      // Connect to DB
      require_once 'config/db.php';

        $limit = 30; // albums per page
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;

// Fetch total count for pagination
$total_stmt = $pdo->query("SELECT COUNT(*) FROM albums");
$total_albums = $total_stmt->fetchColumn();
$total_pages = ceil($total_albums / $limit);

// Fetch paginated results
$stmt = $pdo->prepare("SELECT albums.*, artists.Name AS ArtistName
                       FROM albums
                       JOIN artists ON albums.ArtistId = artists.ArtistId
                       ORDER BY albums.Title
                       LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$albums = $stmt->fetchAll();


//        echo "<pre>";
//        print_r($albums); // for debugging
//        echo "</pre>";


    // For displaying in table:
    if ($albums) {
 

      echo '<table border="1">
        <tr><th>Title (A-Z)</th><th>Artist</th><th>Actions</th></tr>';

foreach ($albums as $row) {
    echo '<tr>
            <td>' . htmlspecialchars($row['Title']) . '</td>
            <td>' . htmlspecialchars($row['ArtistName']) . '</td>
            <td style="text-align: center;">
              
            <div style="display: flex"> 
               <a href="album_details.php?id=' . $row['AlbumId'] . '" title="Details">
              <i class="fas fa-info-circle" style="font-size: 18px; margin-right: 12px; color: #007BFF;"></i>
              </a>


              <a href="edit_album_form.php?id=' . $row['AlbumId'] . '" title="Edit">
                <i class="fas fa-pen-to-square" style="font-size: 18px; margin-right: 12px; color: #007BFF;"></i>
              </a>
              <a href="actions/delete_album.php?id=' . $row['AlbumId'] . '" onclick="return confirm(\'Delete this album?\')" title="Delete">
                <i class="fas fa-trash" style="font-size: 18px; color: #e74c3c;"></i>
            </div>
         
            </td>
          </tr>';
}

echo '</table>';
echo '<div style="margin-top: 20px;">';

if ($page > 1) {
    echo '<a href="?page=' . ($page - 1) . '">« Prev</a> ';
}

echo ' Page ' . $page . ' of ' . $total_pages . ' ';

if ($page < $total_pages) {
    echo '<a href="?page=' . ($page + 1) . '">Next »</a>';
}

echo '</div>';

    } else {
        echo "<p>No albums found.</p>";
    }

      ?>

    <?php endif; ?>
  </div>


</body>
</html>





















