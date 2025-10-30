<?php
include 'includes/db.php';

$error = ""; // buat nampung pesan error

if (isset($_POST['register'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email sudah terdaftar
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkResult = $checkEmail->get_result();

    if ($checkResult->num_rows > 0) {
        $error = "Email sudah terdaftar! Gunakan email lain.";
    } else {
        // Jika belum ada, lanjut insert
        $query = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $query->bind_param("sss", $name, $email, $password);
        if ($query->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Pendaftaran gagal. Coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="assets/register.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>

        <?php if (!empty($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Nama" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            
            <input type="password" name="password" id="password" placeholder="Password" required>
            <br>
            <input type="checkbox" onclick="togglePassword()"> Tampilkan Password
            <br>

            <button name="register">Register</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    
    </div>
    <script>
        function togglePassword() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>
</html>

