<?php 
require 'shared/header.php'; 
require 'db_connect/db_connection.php';

// SQL-запрос для выборки всех записей из таблицы Vehicle
$sql = "SELECT 
    mu.id AS id,
    mu.name AS name,
    mf.name AS formation_name,
    w.amount AS weapon_amount,
    v.amount AS vehicle_amount,
    a.amount AS aircraft_amount,
    b.name AS building_name,
    s.name AS settlement_name
FROM 
    mil_unit mu
LEFT JOIN mil_formation mf ON mu.formation_id = mf.id
LEFT JOIN weapon w ON mu.weapon = w.id
LEFT JOIN vehicle v ON mu.vehicle = v.id
LEFT JOIN aircraft a ON mu.aircraft = a.id
LEFT JOIN buildings b ON mu.buildings = b.id
LEFT JOIN settlement s ON mu.settlement = s.id";


try {
    // Выполнение запроса
    $stmt = $pdo->query($sql);
    
    // Проверка, есть ли записи в таблице
    if ($stmt->rowCount() > 0) {
        echo '<h2>Список военных частей</h2>';
        
        echo '<div class="table-container">
                <table>
                    <tr>
                        
                    </tr>';
        
        // Перебираем все записи
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['id'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['name'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['formation_name'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['weapon_amount'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['vehicle_amount'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['aircraft_amount'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['building_name'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['settlement_name'] ?? 'NDA') . "</td>
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
