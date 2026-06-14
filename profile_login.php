<?php
session_start();
require "database.php";

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$currentUser = $_SESSION['username'] ?? null;

if (isset($_POST['submit_password'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $error = "Passwords don't match.";
    } else {
        $stmt = $conn->prepare("UPDATE gebruikers SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $newPassword, $currentUser);
        $stmt->execute();
        $success = "Password successfully changed!";
    }
}

if (isset($_POST['submit_username'])) {
    $newUsername = $_POST['new_username'];

    $stmt = $conn->prepare("UPDATE gebruikers SET username = ? WHERE username = ?");
    $stmt->bind_param("ss", $newUsername, $currentUser);
    $stmt->execute();

    $_SESSION['username'] = $newUsername;
    $success = "Username successfully changed!";
}

// ACCOUNT DELETE
if (isset($_POST['submit_delete'])) {

    $del1 = $conn->prepare("DELETE FROM friends WHERE user = ? OR friend = ?");
    $del1->bind_param("ss", $currentUser, $currentUser);
    $del1->execute();

    $del2 = $conn->prepare("DELETE FROM friend_requests WHERE sender = ? OR receiver = ?");
    $del2->bind_param("ss", $currentUser, $currentUser);
    $del2->execute();

    $del3 = $conn->prepare("DELETE FROM gebruikers WHERE username = ?");
    $del3->bind_param("s", $currentUser);
    $del3->execute();

    session_destroy();
    header("Location: login.php");
    exit;
}

$friends = $conn->prepare("SELECT friend FROM friends WHERE user = ?");
$friends->bind_param("s", $currentUser);
$friends->execute();
$friendsResult = $friends->get_result();
?>

<?php include 'includes/header_login.php'; ?>

<main>

    <h1>Profile data:</h1>
    <p>Username: <strong><?= htmlspecialchars($_SESSION['username']); ?></strong></p>

    <?php if (isset($error)) : ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <?php if (isset($success)) : ?>
        <p style="color:green;"><?= $success ?></p>
    <?php endif; ?>

    <form action="" method="POST" id="form_password">
        <h2>Change Password:</h2><br>
        New Password:<br>
        <input type="password" name="new_password" id="new_password"><br><br>

        Confirm New Password:<br>
        <input type="password" name="confirm_password" id="confirm_password"><br><br>

        <input type="submit" name="submit_password" value="Change" id="submit">
    </form>

    <form action="" method="POST" id="form_delete" onsubmit="return confirmDelete();">
        <h2>Delete Account:</h2>
        <p style="color:red;">This will permanently delete this account.</p><br>
        <input type="submit" name="submit_delete" value="Delete Account" id="delete_btn">
    </form>

    <form action="" method="POST" id="form_username">
        <h2>Change Username:</h2><br>
        New Username:<br>
        <input type="text" name="new_username" id="new_username"><br><br>

        <input type="submit" name="submit_username" value="Change" id="submit">

        <section id="friends_list">
            <h2>Friends:</h2>
            <?php if ($friendsResult->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $friendsResult->fetch_assoc()): ?>
                        <li><?= htmlspecialchars($row['friend']); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>[You currently don't have any friends.]</p>
            <?php endif; ?>
        </section>
    </form>

</main>

<style>

main {
    display: flex;
    flex-direction: column;
    padding: 60px;
}

#form_password, #form_username, #form_delete {
    display: inline-block;
    vertical-align: top;
    text-align: left;
}

#form_password {
    position: absolute;
    left: 10%;
    top: 220px;
}

#form_delete {
    position: absolute;
    left: 10%;
    top: 440px;
}

#form_username {
    position: absolute;
    right: 10%;
    top: 220px;
}

#friends_list {
    margin-top: 25px;
}

#new_username, #new_password, #confirm_password {
    width: 80%;
    max-width: 300px;
    border-radius: 4px;
    box-sizing: border-box;
}

#submit {
    padding: 0.2vw 0.4vw;
    border-radius: 4px;
    cursor: pointer;
}

#delete_btn {
    background-color: red;
    color: white;
    padding: 0.2vw 0.4vw;
    border-radius: 4px;
    cursor: pointer;
}

#friends_list ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

#friends_list li {
    background-color: rgba(255,255,255,0.1);
    padding: 5px 10px;
    border-radius: 4px;
    margin-bottom: 5px;
}

</style>

<script>
function confirmDelete() {
    return confirm("Are you sure you want to permanently delete this account? This can not be undone.");
}
</script>

<?php include 'includes/footer.php'; ?>