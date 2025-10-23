<?php
include 'includes/db.php';
include 'includes/auth.php';

header('Content-Type: application/json'); // supaya return JSON

$user_id    = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if ($product_id > 0) {
    // cek stok produk
    $check = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $check->bind_param("i", $product_id);
    $check->execute();
    $result = $check->get_result();
    $product = $result->fetch_assoc();

    if ($product && $product['stock'] > 0) {
        // simpan transaksi
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, product_id, quantity, created_at) VALUES (?, ?, 1, NOW())");
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();

        // kurangi stok
        $update = $conn->prepare("UPDATE products SET stock = stock - 1 WHERE id = ?");
        $update->bind_param("i", $product_id);
        $update->execute();

        echo json_encode([
            "success" => true,
            "new_stock" => $product['stock'] - 1,
            "message" => "Produk berhasil dibeli!"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Stok habis!"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Produk tidak valid!"
    ]);
}
