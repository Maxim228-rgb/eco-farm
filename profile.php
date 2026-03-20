<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'includes/header.php';

$user_id = $_SESSION['user_id'];

// Получаем данные пользователя
$stmt = $pdo->prepare("SELECT name, email, created_at FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Получаем бронирования домиков
$stmt = $pdo->prepare("SELECT * FROM house_bookings WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$house_bookings = $stmt->fetchAll();

// Получаем бронирования экскурсий
$stmt = $pdo->prepare("SELECT tb.*, t.name as tour_name, t.date FROM tour_bookings tb JOIN tours t ON tb.tour_id = t.id WHERE tb.user_id = ? ORDER BY tb.booking_date DESC");
$stmt->execute([$user_id]);
$tour_bookings = $stmt->fetchAll();
?>

<section class="profile">
    <div class="container">
        <h1>Личный кабинет</h1>
        
        <div class="profile-info">
            <h2>Мои данные</h2>
            <p><strong>Имя:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Зарегистрирован:</strong> <?= $user['created_at'] ?></p>
        </div>
        
        <?php if ($house_bookings): ?>
            <div class="profile-bookings">
                <h2>Мои бронирования домиков</h2>
                <table>
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
                        <?php foreach ($house_bookings as $b): ?>
                            <tr>
                                <td><?= htmlspecialchars($b['house_type']) ?></td>
                                <td><?= $b['check_in'] ?></td>
                                <td><?= $b['check_out'] ?></td>
                                <td><?= $b['guests'] ?></td>
                                <td><?= $b['created_at'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <?php if ($tour_bookings): ?>
            <div class="profile-bookings">
                <h2>Мои экскурсии</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Экскурсия</th>
                            <th>Дата</th>
                            <th>Участников</th>
                            <th>Забронировано</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tour_bookings as $b): ?>
                            <tr>
                                <td><?= htmlspecialchars($b['tour_name']) ?></td>
                                <td><?= $b['date'] ?></td>
                                <td><?= $b['participants'] ?></td>
                                <td><?= $b['booking_date'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>