<?php
session_start();
$body_id = "AdminLogin";

include "header.inc";

$admin_user = "against_all_odds";
$admin_pass = "123"; 

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION["is_admin"] = true;
        header("Location: manage.php");
        exit();
    } else {
        $errors[] = "Invalid username or password.";
    }
}
?>

<main>
<section>
    <h1>Admin Login</h1>

    <?php if (count($errors) > 0): ?>
        <div class="message error">
            <ul>
                <?php foreach($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="simple_login.php" novalidate>
        <fieldset>
            <legend>Login Credentials</legend>

            <label for="username">Username</label>
            <input type="text" name="username" id="username" required value="<?php echo htmlspecialchars($_POST["username"] ?? ""); ?>">

            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Login</button>
        </fieldset>
    </form>
</section>
</main>

<?php include "footer.inc"; ?>
