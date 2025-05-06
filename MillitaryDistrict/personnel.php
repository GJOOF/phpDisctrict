<?php 
require 'shared/header.php'; 
require 'db_connect/db_connection.php';

// Определяем параметры сортировки
$sort_column = $_GET['sort'] ?? 'id';
$sort_order = $_GET['order'] ?? 'asc';

// Валидация параметров сортировки
$allowed_columns = ['id', 'name', 'age', 'sex', 'rank_id', 'occupation_id', 'formation_id', 'service_len'];
if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'id';
}
$sort_order = $sort_order === 'desc' ? 'desc' : 'asc';

// SQL-запрос с сортировкой
$sql = "SELECT 
    r.id,
    r.name,
    TIMESTAMPDIFF(YEAR, r.age, CURDATE()) AS age,
    IF(r.sex = 1, 'Муж', 'Жен') AS sex,
    mr.name AS rank_id,
    mo.name AS occupation_id,
    mf.name AS formation_id,
    TIMESTAMPDIFF(YEAR, r.service_len, CURDATE()) AS service_len,
    r.IsSergeant,
    r.IsOfficer
FROM 
    recruit r
LEFT JOIN mil_rank mr ON r.rank_id = mr.id
LEFT JOIN mil_occupation mo ON r.occupation_id = mo.id
LEFT JOIN mil_formation mf ON r.formation_id = mf.id
ORDER BY $sort_column $sort_order";

try {
    $stmt = $pdo->query($sql);
    
    if ($stmt->rowCount() > 0) {
        echo '<h2>Список военнослужащих</h2>';
        
        echo '<div class="table-container">
                <table>
                    <thead>
                        <tr>';
        
        // Функция для генерации ссылок сортировки
        function sort_link($column, $title, $current_sort, $current_order) {
            $new_order = ($current_sort == $column && $current_order == 'asc') ? 'desc' : 'asc';
            $arrow = '';
            if ($current_sort == $column) {
                $arrow = $current_order == 'asc' ? ' ↑' : ' ↓';
            }
            return '<a href="?sort='.$column.'&order='.$new_order.'">'.$title.$arrow.'</a>';
        }
        
        // Заголовки таблицы с ссылками сортировки
        echo '<th>'.sort_link('id', 'ID', $sort_column, $sort_order).'</th>';
        echo '<th>'.sort_link('name', 'Name', $sort_column, $sort_order).'</th>';
        echo '<th>'.sort_link('age', 'Age', $sort_column, $sort_order).'</th>';
        echo '<th>'.sort_link('sex', 'Sex', $sort_column, $sort_order).'</th>';
        echo '<th>'.sort_link('rank_id', 'Rank', $sort_column, $sort_order).'</th>';
        echo '<th>'.sort_link('occupation_id', 'VUS', $sort_column, $sort_order).'</th>';
        echo '<th>'.sort_link('formation_id', 'Formation', $sort_column, $sort_order).'</th>';
        echo '<th>'.sort_link('service_len', 'Service', $sort_column, $sort_order).'</th>';
        echo '<th>Sergeant</th>';
        echo '<th>Officer</th>';
        
        echo '</tr>
                    </thead>
                    <tbody>';
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr class='clickable-row' data-id='" . htmlspecialchars($row['id']) . "'>
                    <td>" . htmlspecialchars($row['id'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['name'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['age'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['sex'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['rank_id'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['occupation_id'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['formation_id'] ?? 'NDA') . "</td>
                    <td>" . htmlspecialchars($row['service_len'] ?? 'NDA') . "</td>
                    <td>" . ($row['IsSergeant'] ? 'Да' : 'Нет') . "</td>
                    <td>" . ($row['IsOfficer'] ? 'Да' : 'Нет') . "</td>
                  </tr>";
        }

        echo '</tbody>
              </table>
              </div>';
    } else {
        echo "Нет данных для отображения.";
    }
} catch (PDOException $e) {
    echo "Ошибка при выборке данных: " . $e->getMessage();
}
?>