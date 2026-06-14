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
    <h1 class="games-title">Games</h1>

    <section class="games-container" id="games-container">
        <a href="reaction_time_login.php" class="game">
            <h2>Reaction Time</h2>
            <img src="img/GlitchReaction.png" alt="Reaction Time">
            <p>Test your reaction timing!</p>
        </a>

        <a href="space_invaders_login.php" class="game">
            <h2>Space Invaders</h2>
            <img src="img/space_invaders.png" alt="Space Invaders">
            <p>Save the universe from the attacking Aliëns!</p>
        </a>

        <a href="hotdog_clicker_login.php" class="game">
            <h2>Hotdog Clicker</h2>
            <img src="img/hotdog.png" alt="Hotdog Clicker">
            <p>Try to gather as many hotdogs as you can!</p>
        </a>
    </section>
</main>

<?php include 'includes/footer.php'; ?>