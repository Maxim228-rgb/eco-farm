<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

// Если пользователь не авторизован, перенаправляем на логин
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after'] = 'booking.php';
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Обработка формы бронирования
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $house_type = $_POST['house_type'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guests = (int)$_POST['guests'];
    
    // Простейшая валидация
    $errors = [];
    if (empty($house_type)) $errors[] = 'Выберите тип домика';
    if (empty($check_in) || empty($check_out)) $errors[] = 'Укажите даты';
    if ($guests < 1) $errors[] = 'Укажите количество гостей';
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO house_bookings (user_id, house_type, check_in, check_out, guests) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $house_type, $check_in, $check_out, $guests]);
        $success = 'Домик успешно забронирован!';
    }
}

// Получаем бронирования пользователя
$stmt = $pdo->prepare("SELECT * FROM house_bookings WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>

<section class="booking">
    <div class="container">
        <h1>Бронирование домиков</h1>
        
        <?php if (isset($success)): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        
        <div class="booking-form">
            <h2>Забронировать домик</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="house_type">Тип домика</label>
                    <select name="house_type" id="house_type" required>
                        <option value="">Выберите...</option>
                        <option value="Деревянный домик">Деревянный домик</option>
                        <option value="Эко-шале">Эко-шале</option>
                        <option value="Глэмпинг">Глэмпинг</option>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="check_in">Дата заезда</label>
                        <input type="date" name="check_in" id="check_in" required>
                    </div>
                    <div class="form-group">
                        <label for="check_out">Дата выезда</label>
                        <input type="date" name="check_out" id="check_out" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="guests">Количество гостей</label>
                    <input type="number" name="guests" id="guests" min="1" max="6" required>
                </div>
                <button type="submit" class="btn">Забронировать</button>
            </form>
        </div>
        
        <?php if ($bookings): ?>
            <div class="my-bookings">
                <h2>Мои бронирования</h2>
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>Тип</th>
                            <th>Заезд</th>
                            <th>Выезд</th>
                            <th>Гости</th>
                            <th>Дата брони</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?= htmlspecialchars($booking['house_type']) ?></td>
                                <td><?= $booking['check_in'] ?></td>
                                <td><?= $booking['check_out'] ?></td>
                                <td><?= $booking['guests'] ?></td>
                                <td><?= $booking['created_at'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>