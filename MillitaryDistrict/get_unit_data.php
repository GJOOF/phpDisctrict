<?php
require_once 'db_connect/db_connection.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'No ID provided']);
    exit;
}

$unitId = $_GET['id'];

// Get basic unit info
$stmt = $sql->prepare("SELECT id, name, formation_id FROM mil_unit WHERE id = ?");
$stmt->execute([$unitId]);
$unit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$unit) {
    echo json_encode(['error' => 'Unit not found']);
    exit;
}

// Get settlement
$stmt = $sql->prepare("SELECT settlement_id FROM unit_settlement WHERE unit_id = ?");
$stmt->execute([$unitId]);
$settlement = $stmt->fetch(PDO::FETCH_ASSOC);
$unit['settlement_id'] = $settlement ? $settlement['settlement_id'] : null;

// Get buildings
$stmt = $sql->prepare("SELECT building_id FROM unit_buildings WHERE unit_id = ?");
$stmt->execute([$unitId]);
$unit['buildings'] = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

echo json_encode($unit);