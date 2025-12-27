-- Создание таблицы учебных программ (направлений подготовки)
CREATE TABLE IF NOT EXISTS education_programs (
    program_id INTEGER PRIMARY KEY AUTOINCREMENT,
    program_code VARCHAR(20) NOT NULL UNIQUE,
    program_name VARCHAR(200) NOT NULL,
    qualification_level VARCHAR(50) NOT NULL CHECK(qualification_level IN ('бакалавриат', 'магистратура', 'специалитет')),
    created_date DATE DEFAULT CURRENT_DATE
);

-- Создание таблицы учебных групп
CREATE TABLE IF NOT EXISTS student_groups (
    group_id INTEGER PRIMARY KEY AUTOINCREMENT,
    group_code VARCHAR(20) NOT NULL UNIQUE,
    program_id INTEGER NOT NULL,
    start_year INTEGER NOT NULL,
    end_year INTEGER NOT NULL,
    FOREIGN KEY (program_id) REFERENCES education_programs(program_id) ON DELETE CASCADE,
    CHECK(start_year < end_year)
);

-- Создание таблицы дисциплин
CREATE TABLE IF NOT EXISTS disciplines (
    discipline_id INTEGER PRIMARY KEY AUTOINCREMENT,
    discipline_code VARCHAR(20) NOT NULL UNIQUE,
    discipline_name VARCHAR(200) NOT NULL,
    hours_total INTEGER NOT NULL CHECK(hours_total > 0),
    hours_lectures INTEGER NOT NULL CHECK(hours_lectures >= 0),
    hours_practice INTEGER NOT NULL CHECK(hours_practice >= 0),
    CHECK(hours_lectures + hours_practice <= hours_total)
);

-- Создание таблицы учебных планов (связь групп и дисциплин)
CREATE TABLE IF NOT EXISTS academic_plans (
    plan_id INTEGER PRIMARY KEY AUTOINCREMENT,
    group_id INTEGER NOT NULL,
    discipline_id INTEGER NOT NULL,
    semester INTEGER NOT NULL CHECK(semester BETWEEN 1 AND 12),
    assessment_type VARCHAR(10) NOT NULL CHECK(assessment_type IN ('экзамен', 'зачет')),
    academic_year VARCHAR(9) NOT NULL, -- формат: 2020/2021
    FOREIGN KEY (group_id) REFERENCES student_groups(group_id) ON DELETE CASCADE,
    FOREIGN KEY (discipline_id) REFERENCES disciplines(discipline_id) ON DELETE CASCADE,
    UNIQUE(group_id, discipline_id, semester, academic_year)
);

-- Создание таблицы студентов
CREATE TABLE IF NOT EXISTS students (
    student_id INTEGER PRIMARY KEY AUTOINCREMENT,
    last_name VARCHAR(50) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    patronymic VARCHAR(50),
    birth_date DATE NOT NULL,
    gender VARCHAR(1) NOT NULL CHECK(gender IN ('М', 'Ж')),
    group_id INTEGER NOT NULL,
    enrollment_year INTEGER NOT NULL,
    is_active BOOLEAN DEFAULT 1,
    FOREIGN KEY (group_id) REFERENCES student_groups(group_id) ON DELETE SET NULL
);

-- Создание таблицы результатов экзаменов
CREATE TABLE IF NOT EXISTS exam_results (
    result_id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER NOT NULL,
    plan_id INTEGER NOT NULL,
    exam_date DATE NOT NULL,
    grade INTEGER CHECK(grade BETWEEN 2 AND 5),
    attempt_number INTEGER DEFAULT 1 CHECK(attempt_number BETWEEN 1 AND 3),
    is_retake BOOLEAN DEFAULT 0,
    teacher_name VARCHAR(150),
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES academic_plans(plan_id) ON DELETE CASCADE,
    UNIQUE(student_id, plan_id, attempt_number)
);

