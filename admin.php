<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

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

// Add cause functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_cause'])) {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // File upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image = $target_file;
    } else {
        $image = 'uploads/default_image.jpg';
    }

    $stmt = $pdo->prepare("INSERT INTO causes (name, title, description, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $title, $description, $image]);
    header('Location: admin.php');
    exit;
}

// Delete cause functionality
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM causes WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin.php');
    exit;
}

// Verify donation functionality
if (isset($_GET['verify'])) {
    $id = $_GET['verify'];
    $stmt = $pdo->prepare("UPDATE donations SET status = 'verified' WHERE id = ?");
    $stmt->execute([$id]);
    
    // Insert a thank-you message for the donor
    $stmt = $pdo->prepare("UPDATE users SET thank_you_message = 'Thank you for donating!' WHERE id = (SELECT user_id FROM donations WHERE id = ?)");
    $stmt->execute([$id]);
    
    header('Location: admin.php');
    exit;
}

// Fetch all donations
$stmt = $pdo->prepare("SELECT donations.*, users.name AS donor_name, causes.title AS cause_name FROM donations 
                        JOIN users ON donations.user_id = users.id 
                        JOIN causes ON donations.cause_id = causes.id");
$stmt->execute();
$donations = $stmt->fetchAll();

// Fetch all causes
$stmt_causes = $pdo->prepare("SELECT * FROM causes");
$stmt_causes->execute();
$causes = $stmt_causes->fetchAll();

// Fetch donation analytics data
$stmt_analytics = $pdo->prepare("SELECT causes.title AS cause, SUM(donations.amount) AS total FROM donations JOIN causes ON donations.cause_id = causes.id GROUP BY causes.title");
$stmt_analytics->execute();
$analytics_data = $stmt_analytics->fetchAll(PDO::FETCH_ASSOC);
$causes_json = json_encode(array_column($analytics_data, 'cause'));
$totals_json = json_encode(array_column($analytics_data, 'total'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Donations</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #donations {
    margin-top: 30px;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

#donations h2 {
    text-align: center;
    color: #0056b3;
}

#donations table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

#donations th, #donations td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

#donations th {
    background-color: #0056b3;
    color: white;
}

#donations .verify-btn {
    background: green;
    color: white;
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 5px;
}

#donations .verify-btn:hover {
    background: darkgreen;
}
    </style>
</head>
<body>
<header>
    <h1>Admin Panel - Manage Donations</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
</header>

<main>
    <section id="add-cause">
        <h2>Add a New Cause</h2>
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <label for="name">Cause Name:</label>
            <input type="text" name="name" required><br>
            <label for="title">Cause Title:</label>
            <input type="text" name="title" required><br>
            <label for="description">Cause Description:</label>
            <textarea name="description" required></textarea><br>
            <label for="image">Cause Image:</label>
            <input type="file" name="image" accept="image/*" required><br>
            <button type="submit" name="add_cause">Add Cause</button>
        </form>
    </section>
    <section id="existing-causes">
            <h2>Existing Causes</h2>
            <div class="cause-container">
                <?php foreach ($causes as $cause): ?>
                    <div class="cause-card">
                        <img src="<?= $cause['image'] ?>" alt="Cause Image" style="width:100%; height:200px; object-fit:cover;">
                        <h3><?= htmlspecialchars($cause['name']) ?></h3>
                        <h4><?= htmlspecialchars($cause['title']) ?></h4>
                        <p><?= htmlspecialchars($cause['description']) ?></p>
                        <a href="admin.php?delete=<?= $cause['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this cause?');">Delete</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <section id="donations">
        <h2>All Donations</h2>
        <table border="1">
            <tr>
                <th>Donor</th>
                <th>Cause</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($donations as $donation): ?>
                <tr>
                    <td><?= htmlspecialchars($donation['donor_name']) ?></td>
                    <td><?= htmlspecialchars($donation['cause_name']) ?></td>
                    <td>$<?= htmlspecialchars($donation['amount']) ?></td>
                    <td><?= htmlspecialchars($donation['status']) ?></td>
                    <td>
                        <?php if ($donation['status'] !== 'verified'): ?>
                            <a href="admin.php?verify=<?= $donation['id'] ?>" class="verify-btn">Verify</a>
                        <?php else: ?>
                            Verified
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <section id="analytics">
        <h2>Donation Analytics</h2>
        <canvas id="donationChart"></canvas>
    </section>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('donationChart').getContext('2d');
        var donationChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $causes_json ?>,
                datasets: [{
                    label: 'Total Donations',
                    data: <?= $totals_json ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

</body>
</html>
