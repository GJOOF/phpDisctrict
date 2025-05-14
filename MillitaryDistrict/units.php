<?php 
require 'shared/header.php'; 
require 'db_connect/db_connection.php';

$sql = "SELECT 
    mu.id AS id,
    mu.name AS name,
    mf.name AS formation_name,
    w.amount AS weapon_amount,
    v.amount AS vehicle_amount,
    GROUP_CONCAT(DISTINCT b.name SEPARATOR ',') AS buildings_list,
    GROUP_CONCAT(DISTINCT s.name SEPARATOR ',') AS settlements_list
FROM 
    mil_unit mu
LEFT JOIN mil_formation mf ON mu.formation_id = mf.id
LEFT JOIN weapon w ON mu.weapon_id = w.id
LEFT JOIN vehicle v ON mu.vehicle_id = v.id
LEFT JOIN unit_buildings ub ON mu.id = ub.unit_id
LEFT JOIN unit_settlement us ON mu.id = ub.unit_id
LEFT JOIN buildings b ON ub.building_id = b.id
LEFT JOIN settlement s ON us.settlement_id = s.id
GROUP BY mu.id";

try {
    // Выполнение запроса
    $stmt = $pdo->query($sql);
    
    // Проверка, есть ли записи в таблице
    if ($stmt->rowCount() > 0) {
        echo '<h2>Список воинских частей</h2>';
        echo '<div class="table-container">
                <table>
                    <tr>
                        <th>Название</th>
                        <th>Формирование</th>
                        <th>Оружие</th>
                        <th>Транспорт</th>
                        <th>Здания</th>
                        <th>Местоположение</th>
                    </tr>';
        
        // Перебираем все записи
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['name'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['formation_name'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['weapon_amount'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['vehicle_amount'] ?? 'NDA') . "</td>
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
                    <td>";
            
            // Вывод списка местоположений
            if (!empty($row['settlements_list'])) {
                $settlements = explode(',', $row['settlements_list']);
                $settlementsCount = count($settlements);
                
                echo '<div class="settlements-wrapper">
                        <button class="toggle-settlements-btn" onclick="toggleSettlements(this)">
                            Показать (' . $settlementsCount . ')
                        </button>
                        <ul class="settlements-list" style="display:none;">';
                
                foreach ($settlements as $settlement) {
                    $cleanSettlement = trim($settlement);
                    if (!empty($cleanSettlement)) {
                        echo '<li>' . htmlspecialchars($cleanSettlement) . '</li>';
                    }
                }
                
                echo '</ul></div>';
            } else {
                echo 'NDA';
            }
            
            echo "</td>
                </tr>";
        }

        echo '</table>
              </div>';
        
        // Добавляем стили и скрипт один раз в конце
        echo '
        <style>
            .buildings-wrapper, .settlements-wrapper {
                position: relative;
                margin: 2px 0;
            }
            .toggle-buildings-btn, .toggle-settlements-btn {
                background: none;
                border: none;
                color: #0066cc;
                cursor: pointer;
                padding: 2px 5px;
                text-align: left;
                font: inherit;
            }
            .toggle-buildings-btn:hover, .toggle-settlements-btn:hover {
                text-decoration: underline;
            }
            .buildings-list, .settlements-list {
                margin: 5px 0 0 15px;
                padding: 0;
                list-style-type: none;
                border-left: 2px solid #ddd;
                padding-left: 10px;
            }
            .buildings-list li, .settlements-list li {
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
            
            function toggleSettlements(btn) {
                const list = btn.nextElementSibling;
                if (list.style.display === "none") {
                    list.style.display = "block";
                    btn.textContent = btn.textContent.replace("Показать", "Скрыть");
                } else {
                    list.style.display = "none";
                    btn.textContent = btn.textContent.replace("Скрыть", "Показать");
                }
            }
                $(document).ready(function(){
  $(".filter-dropdown, .text-button").click(function(){
    $(".edit-filter-modal").toggleClass("hidden");
    
  });
    $(".apply-button").click(function(){
    $(".edit-filter-modal").toggleClass("hidden");
          $(".filter, .filter-remove, .fa-plus, .fa-filter").toggleClass("filter-hidden");
      $(".filter-dropdown-text").text("Add filter");
    
      
    });
      
      $(".filter-remove").click(function(){
        $(".filter, .filter-remove, .fa-plus, .fa-filter").toggleClass("filter-hidden");
        $(".filter-dropdown-text").text("Filter dataset");
      });
  
  
  
  
});
        </script>';
    } else {
        echo "Нет данных для отображения.";
    }
} catch (PDOException $e) {
    echo "Ошибка при выборке данных: " . $e->getMessage();
}
?>
<link href="../css/style.css" rel="stylesheet">