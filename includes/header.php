<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">TravelSite</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<li class="nav-item"><a class="nav-link" href="user_panel.php">User Panel</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="add_review.php">Add Review</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
            } else {
                echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>
