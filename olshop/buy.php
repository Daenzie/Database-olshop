<?php
include 'includes/db.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Misalnya: transaksi langsung beli (catatan, real-nya kamu perlu sistem transaksi / orders)
$stmt = $conn->prepare("INSERT INTO transactions (user_id, product_id, quantity, created_at) VALUES (?, ?, 1, NOW())");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();

echo "Produk berhasil dibeli! <a href='dashboard.php'>Kembali</a>";
?>
