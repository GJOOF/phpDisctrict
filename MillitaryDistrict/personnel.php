<?php
require 'shared/header.php'; 
require 'db_connect/db_connection.php';

$output = '<h2>Список военнослужащих</h2>';
$output .= '<div class="table-container"><table>';
$output .= '<thead>
            <tr>
                <th>Имя <input type="text" id="name_filter" placeholder="Фильтр"></th>
                <th>Возраст <input type="number" id="age_filter" placeholder="Фильтр (мин. 18)" min="18"></th>
                <th>Пол <select id="sex_filter">
                    <option value="">Любой</option>
                    <option value="0">Муж</option>
                    <option value="1">Жен</option>
                </select></th>
                <th>Звание <select id="rank_filter">
                    <option value="">Любое</option>';

$sqlRanks = "SELECT id, name FROM mil_rank ORDER BY name";
$rankStmt = $pdo->prepare($sqlRanks);
$rankStmt->execute();

while ($rank = $rankStmt->fetch(PDO::FETCH_ASSOC)) {
    $output .= "<option value='" . htmlspecialchars($rank['id']) . "'>" . htmlspecialchars($rank['name']) . "</option>";
}

$output .= '</select></th>';
$output .= '<th>Подразделение <select id="formation_filter">
                    <option value="">Любое</option>';

$sqlFormations = "SELECT id, name FROM mil_formation ORDER BY name";
$formationStmt = $pdo->prepare($sqlFormations);
$formationStmt->execute();

while ($formation = $formationStmt->fetch(PDO::FETCH_ASSOC)) {
    $output .= "<option value='" . htmlspecialchars($formation['id']) . "'>" . htmlspecialchars($formation['name']) . "</option>";
}

$output .= '</select></th><th>ВУС</th><th>Срок службы</th></tr></thead><tbody>';

$sql = "SELECT 
                r.id,
                r.name,
                TIMESTAMPDIFF(YEAR, r.age, CURDATE()) AS age,
                r.sex,
                mr.name AS rank_name,
                r.rank_id as rank_num,
                GROUP_CONCAT(DISTINCT mo.name SEPARATOR ',') AS occupation_names,
                mf.name AS formation_name,
                mf.id AS formation_id,
                TIMESTAMPDIFF(YEAR, r.service_len, CURDATE()) AS service_len,
                r.IsSergeant,
                r.IsOfficer
            FROM 
                recruit r
            LEFT JOIN mil_rank mr ON r.rank_id = mr.id
            LEFT JOIN recruit_occupation ro ON r.id = ro.recruit_id
            LEFT JOIN mil_occupation mo ON ro.occupation_id = mo.id
            LEFT JOIN recruit_formation rf ON r.id = rf.recruit_id
            LEFT JOIN mil_formation mf ON rf.formation_id = mf.id 
            GROUP BY r.id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sex_display = $row['sex'] == 0 ? 'Муж' : 'Жен';
            $output .= "<tr data-rank-id='{$row['rank_num']}' data-formation-id='{$row['formation_id']}' data-sex='{$row['sex']}'>
                        <td>" . htmlspecialchars($row['name'] ?? 'NDA') . "</td>
                        <td>" . htmlspecialchars($row['age'] ?? 'NDA') . "</td>
                        <td>$sex_display</td>
                        <td>" . htmlspecialchars($row['rank_name'] ?? 'NDA') . "</td>
                        <td>" . htmlspecialchars($row['formation_name'] ?? 'NDA') . "</td>
                        <td>";
            
            if (!empty($row['occupation_names'])) {
                $vusList = explode(',', $row['occupation_names']);
                $vusCount = count($vusList);
                $output .= '<div class="vus-wrapper">
                            <button class="toggle-vus-btn" onclick="toggleVus(this)">
                                Показать ВУС (' . $vusCount . ')
                            </button>
                            <ul class="vus-list" style="display:none;">';
                foreach ($vusList as $vus) {
                    $cleanVus = trim($vus);
                    if (!empty($cleanVus)) {
                        $output .= '<li>' . htmlspecialchars($cleanVus) . '</li>';
                    }
                }
                $output .= '</ul></div>';
            } else {
                $output .= 'NDA';
            }

            $output .= "</td><td>" . htmlspecialchars($row['service_len'] ?? 'NDA') . "</td></tr>";
        }
    } else {
        $output .= "<tr><td colspan='7'>Нет данных для отображения.</td></tr>";
    }
} catch (PDOException $e) {
    $output .= "<tr><td colspan='7'>Ошибка при выборке данных: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
}
$output .= '</tbody></table></div>';

$output .= '
<script>
    function debounce(func, timeout = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    function filterTable() {
        const nameVal = document.getElementById("name_filter").value.toLowerCase();
        const ageVal = document.getElementById("age_filter").value;
        const sexVal = document.getElementById("sex_filter").value;
        const rankVal = document.getElementById("rank_filter").value;
        const formationVal = document.getElementById("formation_filter").value.toLowerCase();
        
        document.querySelectorAll("tbody tr").forEach(row => {
            const name = row.cells[0].textContent.toLowerCase();
            const age = parseInt(row.cells[1].textContent) || 0;
            const sex = row.getAttribute("data-sex");
            const rankId = row.getAttribute("data-rank-id");
            const formation = row.cells[4].textContent.toLowerCase();
            
            const showRow = 
                name.includes(nameVal) &&
                (ageVal ? age >= parseInt(ageVal) : true) &&
                (sexVal ? sex === sexVal : true) &&
                (rankVal ? rankId === rankVal : true) &&
                (formationVal ? formation.includes(formationVal) : true);
            
            row.style.display = showRow ? "" : "none";
        });
    }

    document.getElementById("name_filter").addEventListener("input", debounce(filterTable));
    document.getElementById("age_filter").addEventListener("input", debounce(filterTable));
    document.getElementById("sex_filter").addEventListener("change", filterTable);
    document.getElementById("rank_filter").addEventListener("change", filterTable);
    document.getElementById("formation_filter").addEventListener("change", filterTable);

    function toggleVus(btn) {
        const list = btn.nextElementSibling;
        if (list.style.display === "none") {
            list.style.display = "block";
            btn.textContent = btn.textContent.replace("Показать", "Скрыть");
        } else {
            list.style.display = "none";
            btn.textContent = btn.textContent.replace("Скрыть", "Показать");
        }
    }

    // Первоначальная фильтрация
    filterTable();
</script>

<style>
    .table-container {
        overflow-x: auto;
        margin-top: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #3498DB;
        position: sticky;
        top: 0;
    }
    input[type="text"], input[type="number"], select {
        width: 100%;
        padding: 4px;
        box-sizing: border-box;
        margin-top: 4px;
    }
    .vus-wrapper {
        position: relative;
    }
    .toggle-vus-btn {
        background: none;
        border: none;
        color: #0066cc;
        cursor: pointer;
        padding: 0;
        font: inherit;
    }
    .toggle-vus-btn:hover {
        text-decoration: underline;
    }
    .vus-list {
        margin: 5px 0 0 0;
        padding: 0;
        list-style-type: none;
    }
    .vus-list li {
        padding: 2px 0;
    }
</style>';

echo $output;
?>