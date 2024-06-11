<?php
global $conn;
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tour_id = $_POST['tour_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO reviews (user_id, tour_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $tour_id, $rating, $comment);

    if ($stmt->execute()) {
        echo "Review added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">Add Review</h1>
    <form action="add_review.php" method="POST" class="mt-4">
        <div class="form-group">
            <label for="tour_id">Tour</label>
            <select class="form-control" id="tour_id" name="tour_id" required>
                <?php
                $result = $conn->query("SELECT tour_id, title FROM tours");
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['tour_id'] . '">' . $row['title'] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="rating">Rating</label>
            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
        </div>
        <div class="form-group">
            <label for="comment">Comment</label>
            <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Review</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
