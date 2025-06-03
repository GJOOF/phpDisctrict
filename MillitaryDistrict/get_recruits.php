<?php
// Настройки подключения к базе данных
header('Content-Type: application/json'); // Устанавливаем заголовок для JSON

$config = [
    'host' => 'localhost',
    'db'   => 'military_db',
    'user' => 'root',
    'pass' => '',
    'charset' => 'utf8mb4'
];

try {
    // Подключение к базе данных через PDO
    $dsn = "mysql:host={$config['host']};dbname={$config['db']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['user'], $config['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ваш SQL-запрос
    $sql = "SELECT 
                r.id,
                r.name,
                TIMESTAMPDIFF(YEAR, r.age, CURDATE()) AS age,
                IF(r.sex = 0, 'Муж', 'Жен') AS sex,
                mr.name AS rank_id,
                r.rank_id as rank_num,
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

    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Возвращаем данные в формате JSON
    echo json_encode([
        'success' => true,
        'data' => $results,
        'count' => count($results)
    ]);

} catch (PDOException $e) {
    // Обработка ошибок
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>