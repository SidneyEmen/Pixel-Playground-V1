<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Een korte beschrijving...">
    <meta name="keywords" content="HTML, meta tags, voorbeeld, webontwikkeling">
    <meta name="author" content="Farich & Sidney">
    <title>GameVerse</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/PixelPlayground.css?v=<?php echo time(); ?>">
    <script src="js/PixelPlayground.js" defer></script>
    <link rel="icon" href="img/logo_gameverse.png" type="image/default.png">
</head>

<body>
<header>
    <img src="img/logo_gameverse.png" alt="GameVerse Logo">

    <nav>
        <a href="index_login.php">Home</a>
        <a href="gamepage_login.php">Games</a>
        <a href="profile_login.php">Profile</a>
        <a href="friends_login.php">Friends</a>
        <a href="highscores_login.php">Highscores</a>

        <a href="?logout=true" style="color:red; font-weight:bold;">Log out</a>
    </nav>
</header>
