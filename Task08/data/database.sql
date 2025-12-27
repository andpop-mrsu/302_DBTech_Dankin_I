-- Создание таблицы групп
CREATE TABLE IF NOT EXISTS groups (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    number VARCHAR(20) NOT NULL UNIQUE
);

-- Создание таблицы студентов
CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    last_name VARCHAR(50) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    gender VARCHAR(10) CHECK(gender IN ('male', 'female')) NOT NULL,
    group_id INTEGER NOT NULL,
    FOREIGN KEY (group_id) REFERENCES groups(id)
);

-- Создание таблицы дисциплин
CREATE TABLE IF NOT EXISTS disciplines (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    direction VARCHAR(100) NOT NULL,
    course INTEGER NOT NULL
);

-- Создание таблицы экзаменов
CREATE TABLE IF NOT EXISTS exams (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id INTEGER NOT NULL,
    discipline_id INTEGER NOT NULL,
    exam_date DATE NOT NULL,
    grade INTEGER CHECK(grade BETWEEN 2 AND 5) NOT NULL,
    course INTEGER NOT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (discipline_id) REFERENCES disciplines(id)
);

-- Заполняем справочник групп
INSERT INTO groups (number) VALUES 
    ('ИТ-101'),
    ('ИТ-102'),
    ('ПИ-201'),
    ('ПИ-202'),
    ('КБ-301'),
    ('КБ-302');

-- Заполняем дисциплины
INSERT INTO disciplines (name, direction, course) VALUES
    ('Программирование', 'Информационные технологии', 1),
    ('Базы данных', 'Информационные технологии', 2),
    ('Веб-разработка', 'Информационные технологии', 3),
    ('Математический анализ', 'Прикладная информатика', 1),
    ('Теория вероятностей', 'Прикладная информатика', 2),
    ('Экономика', 'Кибербезопасность', 1),
    ('Криптография', 'Кибербезопасность', 3),
    ('Сетевые технологии', 'Кибербезопасность', 2);