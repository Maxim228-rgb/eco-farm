<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    $errors = [];
    if (empty($name)) $errors[] = 'Введите имя';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Некорректный email';
    if (strlen($password) < 6) $errors[] = 'Пароль должен быть не менее 6 символов';
    if ($password !== $confirm) $errors[] = 'Пароли не совпадают';
    
    // Проверка на существующий email
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = 'Пользователь с таким email уже существует';
    }
    
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);
        
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_name'] = $name;
        
        if (isset($_SESSION['redirect_after'])) {
            $redirect = $_SESSION['redirect_after'];
            unset($_SESSION['redirect_after']);
            header("Location: $redirect");
        } else {
            header('Location: profile.php');
        }
        exit;
    }
}
?>

<section class="auth">
    <div class="container">
        <h1>Регистрация</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Подтверждение пароля</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" class="btn">Зарегистрироваться</button>
        </form>
        <p>Уже есть аккаунт? <a href="login.php">Войдите</a></p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>