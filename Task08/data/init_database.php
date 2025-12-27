<?php
/**
 * Скрипт инициализации базы данных
 * Запустите этот файл один раз для создания базы данных
 */

require_once __DIR__ . '/../includes/db.php';

$dbPath = __DIR__ . '/database.sqlite';
$sqlPath = __DIR__ . '/database.sql';

// Удаляем существующую базу данных, если она есть
if (file_exists($dbPath)) {
    unlink($dbPath);
}

try {
    // Создаем новое подключение к базе данных
    $db = new PDO("sqlite:$dbPath");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("PRAGMA foreign_keys = ON");
    
    // Читаем SQL файл
    if (!file_exists($sqlPath)) {
        die("Файл database.sql не найден!");
    }
    
    $sql = file_get_contents($sqlPath);
    
    // Выполняем SQL команды построчно
    $lines = explode("\n", $sql);
    $currentStatement = '';
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        // Пропускаем пустые строки и комментарии
        if (empty($line) || preg_match('/^--/', $line)) {
            continue;
        }
        
        $currentStatement .= $line . "\n";
        
        // Если строка заканчивается на точку с запятой, выполняем команду
        if (substr(rtrim($line), -1) === ';') {
            $currentStatement = trim($currentStatement);
            if (!empty($currentStatement)) {
                try {
                    $db->exec($currentStatement);
                } catch (PDOException $e) {
                    // Игнорируем ошибки типа "table already exists" при использовании IF NOT EXISTS
                    if (strpos($e->getMessage(), 'already exists') === false) {
                        throw $e;
                    }
                }
            }
            $currentStatement = '';
        }
    }
    
    // Выполняем оставшуюся команду, если есть
    $currentStatement = trim($currentStatement);
    if (!empty($currentStatement)) {
        $db->exec($currentStatement);
    }
    
    echo "База данных успешно инициализирована!\n";
    echo "Файл базы данных: $dbPath\n";
    
} catch (PDOException $e) {
    die("Ошибка при инициализации базы данных: " . $e->getMessage() . "\n");
}
?>

