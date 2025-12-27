<?php
require_once '../controllers/ExamController.php';

$controller = new ExamController();

// Обработка удаления
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id']) && isset($_GET['student_id'])) {
    $controller->delete($_GET['id'], $_GET['student_id']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['student_id']) || !isset($_POST['discipline_id']) || !isset($_POST['exam_date']) || !isset($_POST['grade']) || !isset($_POST['course'])) {
        header('Location: index.php');
        exit;
    }
    
    $data = [
        ':student_id' => $_POST['student_id'],
        ':discipline_id' => $_POST['discipline_id'],
        ':exam_date' => $_POST['exam_date'],
        ':grade' => $_POST['grade'],
        ':course' => $_POST['course']
    ];
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $controller->update($_POST['id'], $data);
    } else {
        $controller->store($data);
    }
} else {
    if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
        header('Location: index.php');
        exit;
    }
    
    if (isset($_GET['id'])) {
        $controller->edit($_GET['id'], $_GET['student_id']);
    } else {
        $controller->create($_GET['student_id']);
    }
}
?>