<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

$stmt = $pdo->query("SELECT * FROM products ORDER BY name");
$products = $stmt->fetchAll();
?>

<section class="page-title">
    <div class="container">
        <h1>Наши продукты</h1>
    </div>
</section>

<section class="products">
    <div class="container">
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="description"><?= htmlspecialchars(mb_substr($product['description'], 0, 80)) ?>...</p>
                    <p class="price"><?= number_format($product['price'], 2) ?> ₽</p>
                    <a href="product.php?id=<?= $product['id'] ?>" class="btn-small">Подробнее</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>