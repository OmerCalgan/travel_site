<?php
global $conn;
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT username, email FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Panel</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">User Panel</h1>
    <div class="row mt-4">
        <div class="col-md-6">
            <h3>Profile Information</h3>
            <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div class="col-md-6">
            <h3>Your Bookings</h3>
            <?php
            $query = "SELECT * FROM bookings JOIN tours ON bookings.tour_id = tours.tour_id WHERE bookings.user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row['title'] . '</h5>';
                echo '<p class="card-text">Booking Date: ' . $row['booking_date'] . '</p>';
                echo '<p class="card-text">Status: ' . $row['status'] . '</p>';
                echo '</div></div>';
            }

            $stmt->close();
            ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
