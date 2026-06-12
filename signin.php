<?php
session_start();
require "database.php";

if (isset($_POST['submit'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm = trim($_POST['confirm']);

    if ($username === '' || $password === '' || $confirm === '') {
        $error = "Please fill in all fields.";
    } 
    elseif ($password !== $confirm) {
        $error = "Passwords don't match.";
    } 
    else {

        $stmt = $conn->prepare("SELECT * FROM gebruikers WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "This user already exists.";
        } else {

            $stmt = $conn->prepare("INSERT INTO gebruikers (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();

            $success = "Account made successfully!";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<main>

    <h1>Sign In</h1>

    <?php if (isset($error)) : ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if (isset($success)) : ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        Username:<br>
        <input type="text" name="username" id="username"><br><br>

        Password:<br>
        <input type="password" name="password" id="password"><br><br>

        Confirm Password:<br>
        <input type="password" name="confirm" id="confirm"><br><br>

        <input type="submit" name="submit" value="Sign In" id="submit">
    </form>

</main>

<style>
    h1 {
        padding: 20px;
    }

    form {
        padding: 80px;
    }

    #username, #password, #confirm {
        border-radius: 4px;
    }

    #submit {
        padding: 0.2vw 0.4vw;
        border-radius: 4px;
        cursor: pointer;
    }
</style>

<?php include 'includes/footer.php'; ?>
