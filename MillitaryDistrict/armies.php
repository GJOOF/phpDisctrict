<?php 
require 'shared/header.php'; 
require 'db_connect/db_connection.php';

$sql = "SELECT
    mf.id AS id,
    mf.name AS name,
    ft.name AS type_id,
    CONCAT(mr.name, ' ', r.name) AS commanding_officer,
    w.name AS weapon_name,
    v.name AS vehicle_name
FROM 
    mil_formation mf
LEFT JOIN formation_type ft ON mf.type_id = ft.id
LEFT JOIN recruit r ON mf.commanding_officer = r.id
LEFT JOIN mil_rank mr ON r.rank_id = mr.id
LEFT JOIN weapon w ON mf.weapon_id = w.id
LEFT JOIN vehicle v ON mf.vehicle_id = v.id";


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
                        <td>Weapon</td>
                        <td>Vehicle</td>
                    </tr>';
        
        // Перебираем все записи
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['type_id'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['name'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['commanding_officer'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['weapon_id'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['vehicle_id'] ?? 'NDA') . "</td>
                  </tr>";
                  
        }

        echo '</table>
              </div>';
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
<link href="../css/style.css" rel="stylesheet">