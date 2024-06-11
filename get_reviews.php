<?php
global $conn;
include 'config.php';

if (isset($_GET['tour_id'])) {
    $tour_id = $_GET['tour_id'];
    $query = "SELECT * FROM reviews WHERE tour_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tour_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }

    echo json_encode($reviews);
}

$conn->close();
?>
