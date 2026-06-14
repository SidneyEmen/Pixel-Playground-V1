<?php
    session_start();
    require "database.php";

    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Gebruiker ophalen
        $stmt = $conn->prepare("SELECT * FROM gebruikers WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Wachtwoord controleren (nu nog plain-text)
            if ($password === $user['password']) {
                $_SESSION['username'] = $user['username'];
                header("Location: index_login.php");
                exit;
            } else {
                $error = "Password doesn't match this user.";
            }
        } else {
            $error = "User not found.";
        }
    }
?>

<?php include 'includes/header.php'; ?>

<main>

    <h1>Log In</h1>

    <?php if (isset($error)) : ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        Username:<br>
        <input type="text" name="username" id="username"><br><br>

        Password:<br>
        <input type="password" name="password" id="password"><br><br>

        <input type="submit" name="submit" value="Log In" id="submit">
    </form>

</main>

<style>
    
    h1 {
        padding: 20px;
    }

    form {
        padding: 80px;
    }

    #username, #password {
        border-radius: 4px;
    }

    #submit {
        padding: 0.2vw 0.4vw;
        border-radius: 4px;
        cursor: pointer;
    }

</style>

<?php include 'includes/footer.php'; ?>