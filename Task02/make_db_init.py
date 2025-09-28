import csv
import sqlite3
from pathlib import Path

class MovieDatabaseBuilder:
    def __init__(self, db_name='movies_rating.db'):
        self.db_name = db_name
        self.connection = None
        self.dataset_path = Path("dataset")
        
    def _get_release_year(self, movie_title):
        """Извлекает год выпуска из названия фильма"""
        try:
            open_bracket = movie_title.rindex("(")
            close_bracket = movie_title.rindex(")")
            if open_bracket < close_bracket:
                year_candidate = movie_title[open_bracket+1:close_bracket]
                if year_candidate.isnumeric() and len(year_candidate) == 4:
                    return int(year_candidate)
        except ValueError:
            pass
        return None
    
    def _establish_connection(self):
        """Создает подключение к базе данных"""
        self.connection = sqlite3.connect(self.db_name)
        return self.connection.cursor()
    
    def _remove_existing_tables(self):
        """Удаляет существующие таблицы если они есть"""
        tables_to_drop = ['movies', 'ratings', 'tags', 'users']
        cursor = self.connection.cursor()
        for table in tables_to_drop:
            cursor.execute(f"DROP TABLE IF EXISTS {table}")
    
    def _setup_database_schema(self):
        """Создает структуру таблиц базы данных"""
        schema_definitions = {
            'movies': """
                CREATE TABLE movies (
                    id INTEGER PRIMARY KEY,
                    title TEXT NOT NULL,
                    year INTEGER,
                    genres TEXT
                )
            """,
            'ratings': """
                CREATE TABLE ratings (
                    id INTEGER PRIMARY KEY,
                    user_id INTEGER,
                    movie_id INTEGER,
                    rating REAL,
                    timestamp INTEGER
                )
            """,
            'tags': """
                CREATE TABLE tags (
                    id INTEGER PRIMARY KEY,
                    user_id INTEGER,
                    movie_id INTEGER,
                    tag TEXT,
                    timestamp INTEGER
                )
            """,
            'users': """
                CREATE TABLE users (
                    id INTEGER PRIMARY KEY,
                    name TEXT,
                    email TEXT,
                    gender TEXT,
                    register_date TEXT,
                    occupation TEXT
                )
            """
        }
        
        cursor = self.connection.cursor()
        for table_sql in schema_definitions.values():
            cursor.execute(table_sql)
    
    def _import_user_data(self):
        """Загружает данные о пользователях"""
        user_file = self.dataset_path / "users.txt"
        cursor = self.connection.cursor()
        
        with user_file.open('r', encoding='utf-8') as file:
            for record in file:
                fields = record.strip().split('|')
                if len(fields) == 6:
                    cursor.execute(
                        "INSERT INTO users VALUES (?, ?, ?, ?, ?, ?)", 
                        [int(fields[0]), fields[1], fields[2], fields[3], fields[4], fields[5]]
                    )
    
    def _import_movie_data(self):
        """Загружает данные о фильмах"""
        movie_file = self.dataset_path / "movies.csv"
        cursor = self.connection.cursor()
        
        with movie_file.open('r', encoding='utf-8') as file:
            csv_reader = csv.reader(file)
            headers = next(csv_reader)
            
            for row in csv_reader:
                movie_data = {
                    'id': int(row[0]),
                    'title': row[1],
                    'genres': row[2],
                    'year': self._get_release_year(row[1])
                }
                cursor.execute(
                    "INSERT INTO movies VALUES (?, ?, ?, ?)",
                    [movie_data['id'], movie_data['title'], movie_data['year'], movie_data['genres']]
                )
    
    def _import_rating_data(self):
        """Загружает данные о рейтингах"""
        rating_file = self.dataset_path / "ratings.csv"
        cursor = self.connection.cursor()
        
        with rating_file.open('r', encoding='utf-8') as file:
            csv_reader = csv.DictReader(file)
            for index, record in enumerate(csv_reader, 1):
                cursor.execute(
                    "INSERT INTO ratings VALUES (?, ?, ?, ?, ?)",
                    [index, int(record['userId']), int(record['movieId']), 
                     float(record['rating']), int(record['timestamp'])]
                )
    
    def _import_tag_data(self):
        """Загружает данные о тегах"""
        tag_file = self.dataset_path / "tags.csv"
        cursor = self.connection.cursor()
        
        with tag_file.open('r', encoding='utf-8') as file:
            csv_reader = csv.DictReader(file)
            for index, record in enumerate(csv_reader, 1):
                cursor.execute(
                    "INSERT INTO tags VALUES (?, ?, ?, ?, ?)",
                    [index, int(record['userId']), int(record['movieId']), 
                     record['tag'], int(record['timestamp'])]
                )
    
    def construct_database(self):
        """Основной метод для построения базы данных"""
        try:
            cursor = self._establish_connection()
            self._remove_existing_tables()
            self._setup_database_schema()
            
            # Загрузка данных в определенном порядке
            data_import_methods = [
                self._import_user_data,
                self._import_movie_data,
                self._import_rating_data,
                self._import_tag_data
            ]
            
            for import_method in data_import_methods:
                import_method()
            
            self.connection.commit()
            return True
            
        except Exception as e:
            print(f"Произошла ошибка: {e}")
            if self.connection:
                self.connection.rollback()
            return False
        finally:
            if self.connection:
                self.connection.close()

def execute():
    """Функция для запуска процесса создания базы данных"""
    builder = MovieDatabaseBuilder()
    success = builder.construct_database()
    
    if success:
        print("База данных movies_rating.db успешно создана!")
    else:
        print("Возникли проблемы при создании базы данных")

if __name__ == "__main__":
    execute()