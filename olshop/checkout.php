<?php
include 'includes/db.php';
include 'includes/auth.php';

$user_id = $_SESSION['user_id'];
$selected = $_POST['selected'] ?? [];
$quantities = $_POST['quantities'] ?? [];

if (empty($selected)) {
    echo "Tidak ada barang yang dipilih untuk checkout. <a href='cart.php'>Kembali</a>";
    exit;
}

$productData = [];
$total = 0;

foreach ($selected as $pid) {
    $pid = (int)$pid;
    $qty = (int)$quantities[$pid];

    $stmt = $conn->prepare("SELECT name, price, stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $res = $stmt->get_result();
    $product = $res->fetch_assoc();

    if ($product && $product['stock'] >= $qty) {
        $product['id'] = $pid;
        $product['qty'] = $qty;
        $product['subtotal'] = $qty * $product['price'];
        $total += $product['subtotal'];
        $productData[] = $product;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <h2>Checkout</h2>

    <?php if (!empty($productData)): ?>
        <?php foreach ($productData as $item): ?>
            <div class="product">
                <strong><?= $item['name'] ?></strong> x <?= $item['qty'] ?>
                <p>Rp<?= number_format($item['subtotal'], 0, ',', '.') ?></p>
            </div>
        <?php endforeach; ?>

        <h3>Total: Rp<?= number_format($total, 0, ',', '.') ?></h3>

        <form action="process_checkout.php" method="POST">
            <?php foreach ($productData as $item): ?>
                <input type="hidden" name="product_ids[]" value="<?= $item['id'] ?>">
                <input type="hidden" name="quantities[]" value="<?= $item['qty'] ?>">
            <?php endforeach; ?>

            <h4>Pilih Metode Pembayaran:</h4>
            <label><input type="radio" name="payment_method" value="COD" required> COD</label><br>
            <label><input type="radio" name="payment_method" value="Transfer Bank"> Transfer Bank</label><br>
            <label><input type="radio" name="payment_method" value="E-Wallet"> E-Wallet</label><br><br>

            <button type="submit">Konfirmasi Pembayaran</button>
        </form>
    <?php else: ?>
        <p>Stok tidak mencukupi untuk beberapa barang yang dipilih. <a href="cart.php">Kembali</a></p>
    <?php endif; ?>
</div>
</body>
</html>
