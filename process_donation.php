<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['donate'])) {
    $userId = $_SESSION['user_id'];
    $causeId = $_POST['cause'];
    $amount = $_POST['amount'];

    // Handle file upload for receipt
    $receiptName = null;
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] == 0) {
        $receiptName = uniqid() . '-' . $_FILES['receipt']['name'];
        move_uploaded_file($_FILES['receipt']['tmp_name'], 'uploads/' . $receiptName);
    }

    // Insert donation into the database
    $stmt = $pdo->prepare("INSERT INTO donations (user_id, cause_id, amount, receipt_image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $causeId, $amount, $receiptName]);

    // Redirect back to the dashboard
    header('Location: donor_dashboard.php');
    exit;
}
