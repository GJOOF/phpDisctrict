<?php

$host = 'localhost';
$user = 'root';
$password = '';
$dbName = 'military_db';
$sqlFile = 'sql/military_db.sql';  // Путь к вашему SQL файлу

try {
    // Подключение к серверу MySQL без указания базы данных
    $pdo = new PDO("mysql:host=$host", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Проверка существования базы данных
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("CREATE DATABASE $dbName");
    } else {
    }

    // Выбираем базу данных
    $pdo->exec("USE $dbName");

    // Чтение SQL-запросов из файла
    $sql = file_get_contents($sqlFile);

    // Выполнение SQL-запросов из файла
    $pdo->exec($sql);

} catch (PDOException $e) {
    // Если возникла ошибка
    echo "Ошибка подключения: " . $e->getMessage();
}
?>
