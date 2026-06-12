<?php
session_start();
require "database.php";

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$currentUser = $_SESSION['username'] ?? null;

$message = ''; // melding opslaan

// Vriend toevoegen
if (isset($_POST['add_friend'])) {
    $receiver = trim($_POST['friend_name']);

    if ($receiver === '') {
        $message = "<p style='color:red;'>Type in a username.</p>";
    } else {
        // Controle: bestaat deze gebruiker?
        $checkUser = $conn->prepare("SELECT username FROM gebruikers WHERE username = ?");
        $checkUser->bind_param("s", $receiver);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows === 0) {
            $message = "<p style='color:red;'>User '$receiver' doesn't exist.</p>";
        } else {
            // Controle: bestaat er al een verzoek?
            $checkRequest = $conn->prepare("SELECT id FROM friend_requests WHERE sender = ? AND receiver = ? AND status = 'pending'");
            $checkRequest->bind_param("ss", $currentUser, $receiver);
            $checkRequest->execute();
            $existing = $checkRequest->get_result();

            if ($existing->num_rows > 0) {
                $message = "<p style='color:orange;'>You already sent an friend request to '$receiver'.</p>";
            } else {
                $stmt = $conn->prepare("INSERT INTO friend_requests (sender, receiver, status) VALUES (?, ?, 'pending')");
                $stmt->bind_param("ss", $currentUser, $receiver);
                $stmt->execute();
                $message = "<p style='color:green;'>Friend request was sent to $receiver!</p>";
            }
        }
    }
}

// Verzoek accepteren
if (isset($_POST['accept'])) {
    $sender = $_POST['sender'];
    $conn->query("UPDATE friend_requests SET status='accepted' WHERE sender='$sender' AND receiver='$currentUser'");
    $conn->query("INSERT INTO friends (user, friend) VALUES ('$currentUser', '$sender'), ('$sender', '$currentUser')");
}

// Verzoek weigeren
if (isset($_POST['deny'])) {
    $sender = $_POST['sender'];
    $conn->query("DELETE FROM friend_requests WHERE sender='$sender' AND receiver='$currentUser'");
}

// Ontvangen verzoeken
$received = $conn->query("SELECT sender FROM friend_requests WHERE receiver='$currentUser' AND status='pending'");

// Verzonden verzoeken
$sent = $conn->query("SELECT receiver FROM friend_requests WHERE sender='$currentUser' AND status='pending'");
?>

<?php include 'includes/header_login.php'; ?>

<main>
    <h1>Friends</h1>

    <section class="friends-layout">
        <article id="left">
            <h2>Users:</h2><br>
            <form action="" method="POST">
                <input type="text" name="friend_name" placeholder="Username">
                <input type="submit" name="add_friend" value="Add" id="add_user">
            </form>
            <?= $message ?>
        </article>

        <article id="middle">
            <h2>Friend Requests Receive:</h2>
            <?php while ($row = $received->fetch_assoc()) : ?>
                <p><?= htmlspecialchars($row['sender']); ?></p>
                <form method="POST">
                    <input type="hidden" name="sender" value="<?= htmlspecialchars($row['sender']); ?>">
                    <button name="accept">Accept</button>
                    <button name="deny">Deny</button>
                </form>
            <?php endwhile; ?>
        </article>

        <article id="right">
            <h2>Friend Requests Send:</h2>
            <?php while ($row = $sent->fetch_assoc()) : ?>
                <p><?= htmlspecialchars($row['receiver']); ?></p>
            <?php endwhile; ?>
        </article>
    </section>
</main>

<style>
    h1 {
        padding: 20px;
        text-align: center;
    }

    .friends-layout {
        display: flex;
        justify-content: space-between;
        text-align: center;
        padding: 100px;
    }

    article {
        width: 30%;
    }

    input[type="text"] {
        border-radius: 4px;
        padding: 0.2vw 0vw;
    }

    input[type="submit"], button {
        border-radius: 4px;
        cursor: pointer;
        padding: 0.2vw 0.4vw;
    }

    p {
        margin-top: 10px;
    }
</style>

<?php include 'includes/footer.php'; ?>
