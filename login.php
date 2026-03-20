<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        
        if (isset($_SESSION['redirect_after'])) {
            $redirect = $_SESSION['redirect_after'];
            unset($_SESSION['redirect_after']);
            header("Location: $redirect");
        } else {
            header('Location: profile.php');
        }
        exit;
    } else {
        $error = 'Неверный email или пароль';
    }
}
?>

<section class="auth">
    <div class="container">
        <h1>Вход</h1>
        
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <form method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn">Войти</button>
        </form>
        <p class="auth-footer">Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>