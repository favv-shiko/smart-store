<?php
$conn = new mysqli("localhost", "username", "password", "database_name");
$result = $conn->query("SELECT AVG(rating) AS average_rating FROM ratings");
$row = $result->fetch_assoc();
echo "متوسط التقييم: " . round($row['average_rating'], 2) . " نجوم.";
?>
