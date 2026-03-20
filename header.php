<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Эко-ферма "Зелёный угол"</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Наш основной стиль -->
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo">
                <h1><a href="index.php">🌿 Эко-ферма</a></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php"><i class="fas fa-home"></i> Главная</a></li>
                    <li><a href="products.php"><i class="fas fa-apple-alt"></i> Продукты</a></li>
                    <li><a href="booking.php"><i class="fas fa-house-user"></i> Домики</a></li>
                    <li><a href="tours.php"><i class="fas fa-hiking"></i> Экскурсии</a></li>
                    <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Корзина</a></li> <!-- теперь всегда видна -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="profile.php"><i class="fas fa-user"></i> Личный кабинет</a></li>
                        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Выйти</a></li>
                    <?php else: ?>
                        <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Войти</a></li>
                        <li><a href="register.php"><i class="fas fa-user-plus"></i> Регистрация</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>