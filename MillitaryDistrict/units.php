<?php 
require 'shared/header.php'; 
require 'db_connect/db_connection.php';

$sql = "SELECT 
    mu.id AS id,
    mu.name AS name,
    mf.name AS formation_name,
    GROUP_CONCAT(DISTINCT w.name SEPARATOR ', ') AS weapons_list,  
    GROUP_CONCAT(DISTINCT ue.weapon_count SEPARATOR ', ') AS weapons_count,  
    GROUP_CONCAT(DISTINCT ue.vehicle_count SEPARATOR ', ') AS vehicle_count,  
    GROUP_CONCAT(DISTINCT v.name SEPARATOR ', ') AS vehicle_names,  
    GROUP_CONCAT(DISTINCT b.name SEPARATOR ', ') AS buildings_list,
    GROUP_CONCAT(DISTINCT s.name SEPARATOR ', ') AS settlements_list
FROM 
    mil_unit mu
LEFT JOIN mil_formation mf ON mu.formation_id = mf.id
LEFT JOIN unit_buildings ub ON mu.id = ub.unit_id
LEFT JOIN unit_settlement us ON mu.id = us.unit_id
LEFT JOIN buildings b ON ub.building_id = b.id
LEFT JOIN settlement s ON us.settlement_id = s.id
LEFT JOIN unit_equipment ue ON mu.id = ue.unit_id
LEFT JOIN weapon w ON ue.weapon_id = w.id  
LEFT JOIN vehicle v ON ue.vehicle_id = v.id  
GROUP BY mu.id";

try {
    $stmt = $pdo->query($sql);
    
    if ($stmt->rowCount() > 0) {
        $output = '<h2>Список воинских частей</h2>';
        $output .= '<div class="table-container"><table>';
        $output .= '<tr><th>Название</th><th>Формирование</th><th>Оружие</th><th>Транспорт</th><th>Здания</th><th>Местоположение</th></tr>';
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Разбиваем строки данных на массивы
            $weapons = !empty($row['weapons_list']) ? explode(',', $row['weapons_list']) : [];
            $weapons_count = !empty($row['weapons_count']) ? explode(',', $row['weapons_count']) : [];
            $vehicles = !empty($row['vehicle_names']) ? explode(',', $row['vehicle_names']) : [];
            $vehicles_count = !empty($row['vehicle_count']) ? explode(',', $row['vehicle_count']) : [];
            $buildings = !empty($row['buildings_list']) ? explode(',', $row['buildings_list']) : [];
            $settlements = !empty($row['settlements_list']) ? explode(',', $row['settlements_list']) : [];
            
            $output .= "<tr>
                        <td>" . htmlspecialchars($row['name'] ?? 'NDA') . "</td>
                        <td>" . htmlspecialchars($row['formation_name'] ?? 'NDA') . "</td>
                        <td>";
            
            // Оружие
            $output .= displayListWithToggle($weapons, $weapons_count, 'оружие');
            
            $output .= "</td><td>";
            
            // Транспорт
            $output .= displayListWithToggle($vehicles, $vehicles_count, 'транспорт');
            
            $output .= "</td><td>";
            
            // Здания
            $output .= displayListWithToggle($buildings, [], 'здания');
            
            $output .= "</td><td>";
            
            // Местоположение
            $output .= displayListWithToggle($settlements, [], 'местоположение');
            
            $output .= "</td></tr>";
        }

        $output .= '</table></div>';
        
        // Добавляем стили и скрипт один раз в конце
        $output .= '
        <style>
            .toggle-btn {
                background: none;
                border: none;
                color: #0066cc;
                cursor: pointer;
                padding: 2px 5px;
                text-align: left;
                font: inherit;
            }
            .toggle-btn:hover {
                text-decoration: underline;
            }
            .list-wrapper {
                margin: 5px 0 0 15px;
                padding: 0;
                list-style-type: none;
                border-left: 2px solid #ddd;
                padding-left: 10px;
            }
            .list-wrapper li {
                padding: 2px 0;
            }
        </style>
        <script>
            function toggleList(btn) {
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
        
        echo $output;  // Выводим весь собранный HTML
    } else {
        echo "Нет данных для отображения.";
    }
} catch (PDOException $e) {
    echo "Ошибка при выборке данных: " . $e->getMessage();
}

// Функция для отображения списка с кнопкой показать/скрыть
function displayListWithToggle($items, $counts, $type) {
    if (!empty($items)) {
        $output = '<div class="' . $type . '-wrapper">
                    <button class="toggle-btn" onclick="toggleList(this)">
                        Показать ' . $type . ' (' . count($items) . ')
                    </button>
                    <ul class="' . $type . '-list" style="display:none;">';
        
        foreach ($items as $index => $item) {
            $cleanItem = trim($item);
            $count = isset($counts[$index]) ? $counts[$index] : 0;
            if (!empty($cleanItem)) {
                $output .= '<li>' . htmlspecialchars($cleanItem) . ' (x' . htmlspecialchars($count) . ')</li>';
            }
        }
        
        $output .= '</ul></div>';
        return $output;
    }
    return 'NDA';
}
?>
