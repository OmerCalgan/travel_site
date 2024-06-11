<?php global $conn;
include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Details</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-5" id="tour-details" data-tour-id="<?php echo $_GET['tour_id']; ?>
    <?php
    if (isset($_GET['tour_id'])) {
        $tour_id = $_GET['tour_id'];
        $query = "SELECT * FROM tours WHERE tour_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $tour_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo '<h1 class="text-center">' . $row['title'] . '</h1>';
            echo '<p class="text-center">' . $row['description'] . '</p>';
            echo '<p class="text-center"><strong>Price: $' . $row['price'] . '</strong></p>';
            echo '<p class="text-center">Start Date: ' . $row['start_date'] . '</p>';
            echo '<p class="text-center">End Date: ' . $row['end_date'] . '</p>';
            echo '<a href="user_panel.php" class="btn btn-primary">Book This Tour</a>';
        } else {
            echo '<p class="text-center">Tour not found.</p>';
        }

        $stmt->close();
    } else {
        echo '<p class="text-center">No tour selected.</p>';
    }

    $conn->close();
    ?>

    <div class="mt-5">
        <h3>Reviews</h3>
        <div id="reviews">
            <!-- Yorumlar buraya yüklenecek -->
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
