@echo off
chcp 65001 >nul

echo ========================================
echo   Инициализация базы данных фильмов
echo ========================================

echo Создание структуры базы данных...
..\sqlite\sqlite3.exe movies_rating.db < db_init.sql

if %errorlevel% neq 0 (
    echo ОШИБКА: Не удалось выполнить db_init.sql
    pause
    exit /b %errorlevel%
)

echo Структура базы создана успешно!

echo.
echo Добавление новых пользователей и оценок...
..\sqlite\sqlite3.exe movies_rating.db < data_add.sql

if %errorlevel% neq 0 (
    echo ОШИБКА: Не удалось выполнить data_add.sql
    pause
    exit /b %errorlevel%
)

echo Новые данные добавлены успешно!

echo.
echo ========================================
echo   База данных готова к использованию!
echo ========================================
echo Файл базы данных: movies_rating.db
echo.

pause