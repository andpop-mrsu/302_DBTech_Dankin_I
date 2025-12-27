<?php
function getDBConnection() {
    $dbPath = __DIR__ . '/../data/database.sqlite';
    
    try {
        $db = new PDO("sqlite:$dbPath");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("PRAGMA foreign_keys = ON");
        return $db;
    } catch(PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }
}
?>