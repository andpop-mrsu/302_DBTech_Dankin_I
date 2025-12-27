<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результаты экзаменов</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Результаты экзаменов студента: <?= htmlspecialchars($student['last_name'] . ' ' . $student['first_name']) ?></h1>
    
    <table>
        <thead>
            <tr>
                <th>Дисциплина</th>
                <th>Дата экзамена</th>
                <th>Курс</th>
                <th>Оценка</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($exams)): ?>
                <tr><td colspan="5">Нет данных</td></tr>
            <?php else: ?>
                <?php foreach ($exams as $exam): ?>
                <tr>
                    <td><?= htmlspecialchars($exam['discipline_name']) ?></td>
                    <td><?= htmlspecialchars($exam['exam_date']) ?></td>
                    <td><?= htmlspecialchars($exam['course']) ?></td>
                    <td><?= htmlspecialchars($exam['grade']) ?></td>
                    <td>
                        <a href="exam_form.php?id=<?= $exam['id'] ?>&student_id=<?= $student['id'] ?>">Редактировать</a>
                        <a href="exam_form.php?action=delete&id=<?= $exam['id'] ?>&student_id=<?= $student['id'] ?>">Удалить</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <br>
    <a href="exam_form.php?student_id=<?= $student['id'] ?>">Добавить экзамен</a>
    <br>
    <a href="index.php">Назад к списку студентов</a>
</body>
</html>