-- Создание таблицы результатов зачетов
CREATE TABLE IF NOT EXISTS credit_results (
    credit_id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER NOT NULL,
    plan_id INTEGER NOT NULL,
    credit_date DATE NOT NULL,
    is_passed BOOLEAN NOT NULL,
    teacher_name VARCHAR(150),
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES academic_plans(plan_id) ON DELETE CASCADE,
    UNIQUE(student_id, plan_id)
);

-- Создание индексов для оптимизации запросов
CREATE INDEX idx_students_group ON students(group_id);
CREATE INDEX idx_students_name ON students(last_name, first_name);
CREATE INDEX idx_exam_results_student ON exam_results(student_id);
CREATE INDEX idx_exam_results_plan ON exam_results(plan_id);
CREATE INDEX idx_academic_plans_group ON academic_plans(group_id);
CREATE INDEX idx_academic_plans_discipline ON academic_plans(discipline_id);
CREATE INDEX idx_groups_program ON student_groups(program_id);

-- Заполнение тестовыми данными

-- 1. Добавляем учебные программы
INSERT INTO education_programs (program_code, program_name, qualification_level) VALUES
('01.03.02', 'Прикладная математика и информатика', 'бакалавриат'),
('02.03.02', 'Фундаментальная информатика и информационные технологии', 'бакалавриат'),
('01.04.02', 'Прикладная математика и информатика', 'магистратура');

-- 2. Добавляем учебные группы
INSERT INTO student_groups (group_code, program_id, start_year, end_year) VALUES
('ПМИ-101', 1, 2020, 2024),
('ПМИ-102', 1, 2020, 2024),
('ФИИТ-101', 2, 2020, 2024),
('ПМИ-201', 1, 2021, 2025),
('ПМИ-301', 1, 2019, 2023);

-- 3. Добавляем дисциплины
INSERT INTO disciplines (discipline_code, discipline_name, hours_total, hours_lectures, hours_practice) VALUES
('МАТ-101', 'Математический анализ', 144, 72, 72),
('ИНФ-101', 'Программирование', 108, 36, 72),
('ФИЗ-101', 'Физика', 108, 54, 54),
('ДИФ-101', 'Дифференциальные уравнения', 72, 36, 36),
('БД-201', 'Базы данных', 72, 36, 36),
('АЛГ-101', 'Алгебра и геометрия', 108, 54, 54),
('ТВ-201', 'Теория вероятностей', 72, 36, 36);

-- 4. Добавляем учебные планы
INSERT INTO academic_plans (group_id, discipline_id, semester, assessment_type, academic_year) VALUES
(1, 1, 1, 'экзамен', '2020/2021'),
(1, 2, 1, 'экзамен', '2020/2021'),
(1, 3, 1, 'экзамен', '2020/2021'),
(1, 4, 2, 'экзамен', '2020/2021'),
(1, 5, 3, 'экзамен', '2021/2022'),
(1, 6, 1, 'зачет', '2020/2021'),
(1, 7, 4, 'экзамен', '2021/2022'),
(2, 1, 1, 'экзамен', '2020/2021'),
(2, 2, 1, 'экзамен', '2020/2021');

-- 5. Добавляем студентов
INSERT INTO students (last_name, first_name, patronymic, birth_date, gender, group_id, enrollment_year) VALUES
('Иванов', 'Иван', 'Иванович', '2002-03-15', 'М', 1, 2020),
('Петрова', 'Мария', 'Сергеевна', '2001-11-22', 'Ж', 1, 2020),
('Сидоров', 'Алексей', 'Петрович', '2002-01-30', 'М', 1, 2020),
('Кузнецова', 'Анна', 'Владимировна', '2002-07-14', 'Ж', 1, 2020),
('Смирнов', 'Дмитрий', 'Александрович', '2002-05-18', 'М', 2, 2020),
('Фёдорова', 'Екатерина', 'Игоревна', '2001-12-10', 'Ж', 2, 2020),
('Попов', 'Артём', 'Витальевич', '2002-02-25', 'М', 3, 2020),
('Васильев', 'Максим', 'Олегович', '2002-08-05', 'М', 3, 2020);

