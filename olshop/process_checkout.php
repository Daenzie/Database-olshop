<?php
include 'includes/db.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];
$product_ids = $_POST['product_ids'] ?? [];
$quantities = $_POST['quantities'] ?? [];
$payment_method = $_POST['payment_method'] ?? '';

if (empty($product_ids) || empty($payment_method)) {
    echo "Checkout gagal. Tidak ada produk atau metode pembayaran belum dipilih.";
    echo "<br><a href='cart.php'>Kembali ke Keranjang</a>";
    exit;
}

$success = true;
$conn->begin_transaction();

try {
    for ($i = 0; $i < count($product_ids); $i++) {
        $pid = (int)$product_ids[$i];
        $qty = (int)$quantities[$i];

        // Ambil stok terbaru
        $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ? FOR UPDATE");
        $stmt->bind_param("i", $pid);
        $stmt->execute();
        $res = $stmt->get_result();
        $product = $res->fetch_assoc();

        if (!$product || $product['stock'] < $qty) {
            $success = false;
            throw new Exception("Stok tidak cukup untuk produk ID: $pid");
        }

        // Simpan transaksi
        $stmt = $conn->prepare("
            INSERT INTO transactions (user_id, product_id, quantity, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("iii", $user_id, $pid, $qty);
        $stmt->execute();

        // Kurangi stok
        $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param("ii", $qty, $pid);
        $stmt->execute();

        // Hapus dari cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $user_id, $pid);
        $stmt->execute();
    }

    $conn->commit();
} catch (Exception $e) {
    $conn->rollback();
    $success = false;
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Proses Checkout</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <h2>Checkout Berhasil!</h2>
            <p>Terima kasih sudah berbelanja. Metode pembayaran: <strong><?= htmlspecialchars($payment_method) ?></strong></p>
        <?php else: ?>
            <h2>Checkout Gagal</h2>
            <p><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <a href="dashboard.php" class="btn">Kembali ke Dashboard</a>
    </div>
</body>
</html>

