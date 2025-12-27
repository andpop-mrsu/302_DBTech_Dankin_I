<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

$groupId = $_GET['group_id'] ?? null;
$course = $_GET['course'] ?? null;

if (!$groupId || !$course) {
    echo json_encode([]);
    exit;
}

try {
    $db = getDBConnection();
    
    // Получаем номер группы
    $stmt = $db->prepare("SELECT number FROM groups WHERE id = :group_id");
    $stmt->execute([':group_id' => $groupId]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$group) {
        echo json_encode([]);
        exit;
    }
    
    // Определяем направление по префиксу номера группы
    $groupNumber = $group['number'];
    $direction = null;
    
    if (strpos($groupNumber, 'ИТ-') === 0) {
        $direction = 'Информационные технологии';
    } elseif (strpos($groupNumber, 'ПИ-') === 0) {
        $direction = 'Прикладная информатика';
    } elseif (strpos($groupNumber, 'КБ-') === 0) {
        $direction = 'Кибербезопасность';
    }
    
    if (!$direction) {
        echo json_encode([]);
        exit;
    }
    
    // Получаем дисциплины для данного направления и курса
    $stmt = $db->prepare("SELECT id, name FROM disciplines WHERE direction = :direction AND course = :course ORDER BY name");
    $stmt->execute([
        ':direction' => $direction,
        ':course' => $course
    ]);
    
    $disciplines = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($disciplines);
} catch (Exception $e) {
    echo json_encode([]);
}
?>

