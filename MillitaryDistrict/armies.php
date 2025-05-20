<?php 
require 'shared/header.php'; 
require 'db_connect/db_connection.php';

$output = '<h2>Список подразделений</h2>';
$output .= '<div class="table-container"><table>';
$output .= '<thead>
            <tr>
                <th>Тип <input type="text" id="type_filter" placeholder="Фильтр по типу"></th>
                <th>Название <input type="text" id="name_filter" placeholder="Фильтр по названию"></th>
                <th>Командир <input type="text" id="commander_filter" placeholder="Фильтр по командиру"></th>
                <th>Части</th>
            </tr>
            </thead><tbody>';

$sql = "SELECT
    mf.id AS id,
    mf.name AS name,
    ft.name AS type_id,
    CONCAT(mr.name, ' ', r.name) AS commanding_officer,
    GROUP_CONCAT(DISTINCT mu.name SEPARATOR ',') AS units
FROM 
    mil_formation mf
LEFT JOIN formation_type ft ON mf.type_id = ft.id
LEFT JOIN recruit r ON mf.commanding_officer = r.id
LEFT JOIN mil_rank mr ON r.rank_id = mr.id
LEFT JOIN formation_unit fu ON mf.id = fu.formation_id
LEFT JOIN mil_unit mu ON fu.unit_id = mu.id
GROUP BY mf.id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $units = !empty($row['units']) ? explode(',', $row['units']) : [];

            $output .= "<tr>
                        <td>" . htmlspecialchars($row['type_id'] ?? 'NDA') . "</td>
                        <td>" . htmlspecialchars($row['name'] ?? 'NDA') . "</td>
                        <td>" . htmlspecialchars($row['commanding_officer'] ?? 'NDA') . "</td>
                        <td>";

            if (!empty($units)) {
                $output .= '<div class="units-wrapper">
                            <button class="toggle-units-btn" onclick="toggleUnits(this)">
                                Показать части (' . count($units) . ')
                            </button>
                            <ul class="units-list" style="display:none;">';
                foreach ($units as $unit) {
                    $cleanUnit = trim($unit);
                    if (!empty($cleanUnit)) {
                        $output .= '<li>' . htmlspecialchars($cleanUnit) . '</li>';
                    }
                }
                $output .= '</ul></div>';
            } else {
                $output .= 'NDA';
            }

            $output .= "</td></tr>";
        }
    } else {
        $output .= "Нет данных для отображения.";
    }
} catch (PDOException $e) {
    $output .= "Ошибка при выборке данных: " . $e->getMessage();
}

$output .= '</tbody></table></div>';

// Добавляем стили и скрипт для фильтрации
$output .= '
<script>
    const rows = document.querySelectorAll("table tbody tr");
    
    const typeFilter = document.getElementById("type_filter");
    const nameFilter = document.getElementById("name_filter");
    const commanderFilter = document.getElementById("commander_filter");

    function filterTable() {
        const typeVal = typeFilter.value.toLowerCase();
        const nameVal = nameFilter.value.toLowerCase();
        const commanderVal = commanderFilter.value.toLowerCase();

        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            const type = cells[0].textContent.toLowerCase();
            const name = cells[1].textContent.toLowerCase();
            const commander = cells[2].textContent.toLowerCase();

            const matchesType = type.includes(typeVal);
            const matchesName = name.includes(nameVal);
            const matchesCommander = commander.includes(commanderVal);

            if (matchesType && matchesName && matchesCommander) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    typeFilter.addEventListener("input", filterTable);
    nameFilter.addEventListener("input", filterTable);
    commanderFilter.addEventListener("input", filterTable);

    filterTable(); // Применяем фильтр по умолчанию

    function toggleUnits(btn) {
        const list = btn.nextElementSibling;
        if (list.style.display === "none") {
            list.style.display = "block";
            btn.textContent = btn.textContent.replace("Показать", "Скрыть");
        } else {
            list.style.display = "none";
            btn.textContent = btn.textContent.replace("Скрыть", "Показать");
        }
    }
</script>
<link href="../css/style.css" rel="stylesheet">
<style>
    .units-wrapper {
        position: relative;
        margin: 2px 0;
    }
    .toggle-units-btn {
        background: none;
        border: none;
        color: #0066cc;
        cursor: pointer;
        padding: 2px 5px;
        text-align: left;
        font: inherit;
    }
    .toggle-units-btn:hover {
        text-decoration: underline;
    }
    .units-list {
        margin: 5px 0 0 15px;
        padding: 0;
        list-style-type: none;
        border-left: 2px solid #ddd;
        padding-left: 10px;
    }
    .units-list li {
        padding: 2px 0;
    }
</style>';

echo $output;
?>
