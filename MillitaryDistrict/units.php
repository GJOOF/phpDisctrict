<?php 
require 'shared/header.php'; 
require 'db_connect/db_connection.php';

// SQL-запрос для выборки всех записей из таблицы mil_unit с группировкой зданий
$sql = "SELECT 
    mu.id AS id,
    mu.name AS name,
    mf.name AS formation_name,
    w.amount AS weapon_amount,
    v.amount AS vehicle_amount,
    a.amount AS aircraft_amount,
    GROUP_CONCAT(DISTINCT b.name SEPARATOR ',') AS buildings_list,
    s.name AS settlement_name
FROM 
    mil_unit mu
LEFT JOIN mil_formation mf ON mu.formation_id = mf.id
LEFT JOIN weapon w ON mu.weapon = w.id
LEFT JOIN vehicle v ON mu.vehicle = v.id
LEFT JOIN aircraft a ON mu.aircraft = a.id
LEFT JOIN unit_buildings ub ON mu.id = ub.unit_id
LEFT JOIN buildings b ON ub.building_id = b.id
LEFT JOIN settlement s ON mu.settlement = s.id
GROUP BY mu.id";

try {
    // Выполнение запроса
    $stmt = $pdo->query($sql);
    
    // Проверка, есть ли записи в таблице
    if ($stmt->rowCount() > 0) {
        echo '<h2>Список военных частей</h2>';
        
        echo '<div class="table-container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Формирование</th>
                        <th>Оружие</th>
                        <th>Транспорт</th>
                        <th>Авиация</th>
                        <th>Здания</th>
                        <th>Местоположение</th>
                    </tr>';
        
        // Перебираем все записи
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr class =>
                    <td>" . htmlspecialchars($row['id'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['name'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['formation_name'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['weapon_amount'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['vehicle_amount'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['aircraft_amount'] ?? 'NDA') . "</td>
                    <td>";
            
            // Вывод списка зданий с возможностью сворачивания
            if (!empty($row['buildings_list'])) {
                $buildings = explode(',', $row['buildings_list']);
                $buildingsCount = count($buildings);
                
                echo '<div class="buildings-wrapper">
                        <button class="toggle-buildings-btn" onclick="toggleBuildings(this)">
                            Показать здания (' . $buildingsCount . ')
                        </button>
                        <ul class="buildings-list" style="display:none;">';
                
                foreach ($buildings as $building) {
                    $cleanBuilding = trim($building);
                    if (!empty($cleanBuilding)) {
                        echo '<li>' . htmlspecialchars($cleanBuilding) . '</li>';
                    }
                }
                
                echo '</ul></div>';
            } else {
                echo 'NDA';
            }
            
            echo "</td>
                    <td>" . htmlspecialchars($row['settlement_name'] ?? 'NDA') . "</td>
                </tr>";
        }

        echo '</table>
              </div>';
        
        // Добавляем стили и скрипт один раз в конце
        echo '
        <style>
            .buildings-wrapper {
                position: relative;
                margin: 2px 0;
            }
            .toggle-buildings-btn {
                background: none;
                border: none;
                color: #0066cc;
                cursor: pointer;
                padding: 2px 5px;
                text-align: left;
                font: inherit;
            }
            .toggle-buildings-btn:hover {
                text-decoration: underline;
            }
            .buildings-list {
                margin: 5px 0 0 15px;
                padding: 0;
                list-style-type: none;
                border-left: 2px solid #ddd;
                padding-left: 10px;
            }
            .buildings-list li {
                padding: 2px 0;
            }
        </style>
        <script>
            function toggleBuildings(btn) {
                const list = btn.nextElementSibling;
                if (list.style.display === "none") {
                    list.style.display = "block";
                    btn.textContent = btn.textContent.replace("Показать", "Скрыть");
                } else {
                    list.style.display = "none";
                    btn.textContent = btn.textContent.replace("Скрыть", "Показать");
                }
            }
        </script>';
    } else {
        echo "Нет данных для отображения.";
    }
} catch (PDOException $e) {
    echo "Ошибка при выборке данных: " . $e->getMessage();
}
?>