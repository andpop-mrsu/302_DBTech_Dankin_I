<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список студентов</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .actions a { margin-right: 10px; }
    </style>
</head>
<body>
    <h1>Список студентов</h1>
    
    <!-- Фильтр по группе -->
    <form method="GET" action="">
        <label for="group_id">Фильтр по группе:</label>
        <select name="group_id" id="group_id" onchange="this.form.submit()">
            <option value="">Все группы</option>
            <?php foreach ($groups as $group): ?>
                <option value="<?= htmlspecialchars($group['id']) ?>" 
                    <?= ($_GET['group_id'] ?? '') == $group['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($group['number']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    
    <!-- Таблица студентов -->
    <table>
        <thead>
            <tr>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Отчество</th>
                <th>Пол</th>
                <th>Группа</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($students)): ?>
                <tr><td colspan="6">Нет данных</td></tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= htmlspecialchars($student['last_name']) ?></td>
                    <td><?= htmlspecialchars($student['first_name']) ?></td>
                    <td><?= htmlspecialchars($student['middle_name'] ?? '') ?></td>
                    <td><?= $student['gender'] == 'male' ? 'Мужской' : 'Женский' ?></td>
                    <td><?= htmlspecialchars($student['group_number']) ?></td>
                    <td class="actions">
                        <a href="student_form.php?id=<?= $student['id'] ?>">Редактировать</a>
                        <a href="student_form.php?action=delete&id=<?= $student['id'] ?>">Удалить</a>
                        <a href="exam_list.php?student_id=<?= $student['id'] ?>">Результаты экзаменов</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <br>
    <a href="student_form.php">Добавить нового студента</a>
</body>
</html>