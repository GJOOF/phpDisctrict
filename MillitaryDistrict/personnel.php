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
                IF(r.sex = 0, 'Муж', 'Жен') AS sex,
                mr.name AS rank_id,
                GROUP_CONCAT(DISTINCT mo.name SEPARATOR ',') AS occupation_names,
                mf.name AS formation_name,
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
            $output .= "<tr>
                        <td>" . htmlspecialchars($row['name'] ?? 'NDA') . "</td>
                        <td>" . htmlspecialchars($row['age'] ?? 'NDA') . "</td>
                        <td>" . htmlspecialchars($row['sex'] ?? 'NDA') . "</td>
                        <td>" . htmlspecialchars($row['rank_id'] ?? 'NDA') . "</td>
                        <td>" . htmlspecialchars($row['formation_name'] ?? 'NDA') . "</td>
                        <td>";

            // ВУС
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
        $output .= "Нет данных для отображения.";
    }
} catch (PDOException $e) {
    $output .= "Ошибка при выборке данных: " . $e->getMessage();
}

$output .= '</tbody></table></div>';

// Добавляем стили и скрипт
$output .= '
<script>
    const rows = document.querySelectorAll("table tbody tr");
    
    const nameFilter = document.getElementById("name_filter");
    const ageFilter = document.getElementById("age_filter");
    const sexFilter = document.getElementById("sex_filter");
    const rankFilter = document.getElementById("rank_filter");
    const formationFilter = document.getElementById("formation_filter");

    function filterTable() {
        const nameVal = nameFilter.value.toLowerCase();
        const ageVal = ageFilter.value;
        const sexVal = sexFilter.value;
        const rankVal = rankFilter.value;
        const formationVal = formationFilter.value;

        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            const name = cells[0].textContent.toLowerCase();
            const age = parseInt(cells[1].textContent, 10);
            const sex = cells[2].textContent.toLowerCase();
            const rank = cells[3].textContent.toLowerCase();
            const formation = cells[4].textContent.toLowerCase();

            const matchesName = name.includes(nameVal);
            const matchesAge = ageVal ? age >= parseInt(ageVal, 10) : true;
            const matchesSex = sexVal ? sex.includes(sexVal.toLowerCase()) : true;
            const matchesRank = rankVal ? rank.includes(rankVal.toLowerCase()) : true;
            const matchesFormation = formationVal ? formation.includes(formationVal.toLowerCase()) : true;

            if (matchesName && matchesAge && matchesSex && matchesRank && matchesFormation) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    nameFilter.addEventListener("input", filterTable);
    ageFilter.addEventListener("input", filterTable);
    sexFilter.addEventListener("change", filterTable);
    rankFilter.addEventListener("change", filterTable);
    formationFilter.addEventListener("change", filterTable);

    filterTable();

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
</script>
<link href="../css/style.css" rel="stylesheet">
<style>
    .vus-wrapper {
        position: relative;
        margin: 2px 0;
    }
    .toggle-vus-btn {
        background: none;
        border: none;
        color: #0066cc;
        cursor: pointer;
        padding: 2px 5px;
        text-align: left;
        font: inherit;
    }
    .toggle-vus-btn:hover {
        text-decoration: underline;
    }
    .vus-list {
        margin: 5px 0 0 15px;
        padding: 0;
        list-style-type: none;
        border-left: 2px solid #ddd;
        padding-left: 10px;
    }
    .vus-list li {
        padding: 2px 0;
    }
</style>';

echo $output;
?>
