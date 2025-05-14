<?php
session_start();

require 'shared/header.php';
require 'db_connect/db_connection.php';

// Проверка авторизации администратора
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Обработка CRUD операций
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        try {
            $sql->beginTransaction();
            
            // Создаем военную часть
            $stmt = $sql->prepare("INSERT INTO mil_unit (name, formation_id) VALUES (?, ?)");
            $stmt->execute([
                $_POST['unit_name'],
                $_POST['formation_id'] ?: null
            ]);
            $unit_id = $sql->lastInsertId();
            
            // Добавляем связь с населенным пунктом, если указан
            if (!empty($_POST['settlement_id'])) {
                $stmt = $sql->prepare("INSERT INTO unit_settlement (unit_id, settlement_id) VALUES (?, ?)");
                $stmt->execute([$unit_id, $_POST['settlement_id']]);
            }
            
            // Добавляем здания к части
            if (!empty($_POST['buildings'])) {
                foreach ($_POST['buildings'] as $building_id) {
                    $stmt = $sql->prepare("INSERT INTO unit_buildings (unit_id, building_id) VALUES (?, ?)");
                    $stmt->execute([$unit_id, $building_id]);
                }
            }
            
            $sql->commit();
            $_SESSION['message'] = "Военная часть успешно создана";
        } catch (PDOException $e) {
            $sql->rollBack();
            $_SESSION['error'] = "Ошибка при создании: " . $e->getMessage();
        }
    } elseif (isset($_POST['update'])) {
        try {
            $sql->beginTransaction();
            
            // Обновление записи
            $stmt = $sql->prepare("UPDATE mil_unit SET name = ?, formation_id = ? WHERE id = ?");
            $stmt->execute([
                $_POST['unit_name'],
                $_POST['formation_id'] ?: null,
                $_POST['unit_id']
            ]);
            
            // Обновление населенного пункта
            $sql->prepare("DELETE FROM unit_settlement WHERE unit_id = ?")->execute([$_POST['unit_id']]);
            if (!empty($_POST['settlement_id'])) {
                $stmt = $sql->prepare("INSERT INTO unit_settlement (unit_id, settlement_id) VALUES (?, ?)");
                $stmt->execute([$_POST['unit_id'], $_POST['settlement_id']]);
            }
            
            // Обновление зданий
            $sql->prepare("DELETE FROM unit_buildings WHERE unit_id = ?")->execute([$_POST['unit_id']]);
            if (!empty($_POST['buildings'])) {
                foreach ($_POST['buildings'] as $building_id) {
                    $stmt = $sql->prepare("INSERT INTO unit_buildings (unit_id, building_id) VALUES (?, ?)");
                    $stmt->execute([$_POST['unit_id'], $building_id]);
                }
            }
            
            $sql->commit();
            $_SESSION['message'] = "Военная часть успешно обновлена";
        } catch (PDOException $e) {
            $sql->rollBack();
            $_SESSION['error'] = "Ошибка при обновлении: " . $e->getMessage();
        }
    }
} elseif (isset($_GET['delete'])) {
    try {
        $sql->beginTransaction();
        
        // Удаляем связи с зданиями
        $sql->prepare("DELETE FROM unit_buildings WHERE unit_id = ?")->execute([$_GET['delete']]);
        
        // Удаляем связь с населенным пунктом
        $sql->prepare("DELETE FROM unit_settlement WHERE unit_id = ?")->execute([$_GET['delete']]);
        
        // Удаляем саму часть
        $stmt = $sql->prepare("DELETE FROM mil_unit WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        
        $sql->commit();
        $_SESSION['message'] = "Военная часть успешно удалена";
    } catch (PDOException $e) {
        $sql->rollBack();
        $_SESSION['error'] = "Ошибка при удалении: " . $e->getMessage();
    }
}

// Получение списка всех военных частей с дополнительной информацией
// Получение списка всех военных частей с дополнительной информацией
$units = $pdo->query("
    SELECT 
        u.id, 
        u.name, 
        f.name as formation_name,
        s.name as settlement_name,
        GROUP_CONCAT(b.name SEPARATOR ', ') as buildings_list
    FROM mil_unit u
    LEFT JOIN mil_formation f ON u.formation_id = f.id
    LEFT JOIN unit_settlement us ON u.id = us.unit_id
    LEFT JOIN settlement s ON us.settlement_id = s.id
    LEFT JOIN unit_buildings ub ON u.id = ub.unit_id
    LEFT JOIN buildings b ON ub.building_id = b.id
    GROUP BY u.id
")->fetchAll(PDO::FETCH_ASSOC);

// Получение списка всех формирований
$formations = $pdo->query("SELECT id, name FROM mil_formation ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

// Получение списка всех населенных пунктов
$settlements = $pdo->query("SELECT id, name FROM settlement ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

// Получение списка всех зданий
$buildings = $pdo->query("SELECT id, name FROM buildings ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администрирование военных частей</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color:rgb(17, 115, 190);
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }
        input, select {
            padding: 8px;
            width: 300px;
        }
        button {
            padding: 8px 15px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0052a3;
        }
        .action-buttons a {
            margin-right: 5px;
            text-decoration: none;
            padding: 4px 8px;
            border-radius: 3px;
        }
        .edit-btn {
            background-color: #4CAF50;
            color: white;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            width: 50%;
            max-height: 80vh;
            overflow-y: auto;
            border-radius: 5px;
        }
        .close {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }
        .buildings-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .building-item {
            margin-bottom: 5px;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
    <h1>Администрирование военных частей</h1>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message success"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="message error"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <button onclick="openModal('create')">Добавить новую часть</button>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Название части</th>
                <th>Формирование</th>
                <th>Место дислокации</th>
                <th>Здания</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($units as $unit): ?>
            <tr>
                <td><?= htmlspecialchars($unit['id']) ?></td>
                <td><?= htmlspecialchars($unit['name']) ?></td>
                <td><?= htmlspecialchars($unit['formation_name'] ?? 'Не указано') ?></td>
                <td><?= htmlspecialchars($unit['settlement_name'] ?? 'Не указано') ?></td>
                <td><?= htmlspecialchars($unit['buildings_list'] ?? 'Нет зданий') ?></td>
                <td class="action-buttons">
                    <a href="" class="edit-btn" onclick="openModal('edit', <?= $unit['id'] ?>)">Редактировать</a>
                    <a href="?delete=<?= $unit['id'] ?>" class="delete-btn" onclick="return confirm('Вы уверены что хотите удалить эту военную часть?')">Удалить</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div id="unitModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Добавить военную часть</h2>
            <form id="unitForm" method="POST">
                <input type="hidden" id="unit_id" name="unit_id">
                
                <div class="form-group">
                    <label for="unit_name">Название части:</label>
                    <input type="text" id="unit_name" name="unit_name" required>
                </div>
                
                <div class="form-group">
                    <label for="formation_id">Формирование:</label>
                    <select id="formation_id" name="formation_id">
                        <option value="">-- Не указано --</option>
                        <?php foreach ($formations as $formation): ?>
                        <option value="<?= $formation['id'] ?>"><?= htmlspecialchars($formation['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="settlement_id">Место дислокации:</label>
                    <select id="settlement_id" name="settlement_id">
                        <option value="">-- Не указано --</option>
                        <?php foreach ($settlements as $settlement): ?>
                        <option value="<?= $settlement['id'] ?>"><?= htmlspecialchars($settlement['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Здания:</label>
                    <div class="buildings-list">
                        <?php foreach ($buildings as $building): ?>
                        <div class="building-item">
                            <input type="checkbox" name="buildings[]" id="building_<?= $building['id'] ?>" value="<?= $building['id'] ?>">
                            <label for="building_<?= $building['id'] ?>"><?= htmlspecialchars($building['name']) ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <button type="submit" id="submitBtn" name="create">Создать</button>
            </form>
        </div>
    </div>

    <script>
        // Функция для заполнения формы данными при редактировании
        function fillForm(unitId) {
            fetch('get_unit_data.php?id=' + unitId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('unit_id').value = data.id;
                    document.getElementById('unit_name').value = data.name;
                    
                    if (data.formation_id) {
                        document.getElementById('formation_id').value = data.formation_id;
                    }
                    
                    if (data.settlement_id) {
                        document.getElementById('settlement_id').value = data.settlement_id;
                    }
                    
                    // Сбрасываем все чекбоксы
                    document.querySelectorAll('input[name="buildings[]"]').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    
                    // Отмечаем здания, принадлежащие части
                    if (data.buildings && data.buildings.length > 0) {
                        data.buildings.forEach(buildingId => {
                            const checkbox = document.getElementById('building_' + buildingId);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        }
        
        function openModal(action, unitId = null) {
            const modal = document.getElementById('unitModal');
            const form = document.getElementById('unitForm');
            const title = document.getElementById('modalTitle');
            const submitBtn = document.getElementById('submitBtn');
            
            // Очистка формы
            form.reset();
            
            if (action === 'create') {
                title.textContent = 'Добавить военную часть';
                submitBtn.textContent = 'Создать';
                submitBtn.name = 'create';
            } else if (action === 'edit' && unitId) {
                title.textContent = 'Редактировать военную часть';
                submitBtn.textContent = 'Обновить';
                submitBtn.name = 'update';
                
                // Заполняем форму данными
                fillForm(unitId);
            }
            
            modal.style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('unitModal').style.display = 'none';
        }
        
        // Закрытие при клике вне модального окна
        window.onclick = function(event) {
            const modal = document.getElementById('unitModal');
            if (event.target == modal) {
                closeModal();
            }
        }
        
        // Закрытие по ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>