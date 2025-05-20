<?php 
require 'shared/header.php'; 
require 'db_connect/db_connection.php';

$sql = "SELECT 
                w.id,
                w.name,
                wt.name as type,
                w.amount
            FROM 
                weapon w
            LEFT JOIN weapon_type wt ON w.type = wt.id
            GROUP BY w.id";

try {
    // Выполнение запроса
    $stmt = $pdo->query($sql);
    
    // Проверка, есть ли записи в таблице
    if ($stmt->rowCount() > 0) {
        
        echo '<h2>Список вооружения </h2>';
        
        echo '<div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Название <input type="text" id="name_filter" placeholder="Фильтр"></th>
                            <th>Тип 
                                <select id="type_filter">
                                    <option value="">Любой</option>';
        
        // SQL-запрос для получения типов оружия
        $typeQuery = "SELECT id, name FROM weapon_type ORDER BY name";
        $typeStmt = $pdo->prepare($typeQuery);
        $typeStmt->execute();

        // Генерация опций для выпадающего списка
        while ($type = $typeStmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . htmlspecialchars($type['id']) . "'>" . htmlspecialchars($type['name']) . "</option>";
        }

        echo '          </select>
                            </th>
                            <th>Количество <input type="number" id="amount_filter" placeholder="Фильтр" min="1"></th>
                        </tr>
                    </thead>
                    <tbody>';

        // Перебираем все записи
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr data-type-id='" . htmlspecialchars($row['type']) . "'>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['type']) . "</td>
                    <td>" . htmlspecialchars($row['amount']) . "</td>
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

<script>
    // Получаем все строки таблицы
    const rows = document.querySelectorAll('table tbody tr');
    
    // Получаем фильтры
    const nameFilter = document.getElementById('name_filter');
    const typeFilter = document.getElementById('type_filter');
    const amountFilter = document.getElementById('amount_filter');

    // Функция для фильтрации строк
    function filterTable() {
        const nameVal = nameFilter.value.toLowerCase();
        const typeVal = typeFilter.value;
        const amountVal = amountFilter.value;

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const name = cells[0].textContent.toLowerCase();
            const type = cells[1].textContent.toLowerCase();
            const amount = parseInt(cells[2].textContent, 10);
            const typeId = row.getAttribute('data-type-id');

            const matchesName = name.includes(nameVal);
            const matchesType = typeVal ? typeId === typeVal : true;
            const matchesAmount = amountVal ? amount >= parseInt(amountVal, 10) : true;

            // Показываем или скрываем строку в зависимости от того, соответствует ли она всем фильтрам
            if (matchesName && matchesType && matchesAmount) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Добавляем слушателей событий для всех фильтров
    nameFilter.addEventListener('input', filterTable);
    typeFilter.addEventListener('change', filterTable);
    amountFilter.addEventListener('input', filterTable);

    // Изначальная фильтрация
    filterTable();
</script>

<link href="../css/style.css" rel="stylesheet">

<style>
    .table-container {
        max-width: 800px;
        margin: 20px auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th, table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    table th {
        background-color:#3498db;
    }

    input[type="text"], input[type="number"], select {
        width: 100%;
        padding: 5px;
        margin-top: 5px;
    }
</style>