-- 6. Добавляем результаты экзаменов
INSERT INTO exam_results (student_id, plan_id, exam_date, grade, attempt_number) VALUES
(1, 1, '2021-01-20', 5, 1),
(1, 2, '2021-01-25', 4, 1),
(1, 3, '2021-01-28', 5, 1),
(2, 1, '2021-01-20', 4, 1),
(2, 2, '2021-01-25', 4, 1),
(2, 3, '2021-01-28', 5, 1),
(3, 1, '2021-01-20', 3, 1),
(3, 2, '2021-01-25', 3, 1),
(3, 3, '2021-01-28', 4, 1),
(4, 1, '2021-01-20', 5, 1),
(4, 2, '2021-01-25', 5, 1),
(4, 3, '2021-01-28', 5, 1),
(5, 8, '2021-01-20', 2, 1),
(5, 9, '2021-01-25', 3, 1),
(6, 8, '2021-01-20', 5, 1),
(6, 9, '2021-01-25', 4, 1);

-- 7. Добавляем результаты зачетов
INSERT INTO credit_results (student_id, plan_id, credit_date, is_passed, teacher_name) VALUES
(1, 6, '2020-12-20', 1, 'Профессор Сидоров А.П.'),
(2, 6, '2020-12-20', 1, 'Профессор Сидоров А.П.'),
(3, 6, '2020-12-20', 1, 'Профессор Сидоров А.П.'),
(4, 6, '2020-12-20', 1, 'Профессор Сидоров А.П.');

-- Триггер для автоматического создания новой группы при переходе на следующий курс
CREATE TRIGGER IF NOT EXISTS increment_group_code
AFTER UPDATE OF end_year ON student_groups
WHEN NEW.end_year = OLD.end_year + 1
BEGIN
    INSERT INTO student_groups (group_code, program_id, start_year, end_year)
    SELECT 
        substr(group_code, 1, length(group_code)-3) || 
        CAST(CAST(substr(group_code, length(group_code)-2, 3) AS INTEGER) + 100 AS TEXT),
        program_id,
        NEW.start_year + 1,
        NEW.end_year + 1
    FROM student_groups 
    WHERE group_id = OLD.group_id;
END;

-- Представление для рейтинга студентов
CREATE VIEW IF NOT EXISTS student_rating AS
SELECT 
    s.student_id,
    s.last_name || ' ' || s.first_name || COALESCE(' ' || s.patriom, '') as full_name,
    g.group_code,
    AVG(er.grade) as average_grade,
    COUNT(er.grade) as exams_count
FROM students s
JOIN student_groups g ON s.group_id = g.group_id
LEFT JOIN exam_results er ON s.student_id = er.student_id
WHERE er.grade IS NOT NULL
GROUP BY s.student_id, s.last_name, s.first_name, s.patronymic, g.group_code;

-- Представление для успеваемости групп
CREATE VIEW IF NOT EXISTS group_performance AS
SELECT 
    g.group_code,
    ep.program_name,
    AVG(er.grade) as group_average_grade,
    COUNT(DISTINCT s.student_id) as total_students,
    SUM(CASE WHEN er.grade = 5 THEN 1 ELSE 0 END) as excellent_count,
    SUM(CASE WHEN er.grade = 4 THEN 1 ELSE 0 END) as good_count,
    SUM(CASE WHEN er.grade = 3 THEN 1 ELSE 0 END) as satisfactory_count,
    SUM(CASE WHEN er.grade = 2 THEN 1 ELSE 0 END) as unsatisfactory_count
FROM student_groups g
JOIN education_programs ep ON g.program_id = ep.program_id
JOIN students s ON g.group_id = s.group_id
LEFT JOIN exam_results er ON s.student_id = er.student_id
GROUP BY g.group_id, g.group_code, ep.program_name;