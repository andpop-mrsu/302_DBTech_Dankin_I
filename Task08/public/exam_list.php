<?php
require_once '../controllers/ExamController.php';

if (!isset($_GET['student_id']) || empty($_GET['student_id'])) {
    header('Location: index.php');
    exit;
}

$controller = new ExamController();
$controller->index($_GET['student_id']);
?>