<?php

require 'shared/header.php'; 
require 'db_connect/db_connection.php';
// Удаление записи
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM recruit WHERE id = ?")->execute([$id]);
    header("Location: admin_recruit.php");
    exit;
}

// Добавление или обновление
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $rank_id = $_POST['rank_id'];
    $service_len = $_POST['service_len'];
    $is_sergeant = isset($_POST['is_sergeant']) ? 1 : 0;
    $is_officer = isset($_POST['is_officer']) ? 1 : 0;

    if (isset($_POST['id']) && $_POST['id'] != '') {
        // Обновление
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE recruit SET name=?, age=?, sex=?, rank_id=?, service_len=?, IsSergeant=?, IsOfficer=? WHERE id=?");
        $stmt->execute([$name, $age, $sex, $rank_id, $service_len, $is_sergeant, $is_officer, $id]);
    } else {
        // Добавление
        $stmt = $pdo->prepare("INSERT INTO recruit (name, age, sex, rank_id, service_len, IsSergeant, IsOfficer) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $age, $sex, $rank_id, $service_len, $is_sergeant, $is_officer]);
    }
    header("Location: admin_recruit.php");
    exit;
}

// Редактируемая запись
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM recruit WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}

$recruits = $pdo->query("SELECT * FROM recruit")->fetchAll(PDO::FETCH_ASSOC);
$ranks = $pdo->query("SELECT * FROM mil_rank")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Управление призывниками</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Призывники</h1>

    <form method="post">
        <input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
        <label>ФИО: <input type="text" name="name" required value="<?= $edit['name'] ?? '' ?>"></label><br>
        <label>Дата рождения: <input type="date" name="age" value="<?= isset($edit['age']) ? date('Y-m-d', strtotime($edit['age'])) : '' ?>" required></label><br>
        <label>Пол:
            <select name="sex">
                <option value="0" <?= (isset($edit['sex']) && $edit['sex'] == 0) ? 'selected' : '' ?>>Мужской</option>
                <option value="1" <?= (isset($edit['sex']) && $edit['sex'] == 1) ? 'selected' : '' ?>>Женский</option>
            </select>
        </label><br>
        <label>Звание:
            <select name="rank_id">
                <?php foreach ($ranks as $rank): ?>
                    <option value="<?= $rank['id'] ?>" <?= (isset($edit['rank_id']) && $edit['rank_id'] == $rank['id']) ? 'selected' : '' ?>><?= $rank['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <label>Дата поступления на службу: <input type="datetime-local" name="service_len" value="<?= isset($edit['service_len']) ? date('Y-m-d\TH:i', strtotime($edit['service_len'])) : '' ?>" required></label><br>
        <label><input type="checkbox" name="is_sergeant" <?= (isset($edit['IsSergeant']) && $edit['IsSergeant']) ? 'checked' : '' ?>> Сержант</label><br>
        <label><input type="checkbox" name="is_officer" <?= (isset($edit['IsOfficer']) && $edit['IsOfficer']) ? 'checked' : '' ?>> Офицер</label><br>
        <button type="submit">Сохранить</button>
    </form>

    <h2>Список</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>ФИО</th>
            <th>Дата рождения</th>
            <th>Пол</th>
            <th>Звание</th>
            <th>Дата службы</th>
            <th>Сержант</th>
            <th>Офицер</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($recruits as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['name']) ?></td>
                <td><?= date('d.m.Y', strtotime($r['age'])) ?></td>
                <td><?= $r['sex'] ? 'Ж' : 'М' ?></td>
                <td><?= $r['rank_id'] ?></td>
                <td><?= date('d.m.Y', strtotime($r['service_len'])) ?></td>
                <td><?= $r['IsSergeant'] ? 'Да' : 'Нет' ?></td>
                <td><?= $r['IsOfficer'] ? 'Да' : 'Нет' ?></td>
                <td>
                    <a href="?edit=<?= $r['id'] ?>">Редактировать</a> |
                    <a href="?delete=<?= $r['id'] ?>" onclick="return confirm('Удалить запись?')">Удалить</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
