<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

// Удаление из корзины
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // переиндексация
    }
    header('Location: cart.php');
    exit;
}

// Очистка корзины
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header('Location: cart.php');
    exit;
}

// Подсчет итога
$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
?>

<section class="cart">
    <div class="container">
        <h1>Корзина</h1>
        <?php if (empty($_SESSION['cart'])): ?>
            <p>Ваша корзина пуста.</p>
            <a href="products.php" class="btn">Перейти к продуктам</a>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $key => $item): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="50">
                                <?= htmlspecialchars($item['name']) ?>
                            </td>
                            <td><?= number_format($item['price'], 2) ?> ₽</td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= number_format($item['price'] * $item['quantity'], 2) ?> ₽</td>
                            <td><a href="cart.php?remove=<?= $key ?>" class="btn-remove">✖</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total-label">Итого:</td>
                        <td class="total"><?= number_format($total, 2) ?> ₽</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div class="cart-actions">
                <a href="products.php" class="btn">Продолжить покупки</a>
                <a href="cart.php?clear=1" class="btn btn-clear">Очистить корзину</a>
                <!-- Здесь можно добавить кнопку оформления заказа -->
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>