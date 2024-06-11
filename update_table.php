<?php
global $conn;
include 'config.php';

// Check if the 'date' column exists and add it if it doesn't
$result = $conn->query("SHOW COLUMNS FROM tours LIKE 'date'");
if ($result->num_rows == 0) {
    $conn->query("ALTER TABLE tours ADD COLUMN date DATE NOT NULL");
    echo "Added 'date' column to 'tours' table.<br>";
} else {
    echo "'date' column already exists in 'tours' table.<br>";
}

$conn->close();
?>
