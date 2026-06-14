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
    <h1 class="welcome_title">
        <?php
            if (isset($_SESSION['username'])) {
                echo "Welcome, " . htmlspecialchars($_SESSION['username']) . "!";
            } else {
                echo "Welcome!";
            }
        ?>
    </h1>

    <section class="sections_info">
        <section class="website_info">
            <h2>The Website</h2>
            <p>Welcome to our game world! In this place, you can go around an relax by playingsome of our small,
                yet effective games! <br><br>
                GameVerse is a small website where you can play a few games and even get highscores!<br><br>
                Wouldn't it be fun to race against your friends? Go ahead and give it a try!<br><br>
                And don't forget to share this with your best friends for the best experience!<br><br>
                [GameVerse is not responsible for your account being hacked]</p>
        </section>

        <section class="highscore_info">
            <h2>Latest Highscores</h2>
            <p><strong>[Game_Name]</strong><br>[Username]: [Points]</p>
        </section>
    </section>
</main>

<?php include 'includes/footer.php'; ?>