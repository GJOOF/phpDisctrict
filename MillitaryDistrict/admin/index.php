<?php
session_start();
require '../db_connect/db_connection.php';  // Подключение к базе данных
require '../shared/header.php';  // Подключение общего заголовка страницы

// Если форма была отправлена, добавляем данные в базу
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $soldier_id = $_POST['soldier_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $weapon_id = $_POST['weapon_id'];
    $base_id = $_POST['base_id'];
    $unit_type = $_POST['unit_type'];

    // SQL-запрос для вставки данных в таблицу unit
    $sql = "INSERT INTO Unit (soldier_id, vehicle_id, weapon_id, base_id, unit_type) 
            VALUES (:soldier_id, :vehicle_id, :weapon_id, :base_id, :unit_type)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'soldier_id' => $soldier_id,
        'vehicle_id' => $vehicle_id,
        'weapon_id' => $weapon_id,
        'base_id' => $base_id,
        'unit_type' => $unit_type
    ]);

    echo "Подразделение успешно добавлено!";
    header("Location: view_unit.php");  // Перенаправляем на страницу списка подразделений
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Add Unit</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Подключение кастомных стилей -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Подключение Bootstrap -->
    <style>
        body {
            background-color: #f4f7f6;
        }
        .container {
            margin-top: 50px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-custom {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Добавить Подразделение</h2>
    <form method="POST">
        <!-- Soldier ID -->
        <div class="mb-3">
            <label for="soldier_idв" class="form-label">ID Солдата</label>
            <input type="number" class="form-control" id="soldier_id" name="soldier_id" required>
        </div>

        <!-- Vehicle ID -->
        <div class="mb-3">
            <label for="vehicle_id" class="form-label">ID Транспортного средства</label>
            <input type="number" class="form-control" id="vehicle_id" name="vehicle_id" required>
        </div>

        <!-- Weapon ID -->
        <div class="mb-3">
            <label for="weapon_id" class="form-label">ID Оружия</label>
            <input type="number" class="form-control" id="weapon_id" name="weapon_id" required>
        </div>

        <!-- Base ID -->
        <div class="mb-3">
            <label for="base_id" class="form-label">ID Базы</label>
            <input type="number" class="form-control" id="base_id" name="base_id" required>
        </div>

        <!-- Unit Type -->
        <div class="mb-3">
            <label for="unit_type" class="form-label">Тип подразделения</label>
            <input type="text" class="form-control" id="unit_type" name="unit_type" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-custom">Добавить Подразделение</button>
        <a href="view_unit.php" class="btn btn-secondary">Отмена</a>
    </form>
</div>

</body>
</html>
