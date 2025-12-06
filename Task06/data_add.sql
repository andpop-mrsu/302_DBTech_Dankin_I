-- data_add.sql
-- Добавление новых пользователей (адаптировано под текущую структуру)
INSERT OR IGNORE INTO users (name, email, gender, register_date, occupation)
VALUES
('Данькин Иван Геннадьевич', 'dankini809@gmail.com', 'male', date('now'), 'student'),
('Егор Гришуков Витольевич', 'Grushukov@gmail.com', 'male', date('now'), 'student'),
('Ермаков Егор Александрович', 'Egor@mail.ru', 'male', date('now'), 'student'),
('Кармазов Никита Александрович', 'Nikita@mail.ru', 'male', date('now'), 'student'),
('Китаев Евгений Витальевич', 'Kitaev@gmail.com', 'male', date('now'), 'student');

-- Добавление новых фильмов
INSERT OR IGNORE INTO movies (title, year, genres)
VALUES
('Зеленая книга (2018)', 2018, 'Drama|Comedy'),
('Дюна (2021)', 2021, 'Adventure|Sci-Fi'),
('Оппенгеймер (2023)', 2023, 'Drama|Biography');

-- Оценки для Данькина Ивана
INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.8, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'dankini809@gmail.com' AND m.title = 'Зеленая книга (2018)';

INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.5, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'dankini809@gmail.com' AND m.title = 'Дюна (2021)';

INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.9, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'dankini809@gmail.com' AND m.title = 'Оппенгеймер (2023)';

-- Оценки для Егора Гришукова
INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.7, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Grushukov@gmail.com' AND m.title = 'Зеленая книга (2018)';

INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.6, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Grushukov@gmail.com' AND m.title = 'Дюна (2021)';

INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.8, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Grushukov@gmail.com' AND m.title = 'Оппенгеймер (2023)';

-- Оценки для Егора Ермакова
INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.9, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Egor@mail.ru' AND m.title = 'Зеленая книга (2018)';

INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.7, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Egor@mail.ru' AND m.title = 'Дюна (2021)';

INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 5.0, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Egor@mail.ru' AND m.title = 'Оппенгеймер (2023)';

-- Оценки для Никиты Кармазова
INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.6, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Nikita@mail.ru' AND m.title = 'Зеленая книга (2018)';

INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.8, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Nikita@mail.ru' AND m.title = 'Дюна (2021)';

INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.7, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Nikita@mail.ru' AND m.title = 'Оппенгеймер (2023)';

-- Оценки для Евгения Китаева
INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.5, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Kitaev@gmail.com' AND m.title = 'Зеленая книга (2018)';

INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.9, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Kitaev@gmail.com' AND m.title = 'Дюна (2021)';

INSERT OR IGNORE INTO ratings (user_id, movie_id, rating, timestamp)
SELECT u.id, m.id, 4.8, strftime('%s','now')
FROM users u, movies m 
WHERE u.email = 'Kitaev@gmail.com' AND m.title = 'Оппенгеймер (2023)';