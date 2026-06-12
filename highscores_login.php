<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<?php include 'includes/header_login.php'; ?>

<main>

    <h1>Highscores</h1>

</main>

<?php include 'includes/footer.php'; ?>