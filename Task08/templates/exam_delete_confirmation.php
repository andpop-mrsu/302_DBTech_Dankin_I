<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение удаления экзамена</title>
</head>
<body>
    <h1>Подтверждение удаления</h1>
    
    <p>Вы уверены, что хотите удалить экзамен:</p>
    <p><strong>Дисциплина:</strong> <?= htmlspecialchars($exam['discipline_name']) ?></p>
    <p><strong>Дата:</strong> <?= htmlspecialchars($exam['exam_date']) ?></p>
    <p><strong>Оценка:</strong> <?= htmlspecialchars($exam['grade']) ?></p>
    <p><strong>Студент:</strong> <?= htmlspecialchars($student['last_name'] . ' ' . $student['first_name']) ?></p>
    
    <form method="POST" action="exam_form.php?action=delete&id=<?= $exam['id'] ?>&student_id=<?= $student['id'] ?>">
        <button type="submit">Да, удалить</button>
        <a href="exam_list.php?student_id=<?= $student['id'] ?>">Отмена</a>
    </form>
</body>
</html>

