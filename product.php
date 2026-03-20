<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}

// Добавление в корзину
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = (int)$_POST['quantity'];
    if ($quantity > 0 && $quantity <= $product['stock']) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $product['id']) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image' => $product['image_url']
            ];
        }
        $_SESSION['success'] = 'Товар добавлен в корзину';
        header("Location: product.php?id=$id");
        exit;
    }
}
?>

<section class="product-detail">
    <div class="container">
        <div class="product-wrapper">
            <div class="product-image">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
            </div>
            <div class="product-info">
                <h1><?= htmlspecialchars($product['name']) ?></h1>
                <p class="price"><?= number_format($product['price'], 2) ?> ₽</p>
                <p class="stock">В наличии: <?= $product['stock'] ?> шт.</p>
                <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                
                <form method="POST" class="add-to-cart-form">
                    <label for="quantity">Количество:</label>
                    <input type="number" name="quantity" id="quantity" min="1" max="<?= $product['stock'] ?>" value="1">
                    <button type="submit" name="add_to_cart" class="btn">Добавить в корзину</button>
                </form>
                <?php if (isset($_SESSION['success'])): ?>
                    <p class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>