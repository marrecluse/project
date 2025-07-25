<?php
// forgot_password.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Reset Staff Password</title>
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
  <h2>Reset Staff Password</h2>
  <form action="actions/reset_password.php" method="post">
    <label for="username">Staff Username:</label>
    <input type="text" name="username" required>

    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" required>

    <input type="submit" value="Reset Password">
  </form>
  <p><a href="index.php">Back to Login</a></p>
</div>
</body>
</html>
