<?php
session_start();
include 'db_connection.php';

// Check if the form is submitted
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($email) || empty($password)) {
        // Redirect to login page with error for empty fields
        header('Location: login.php?error=empty_fields');
        exit;
    }

    // Fetch user details from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Check if the user exists and if the password matches
    if ($user && password_verify($password, $user['password'])) {
        // Start user session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email']; // Optionally store email
        $_SESSION['user_profile_pic'] = $user['profile_pic']; // Optionally store profile pic

        // Redirect to the user's dashboard
        header('Location: donor_dashboard.php');
        exit;
    } else {
        // Invalid credentials
        header('Location: login.php?error=invalid_credentials');
        exit;
    }
}
?>
