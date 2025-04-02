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

// Fetch all causes
$stmt = $pdo->prepare("SELECT * FROM causes");
$stmt->execute();
$causes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Now - Support Causes</title>
    <style>
    /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body and General Layout */
body {
    font-family: 'Arial', sans-serif;
    background-color: #ffffff;
    color: #333;
    line-height: 1.6;
    padding: 0;
    margin: 0;
}

header {
    background-color: #003366;
    color: #fff;
    padding: 20px 0;
    text-align: center;
}

.navbar h1 {
    font-size: 2.5rem;
    letter-spacing: 1px;
    font-weight: bold;
}

.navbar nav ul {
    display: flex;
    justify-content: center;
    list-style-type: none;
    padding: 0;
    margin-top: 20px;
}

.navbar nav ul li {
    margin: 0 20px;
}

.navbar nav ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 1.1rem;
    padding: 5px 0;
    transition: color 0.3s ease, text-decoration 0.3s ease;
}

.navbar nav ul li a:hover {
    color: #ff9900;
    text-decoration: underline;
}

/* Main Container for Causes */
main {
    padding: 60px 15%;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
}

h2 {
    font-size: 2.5rem;
    color: #003366;
    margin-bottom: 40px;
    font-weight: bold;
}

/* Flexbox Layout for the Cause Cards */
.cause-container {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}

/* Individual Cause Cards */
.cause-card {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
    width: 22%;  /* Adjusts the width of each cause card */
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer; /* Indicates interactivity */
}

.cause-card h3 {
    font-size: 1.8rem;
    color: #003366;
    margin-bottom: 15px;
}

.cause-card p {
    font-size: 1rem;
    color: #555;
    margin-bottom: 20px;
}

.donate-btn {
    display: inline-block;
    padding: 12px 25px;
    background-color: #ff9900;
    color: white;
    text-align: center;
    text-decoration: none;
    font-size: 1.1rem;
    border-radius: 30px;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.donate-btn:hover {
    background-color: #e68a00;
    transform: scale(1.05);
}

/* Hover Effects for the Cause Cards */
.cause-card:hover {
    background-color: #e0f7fa;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

/* Footer */
footer {
    text-align: center;
    padding: 20px 0;
    background-color: #003366;
    color: white;
    font-size: 1rem;
    position: relative;
    bottom: 0;
    width: 100%;
}

   </style>
</head>
<body>
    <header>
        <div class="navbar">
            <h1>Support Causes That Matter</h1>
            <nav>
                <ul>
                    <li><a href="#education">Education</a></li>
                    <li><a href="#healthcare">Healthcare</a></li>
                    <li><a href="#environment">Environment</a></li>
                    <li><a href="#animals">Animal Welfare</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="causes">
            <div class="container">
                <h2>Choose a Cause to Support</h2>
                <div class="cause-container">
                    <?php foreach ($causes as $cause): ?>
                        <div class="cause-card">
                            <img src="<?= $cause['image'] ?>" alt="Cause Image" style="width:100%; height:200px; object-fit:cover;">
                            <h3><?= htmlspecialchars($cause['title']) ?></h3>
                            <p><?= htmlspecialchars($cause['description']) ?></p>
                            <!-- Redirect to login page -->
                            <a href="login.php" class="donate-btn">Donate Now</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 DonationApp - All rights reserved</p>
    </footer>
</body>
</html>













