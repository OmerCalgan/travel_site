<?php
global $conn;
include 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$query = "SELECT * FROM tours";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelSite - Home</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">Available Tours</h1>
    <div class="row mt-4">
        <?php while ($tour = $result->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $tour['title']; ?></h5>
                        <p class="card-text"><?php echo substr($tour['description'], 0, 100); ?>...</p>
                        <p class="card-text"><strong>Price:</strong> $<?php echo $tour['price']; ?></p>
                        <p class="card-text"><strong>Date:</strong> <?php echo $tour['date']; ?></p>
                        <a href="tour.php?tour_id=<?php echo $tour['tour_id']; ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>
