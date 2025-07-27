<?php
include 'includes/db.php';

if (isset($_POST['register'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        header("Location: login.php");
        exit;
    } else {
        echo "Pendaftaran gagal: " . mysqli_error($conn);
    }
}
?>

<h2>Register</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Nama" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button name="register">Register</button>
</form>

<p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
