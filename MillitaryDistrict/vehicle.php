<?php 
require 'shared/header.php'; 
require 'db_connect/db_connection.php';

// SQL-запрос для выборки всех записей из таблицы Vehicle
$sql = "SELECT * FROM Vehicle";

try {
    // Выполнение запроса
    $stmt = $pdo->query($sql);
    
    // Проверка, есть ли записи в таблице
    if ($stmt->rowCount() > 0) {
        
        echo '<h2>Список транспортных средств</h2>';
        
        echo '<div class="table-container">
                <table>
                    <tr>
                        
                    </tr>';
        
        // Перебираем все записи
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['amount']) . "</td>
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
<link href="../css/bootstrap.min.css" rel="stylesheet">