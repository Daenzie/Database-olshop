<?php
include 'includes/db.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];

$product_ids = $_POST['product_ids'] ?? [];
$quantities = $_POST['quantities'] ?? [];

for ($i = 0; $i < count($product_ids); $i++) {
    $pid = $product_ids[$i];
    $qty = $quantities[$i];

    // Simpan ke transaksi
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, product_id, quantity, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iii", $user_id, $pid, $qty);
    $stmt->execute();

    // Hapus dari cart
    $conn->query("DELETE FROM cart WHERE user_id = $user_id AND product_id = $pid");
}

echo "Checkout berhasil! <a href='dashboard.php'>Kembali ke Dashboard</a>";
?>
