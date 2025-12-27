<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление экзамена</title>
    <script>
        function updateDisciplines() {
            const groupId = document.getElementById('group_id').value;
            const course = document.getElementById('course').value;
            const studentId = document.getElementById('student_id').value;
            
            if (groupId && course) {
                fetch('get_disciplines.php?group_id=' + groupId + '&course=' + course + '&student_id=' + studentId)
                    .then(response => response.json())
                    .then(data => {
                        const select = document.getElementById('discipline_id');
                        select.innerHTML = '<option value="">Выберите дисциплину</option>';
                        data.forEach(discipline => {
                            const option = document.createElement('option');
                            option.value = discipline.id;
                            option.textContent = discipline.name;
                            select.appendChild(option);
                        });
                    });
            }
        }
        
        function updateGroups() {
            const studentId = document.getElementById('student_id').value;
            
            if (studentId) {
                fetch('get_student_groups.php?student_id=' + studentId)
                    .then(response => response.json())
                    .then(data => {
                        const select = document.getElementById('group_id');
                        select.innerHTML = '<option value="">Выберите группу</option>';
                        data.forEach(group => {
                            const option = document.createElement('option');
                            option.value = group.id;
                            option.textContent = group.number;
                            select.appendChild(option);
                        });
                    });
            }
        }
    </script>
</head>
<body>
    <h1><?= isset($exam) ? 'Редактирование экзамена' : 'Добавление экзамена' ?></h1>
    
    <form method="POST" action="exam_form.php">
        <?php if (isset($exam)): ?>
            <input type="hidden" name="id" value="<?= $exam['id'] ?>">
        <?php endif; ?>
        <input type="hidden" name="student_id" id="student_id" value="<?= $student['id'] ?>">
        
        <div>
            <label>Студент:</label>
            <input type="text" value="<?= htmlspecialchars($student['last_name'] . ' ' . $student['first_name']) ?>" disabled>
        </div>
        
        <div>
            <label>Группа на момент экзамена:</label>
            <select name="group_id" id="group_id" onchange="updateDisciplines()" required>
                <option value="">Выберите группу</option>
                <!-- Заполнится через JavaScript -->
            </select>
        </div>
        
        <div>
            <label>Курс:</label>
            <select name="course" id="course" onchange="updateDisciplines()" required>
                <option value="">Выберите курс</option>
                <option value="1" <?= (isset($exam) && $exam['course'] == 1) ? 'selected' : '' ?>>1 курс</option>
                <option value="2" <?= (isset($exam) && $exam['course'] == 2) ? 'selected' : '' ?>>2 курс</option>
                <option value="3" <?= (isset($exam) && $exam['course'] == 3) ? 'selected' : '' ?>>3 курс</option>
                <option value="4" <?= (isset($exam) && $exam['course'] == 4) ? 'selected' : '' ?>>4 курс</option>
            </select>
        </div>
        
        <div>
            <label>Дисциплина:</label>
            <select name="discipline_id" id="discipline_id" required>
                <option value="">Сначала выберите группу и курс</option>
            </select>
        </div>
        
        <div>
            <label>Дата экзамена:</label>
            <input type="date" name="exam_date" value="<?= isset($exam) ? $exam['exam_date'] : '' ?>" required>
        </div>
        
        <div>
            <label>Оценка:</label>
            <select name="grade" required>
                <option value="5" <?= (isset($exam) && $exam['grade'] == 5) ? 'selected' : '' ?>>5 (отлично)</option>
                <option value="4" <?= (isset($exam) && $exam['grade'] == 4) ? 'selected' : '' ?>>4 (хорошо)</option>
                <option value="3" <?= (isset($exam) && $exam['grade'] == 3) ? 'selected' : '' ?>>3 (удовлетворительно)</option>
                <option value="2" <?= (isset($exam) && $exam['grade'] == 2) ? 'selected' : '' ?>>2 (неудовлетворительно)</option>
            </select>
        </div>
        
        <div>
            <button type="submit">Сохранить</button>
            <a href="exam_list.php?student_id=<?= $student['id'] ?>">Отмена</a>
        </div>
    </form>
    
    <script>
        // Загружаем группы студента при загрузке страницы
        document.addEventListener('DOMContentLoaded', function() {
            updateGroups();
            <?php if (isset($exam)): ?>
            // При редактировании загружаем дисциплины после небольшой задержки
            setTimeout(function() {
                const groupId = <?= isset($exam['group_id']) ? $exam['group_id'] : 'null' ?>;
                const course = <?= isset($exam['course']) ? $exam['course'] : 'null' ?>;
                if (groupId && course) {
                    document.getElementById('group_id').value = groupId;
                    updateDisciplines();
                    // Устанавливаем выбранную дисциплину после загрузки
                    setTimeout(function() {
                        document.getElementById('discipline_id').value = <?= isset($exam['discipline_id']) ? $exam['discipline_id'] : 'null' ?>;
                    }, 500);
                }
            }, 500);
            <?php endif; ?>
        });
    </script>
</body>
</html>