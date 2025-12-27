<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение удаления</title>
</head>
<body>
    <h1>Подтверждение удаления</h1>
    
    <p>Вы уверены, что хотите удалить студента:</p>
    <p><strong><?= htmlspecialchars($student['last_name'] . ' ' . $student['first_name'] . ' ' . ($student['middle_name'] ?? '')) ?></strong>?</p>
    
    <form method="POST" action="student_form.php?action=delete&id=<?= $student['id'] ?>">
        <button type="submit">Да, удалить</button>
        <a href="index.php">Отмена</a>
    </form>
</body>
</html>

