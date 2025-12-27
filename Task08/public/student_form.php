<?php
require_once '../controllers/StudentController.php';

$controller = new StudentController();

// Обработка удаления
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $controller->delete($_GET['id']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['last_name']) || !isset($_POST['first_name']) || !isset($_POST['gender']) || !isset($_POST['group_id'])) {
        header('Location: index.php');
        exit;
    }
    
    $data = [
        ':last_name' => $_POST['last_name'],
        ':first_name' => $_POST['first_name'],
        ':middle_name' => $_POST['middle_name'] ?? null,
        ':gender' => $_POST['gender'],
        ':group_id' => $_POST['group_id']
    ];
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $controller->update($_POST['id'], $data);
    } else {
        $controller->store($data);
    }
} else {
    if (isset($_GET['id'])) {
        $controller->edit($_GET['id']);
    } else {
        $controller->create();
    }
}
?>