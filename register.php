<?php
// Database connection
$host = 'localhost';
$db = 'donation_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    if ($stmt->execute([$email, $hashedPassword])) {
        // Redirect to login page after successful registration
        header("Location: login.php");
        exit;
    } else {
        $error = "Registration failed, please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Donate</title>
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

/* Registration Form Container */
#register-form {
    background-color: #fff;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 2, 0.4);
    width: 100%;
    max-width: 400px;
    transition: all 0.3s ease-in-out;
}

/* Change cursor when hovering over the form container */
#register-form:hover {
    cursor: pointer;
    transform: scale(1.05);
}

/* Heading */
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
    transition: border-color 0.3s ease-in-out;
}

/* Focused Input Fields */
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
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #003d80;
    transform: scale(1.05);
}

/* Error Message */
p {
    color: red;
    font-size: 14px;
    text-align: center;
}

/* Register Link */
#login-link {
    text-align: center;
    margin-top: 1rem;
}

#login-link a {
    color: #0056b3;
    text-decoration: none;
    font-weight: bold;
}

#login-link a:hover {
    text-decoration: underline;
}
  
    </style>
</head>
<body>
    

    <main>
        <section id="register-form">
            <h2>Register to Get Started</h2>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <label for="email">Email:</label>
                <input type="email" name="email" required><br>
                <label for="password">Password:</label>
                <input type="password" name="password" required><br>
                <button type="submit">Register</button>
            </form>

            <!-- Link to Login Page -->
            <div id="login-link">
                <p>Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </section>
    </main>
</body>
</html>
