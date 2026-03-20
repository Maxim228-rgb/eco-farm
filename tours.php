<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

// Получаем список экскурсий
$stmt = $pdo->query("SELECT * FROM tours ORDER BY date");
$tours = $stmt->fetchAll();

// Обработка бронирования экскурсии
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_tour'])) {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_after'] = 'tours.php';
        header('Location: login.php');
        exit;
    }
    
    $tour_id = (int)$_POST['tour_id'];
    $participants = (int)$_POST['participants'];
    
    // Проверяем наличие мест
    $stmt = $pdo->prepare("SELECT * FROM tours WHERE id = ? AND (max_people - booked) >= ?");
    $stmt->execute([$tour_id, $participants]);
    $tour = $stmt->fetch();
    
    if ($tour) {
        // Бронируем
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO tour_bookings (user_id, tour_id, participants) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $tour_id, $participants]);
            
            $stmt = $pdo->prepare("UPDATE tours SET booked = booked + ? WHERE id = ?");
            $stmt->execute([$participants, $tour_id]);
            
            $pdo->commit();
            $success = 'Экскурсия успешно забронирована!';
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Ошибка бронирования. Попробуйте позже.';
        }
    } else {
        $error = 'Недостаточно мест.';
    }
}
?>

<section class="tours">
    <div class="container">
        <h1>Экскурсии на ферме</h1>
        
        <?php if (isset($success)): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        
        <div class="tours-grid">
            <?php foreach ($tours as $tour): ?>
                <?php $available = $tour['max_people'] - $tour['booked']; ?>
                <div class="tour-card">
                    <img src="<?= htmlspecialchars($tour['image_url']) ?>" alt="<?= htmlspecialchars($tour['name']) ?>">
                    <h3><?= htmlspecialchars($tour['name']) ?></h3>
                    <p class="description"><?= nl2br(htmlspecialchars($tour['description'])) ?></p>
                    <p class="date"><strong>Дата:</strong> <?= date('d.m.Y', strtotime($tour['date'])) ?></p>
                    <p class="price"><?= number_format($tour['price'], 2) ?> ₽ / чел.</p>
                    <p class="available">Осталось мест: <?= $available ?></p>
                    
                    <?php if ($available > 0): ?>
                        <form method="POST" class="book-tour-form">
                            <input type="hidden" name="tour_id" value="<?= $tour['id'] ?>">
                            <label for="participants_<?= $tour['id'] ?>">Кол-во участников:</label>
                            <input type="number" name="participants" id="participants_<?= $tour['id'] ?>" min="1" max="<?= $available ?>" value="1" required>
                            <button type="submit" name="book_tour" class="btn-small">Забронировать</button>
                        </form>
                    <?php else: ?>
                        <p class="sold-out">Мест нет</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>