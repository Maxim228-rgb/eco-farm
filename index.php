<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

// Получаем последние продукты
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 3");
$latest_products = $stmt->fetchAll();
?>

<section class="hero">
    <div class="container">
        <h1>Добро пожаловать на эко-ферму!</h1>
        <p>Свежие органические продукты, уютные домики и незабываемые экскурсии на природе.</p>
        <a href="products.php" class="btn">К покупкам</a>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2>Почему выбирают нас</h2>
        <div class="features-grid">
            <div class="feature-card">
                <img src="https://images.unsplash.com/photo-1500595046743-fd2a9f19b83c?w=300" alt="Эко">
                <h3>Натуральные продукты</h3>
                <p>Все продукты выращены без химии и ГМО.</p>
            </div>
            <div class="feature-card">
                <img src="https://images.unsplash.com/photo-1444210971048-6130cf0c46cf?w=300" alt="Домики">
                <h3>Уютные домики</h3>
                <p>Отдохните от городской суеты в наших эко-домиках.</p>
            </div>
            <div class="feature-card">
                <img src="https://images.unsplash.com/photo-1533107862482-0e6974b3ec9b?w=300" alt="Экскурсии">
                <h3>Интересные экскурсии</h3>
                <p>Узнайте о жизни фермы и попробуйте себя в роли фермера.</p>
            </div>
        </div>
    </div>
</section>

<section class="latest-products">
    <div class="container">
        <h2>Новинки</h2>
        <div class="products-grid">
            <?php foreach ($latest_products as $product): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="price"><?= number_format($product['price'], 2) ?> ₽</p>
                    <a href="product.php?id=<?= $product['id'] ?>" class="btn-small">Подробнее</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>