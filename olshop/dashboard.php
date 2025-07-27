<?php
include 'includes/db.php';
include 'includes/auth.php';

// Ambil semua produk dari database
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';

$query = "SELECT * FROM products WHERE name LIKE '%$search%'";

if ($sort === 'termurah') {
    $query .= " ORDER BY price ASC";
} elseif ($sort === 'termahal') {
    $query .= " ORDER BY price DESC";
} else {
    $query .= " ORDER BY created_at DESC";
}

$result = $conn->query($query);
?>

<?php
// Cek apakah user buyer
$user_id = $_SESSION['user_id'];
$check = $conn->query("SELECT role FROM users WHERE id = $user_id");
$user = $check->fetch_assoc();

if ($user['role'] === 'buyer') {
    echo '<a href="become_seller.php"><button>Jadi Penjual</button></a>';
} else {
    echo '<a href="seller_dashboard.php"><button>Dashboard Penjual</button></a>';
}
?>
<a href="cart.php"><button>Lihat Keranjang</button></a>


<form method="GET" style="margin-bottom: 20px;">
    <input type="text" name="search" placeholder="Cari produk..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <select name="sort">
        <option value="">Urutkan</option>
        <option value="termurah" <?= ($_GET['sort'] ?? '') === 'termurah' ? 'selected' : '' ?>>Harga Termurah</option>
        <option value="termahal" <?= ($_GET['sort'] ?? '') === 'termahal' ? 'selected' : '' ?>>Harga Termahal</option>
    </select>
    <button type="submit">Cari</button>
</form>

<h2>Daftar Produk</h2>

<div style="display: flex; flex-wrap: wrap;">
<?php while ($row = $result->fetch_assoc()): ?>
    <div style="border: 1px solid #ccc; padding: 10px; margin: 10px; width: 200px;">
        <img src="<?= $row['image'] ?>" alt="gambar" style="width: 100%; height: 150px; object-fit: cover;"><br>
        <strong><?= $row['name'] ?></strong><br>
        Rp<?= number_format($row['price'], 0, ',', '.') ?><br>
        <small><?= $row['description'] ?></small><br><br>
        <form action="buy.php" method="POST" style="margin-bottom: 5px;">
            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
            <button type="submit">Beli</button>
        </form>

        <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
            <button type="submit">Add to Cart</button>
        </form>

    </div>
<?php endwhile; ?>
</div>
