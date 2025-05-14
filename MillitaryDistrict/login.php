<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // В реальном приложении проверка должна быть более надежной!
    if ($username === '1' && $password === '1') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = "Неверные учетные данные";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход в админку</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .login-form { max-width: 300px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; }
        button { padding: 8px 15px; background-color: #0066cc; color: white; border: none; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Вход в систему администрирования</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Логин:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>