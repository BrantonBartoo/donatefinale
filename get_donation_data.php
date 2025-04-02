<?php
require 'db_connection.php';
$stmt = $pdo->query("SELECT causes.title AS cause, SUM(donations.amount) AS total FROM donations
                     JOIN causes ON donations.cause_id = causes.id GROUP BY donations.cause_id");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$causes = [];
$amounts = [];
foreach ($data as $row) {
    $causes[] = $row['cause'];
    $amounts[] = $row['total'];
}

echo json_encode(['causes' => $causes, 'amounts' => $amounts]);
?>
