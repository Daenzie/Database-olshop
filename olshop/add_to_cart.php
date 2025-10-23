<?php
include 'includes/db.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? 0;

if ($product_id > 0) {
    // cek stok
    $check = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $check->bind_param("i", $product_id);
    $check->execute();
    $result = $check->get_result();
    $product = $result->fetch_assoc();

    if ($product && $product['stock'] > 0) {
        $stmt = $conn->prepare("
            INSERT INTO cart (user_id, product_id, quantity)
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE quantity = quantity + 1
        ");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        echo "Produk ditambahkan ke keranjang!";
    } else {
        echo "Stok produk habis!";
    }
} else {
    echo "Produk tidak valid.";
}
