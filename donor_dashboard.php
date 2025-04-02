<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db_connection.php';

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$thankYouMessage = $user['thank_you_message'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Dashboard</title>
    <style>
    /* General body styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f7fc;
    margin: 0;
    padding: 0;
}

.dashboard-container {
    width: 80%;
    margin: 30px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Header Styling */
h2 {
    text-align: center;
    color: #333;
}

/* Section Styling */
section {
    margin-bottom: 30px;
    padding: 20px;
    border-radius: 8px;
    background-color: #f9f9f9;
}

section h3 {
    font-size: 1.5em;
    margin-bottom: 10px;
}

/* User Details Section */
.user-details {
    background-color: #e7f1fe;
    padding: 20px;
    border-radius: 8px;
}

.user-details img.profile-pic {
    max-width: 150px;
    border-radius: 50%;
    margin-bottom: 20px;
}

.user-details p {
    font-size: 1.1em;
    margin: 10px 0;
}

/* Update Profile Button */
.update-profile-button {
    text-align: center;
}

.update-profile-button a {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.update-profile-button a:hover {
    background-color: #45a049;
}

/* Donation History Section */
.donation-history {
    background-color: #e7f7e7;
}

.donation-history table {
    width: 100%;
    border-collapse: collapse;
}

.donation-history th,
.donation-history td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.donation-history th {
    background-color: #4CAF50;
    color: white;
}

.donation-history .status-bar {
    padding: 5px;
    border-radius: 5px;
    text-align: center;
}

.donation-history .status-bar.pending {
    background-color: orange;
    color: white;
}

.donation-history .status-bar.completed {
    background-color: green;
    color: white;
}

.donation-history .status-bar.failed {
    background-color: red;
    color: white;
}

/* Donation Form Section */
.donation-form {
    background-color: #f9f9f9;
}

.donation-form label {
    font-size: 1.1em;
    display: block;
    margin: 10px 0 5px;
}

.donation-form input,
.donation-form select {
    width: 100%;
    padding: 10px;
    font-size: 1em;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.donation-form button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 1.1em;
    cursor: pointer;
}

.donation-form button:hover {
    background-color: #45a049;
}

/* Logout Link */
a {
    display: inline-block;
    margin-top: 20px;
    text-align: center;
    font-size: 1.1em;
    text-decoration: none;
    color: #333;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
}

a:hover {
    background-color: #f1f1f1;
    border-color: #ccc;
}

   </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>

        <?php if (!empty($thankYouMessage)): ?>
            <div class="thank-you-message">
                <p><?php echo htmlspecialchars($thankYouMessage); ?></p>
                <button onclick="dismissThankYou()">Dismiss</button>
            </div>
        <?php endif; ?>

        <section class="user-details">
            <h3>Your Details</h3>
            <img src="uploads/<?php echo $user['profile_pic']; ?>" alt="Profile Picture" class="profile-pic">
            <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>About:</strong> <?php echo $user['about']; ?></p>
        </section>

        <section class="update-profile-button">
            <a href="update_profile.php" class="btn-update-profile">Update Your Profile</a>
        </section>

        <section class="donation-history">
            <h3>Your Donation History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Cause</th>
                        <th>Amount Donated</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt_history = $pdo->prepare("SELECT donations.*, causes.name AS cause_name FROM donations JOIN causes ON donations.cause_id = causes.id WHERE donations.user_id = ?");
                    $stmt_history->execute([$userId]);
                    $donations = $stmt_history->fetchAll();

                    foreach ($donations as $donation):
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($donation['cause_name']); ?></td>
                            <td>$<?php echo number_format($donation['amount'], 2); ?></td>
                            <td>
                                <div class="status-bar <?php echo $donation['status']; ?>">
                                    <?php echo ucfirst($donation['status']); ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="donation-form">
            <h3>Make a Donation</h3>
            <form action="process_donation.php" method="POST" enctype="multipart/form-data">
                <label for="cause">Cause:</label>
                <select name="cause" id="cause" required>
                    <?php
                    $stmt_causes = $pdo->prepare("SELECT * FROM causes");
                    $stmt_causes->execute();
                    $causes = $stmt_causes->fetchAll();
                    foreach ($causes as $cause):
                    ?>
                        <option value="<?php echo $cause['id']; ?>"><?php echo $cause['name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="amount">Amount Donated ($):</label>
                <input type="number" name="amount" id="amount" required step="0.01" min="1">

                <label for="receipt">Upload Receipt:</label>
                <input type="file" name="receipt" id="receipt" accept="image/*">

                <button type="submit" name="donate">Donate</button>
            </form>
        </section>

        <a href="logout.php">Logout</a>
    </div>

    <script>
        function dismissThankYou() {
            document.querySelector('.thank-you-message').style.display = 'none';
        }
    </script>
</body>
</html>









