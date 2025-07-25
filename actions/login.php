<?php
session_start();
require_once '../config/db.php'; // gives you $pdo

// Get form data
$username = trim($_POST['username']);
$password = $_POST['password'];


try {
    // $stmt = $pdo->prepare("SELECT * FROM staff WHERE username = ?");
    // $stmt->execute([$username]);
    // $staff = $stmt->fetch();

    if (true) {
        // if (password_verify($password, $staff['password'])) {
        if (true) { // using this condition for testing only
            $_SESSION['staff'] = 'root';
            header("Location: ../index.php");
            exit;
        } else {
            header("Location: ../index.php?error=invalid");
            exit;
        }
    } else {
        header("Location: ../index.php?error=user-not-found");
        exit;
    }

} catch (PDOException $e) {
    echo "Login failed: " . $e->getMessage();
}

?>






























