<?php
global $conn;
include 'config.php';

// Example tours
$tours = [
    ['title' => 'Paris Sightseeing', 'description' => 'Enjoy the beautiful sights of Paris.', 'price' => 500, 'date' => '2024-07-01'],
    ['title' => 'Rome Historical Tour', 'description' => 'Explore the ancient history of Rome.', 'price' => 450, 'date' => '2024-08-15'],
    ['title' => 'New York City Highlights', 'description' => 'Discover the vibrant life of NYC.', 'price' => 600, 'date' => '2024-09-10'],
    ['title' => 'Tokyo Modern Wonders', 'description' => 'Experience the modern wonders of Tokyo.', 'price' => 700, 'date' => '2024-10-05'],
    ['title' => 'Sydney Beach and Surf', 'description' => 'Relax on the beautiful beaches of Sydney.', 'price' => 550, 'date' => '2024-11-20'],
];

foreach ($tours as $tour) {
    $stmt = $conn->prepare("INSERT INTO tours (title, description, price, date, start_date, end_date) VALUES (?, ?, ?, ?, NOW(), NOW())");

    $stmt->bind_param("ssis", $tour['title'], $tour['description'], $tour['price'], $tour['date']);

    if ($stmt->execute()) {
        echo "Added tour: " . $tour['title'] . "<br>";
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }

    $stmt->close();
}

$conn->close();
?>
