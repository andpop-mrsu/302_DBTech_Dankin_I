<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

$studentId = $_GET['student_id'] ?? null;

if (!$studentId) {
    echo json_encode([]);
    exit;
}

try {
    $db = getDBConnection();
    
    // Получаем текущую группу студента
    $stmt = $db->prepare("SELECT g.id, g.number FROM students s JOIN groups g ON s.group_id = g.id WHERE s.id = :student_id");
    $stmt->execute([':student_id' => $studentId]);
    $group = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($group) {
        echo json_encode([$group]);
    } else {
        echo json_encode([]);
    }
} catch (Exception $e) {
    echo json_encode([]);
}
?>

