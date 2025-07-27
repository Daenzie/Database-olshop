<?php
include 'includes/db.php';
include 'includes/auth.php';


if ($_SESSION['role'] != 'seller') {
    echo "Akses hanya untuk seller!";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $stock = $_POST['stock'];
    $image = '';

    // upload image
    if ($_FILES['image']['name']) {
        $target = 'assets/img/' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $image = $target;
        }
    }

    $stmt = $conn->prepare("INSERT INTO products (seller_id, name, price, description, image, stock, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("isissi", $_SESSION['user_id'], $name, $price, $desc, $image, $stock);
    $stmt->execute();
    echo "Produk berhasil ditambahkan!";
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Nama produk" required><br>
    <input type="number" name="price" placeholder="Harga" required><br>
    <input type="number" name="stock" placeholder="Stok" required><br>
    <textarea name="description" placeholder="Deskripsi produk"></textarea><br>
    <input type="file" name="image"><br>
    <button type="submit">Tambah Produk</button>
</form>
