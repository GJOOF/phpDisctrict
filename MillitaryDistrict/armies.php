<?php 
require 'shared/header.php'; 
require 'db_connect/db_connection.php';

// SQL-запрос для выборки всех записей из таблицы Vehicle
$sql = "SELECT
    mf.id AS id,
    mf.name AS name,
    ft.name AS type_id,
    CONCAT(mr.name, ' ', r.name) AS commanding_officer,
    mf.manpower,
    w.name AS weapon_name,
    v.name AS vehicle_name,
    a.name AS aircraft_name
FROM 
    mil_formation mf
LEFT JOIN formation_type ft ON mf.type_id = ft.id
LEFT JOIN recruit r ON mf.commanding_officer = r.id
LEFT JOIN mil_rank mr ON r.rank_id = mr.id
LEFT JOIN weapon w ON mf.weapon_id = w.id
LEFT JOIN vehicle v ON mf.vehicle_id = v.id
LEFT JOIN aircraft a ON mf.aircraft_id = a.id";

try {
    // Выполнение запроса
    $stmt = $pdo->query($sql);
    
    // Проверка, есть ли записи в таблице
    if ($stmt->rowCount() > 0) {
        
        echo '<h2>Список подразделений</h2>';
        
        echo '<div class="table-container">
                <table>
                    <tr>
                        <td>Type</td>
                        <td>Name</td>
                        <td>Commanding officer</td>
                        <td>Manpower</td>
                        <td>Weapon</td>
                        <td>Vehicle</td>
                        <td>Aircraft</td>
                    </tr>';
        
        // Перебираем все записи
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['type_id'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['name'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['commanding_officer'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['manpower'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['weapon_id'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['vehicle_id'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['aircraft_id'] ?? 'NDA') . "</td>
                  </tr>";
        }

        echo '</table>
              </div>';
    } else {
        echo "Нет данных для отображения.";
    }
} catch (PDOException $e) {
    echo "Ошибка при выборке данных: " . $e->getMessage();
}
?>
