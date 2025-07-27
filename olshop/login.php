<?php
session_start();
include 'includes/db.php';

if (isset($_POST['login'])) {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] == 'seller') {
            header("Location: seller_dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        echo "Email atau password salah!";
    }
}
?>

<h2>Login</h2>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button name="login">Login</button>
</form>

<p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
