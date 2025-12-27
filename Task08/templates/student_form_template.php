<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($student) ? 'Редактирование' : 'Добавление' ?> студента</title>
</head>
<body>
    <h1><?= isset($student) ? 'Редактирование студента' : 'Добавление нового студента' ?></h1>
    
    <form method="POST" action="student_form.php">
        <?php if (isset($student)): ?>
            <input type="hidden" name="id" value="<?= $student['id'] ?>">
        <?php endif; ?>
        
        <div>
            <label>Фамилия:</label>
            <input type="text" name="last_name" value="<?= $student['last_name'] ?? '' ?>" required>
        </div>
        
        <div>
            <label>Имя:</label>
            <input type="text" name="first_name" value="<?= $student['first_name'] ?? '' ?>" required>
        </div>
        
        <div>
            <label>Отчество:</label>
            <input type="text" name="middle_name" value="<?= $student['middle_name'] ?? '' ?>">
        </div>
        
        <div>
            <label>Пол:</label>
            <input type="radio" name="gender" value="male" 
                   <?= ($student['gender'] ?? '') == 'male' ? 'checked' : '' ?> required> Мужской
            <input type="radio" name="gender" value="female" 
                   <?= ($student['gender'] ?? '') == 'female' ? 'checked' : '' ?>> Женский
        </div>
        
        <div>
            <label>Группа:</label>
            <select name="group_id" required>
                <option value="">Выберите группу</option>
                <?php foreach ($groups as $group): ?>
                    <option value="<?= $group['id'] ?>" 
                        <?= ($student['group_id'] ?? '') == $group['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($group['number']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <button type="submit">Сохранить</button>
            <a href="index.php">Отмена</a>
        </div>
    </form>
</body>
</html>