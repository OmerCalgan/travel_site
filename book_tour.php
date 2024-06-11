<?php
global $conn;
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = 1; // Example user_id, replace with session or actual user data
    $tour_id = $_POST['tour_id'];

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, tour_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $tour_id);

    if ($stmt->execute()) {
        echo "Booking successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
