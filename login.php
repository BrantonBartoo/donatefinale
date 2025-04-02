<?php
session_start();

// Check if there is any error passed from the login process
$error = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'invalid_credentials') {
        $error = 'Invalid username or password. Please try again.';
    } elseif ($_GET['error'] == 'empty_fields') {
        $error = 'Please fill in all fields.';
    } elseif ($_GET['error'] == 'login_failed') {
        $error = 'Login failed. Please try again later.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        <style>
        /* Basic Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body and Background */
body {
    font-family: Arial, sans-serif;
    background-color: #fff;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Login Container */
#login-form {
    background-color: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 400px;
}

h1 {
    text-align: center;
    margin-bottom: 1rem;
    font-size: 24px;
    color: #333;
}

/* Form */
form {
    display: flex;
    flex-direction: column;
}

/* Form Fields */
input[type="email"],
input[type="password"] {
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

input[type="email"]:focus,
input[type="password"]:focus {
    border-color: #0056b3;
    outline: none;
}

/* Submit Button */
button {
    background-color: #0056b3;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #003d80;
}

/* Error Message */
p {
    color: red;
    font-size: 14px;
    text-align: center;
}

/* Register Link */
#register-link {
    text-align: center;
    margin-top: 1rem;
}

#register-link a {
    color: #0056b3;
    text-decoration: none;
    font-weight: bold;
}

#register-link a:hover {
    text-decoration: underline;
}

    </style>
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Display Error Message if any -->
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="login">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>











