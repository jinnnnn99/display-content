import psycopg2
from psycopg2 import sql

# PostgreSQL 연결 정보
host = 'localhost'
dbname = 'yourdbname'
username = 'yourusername'
password = 'newpassword'

try:
    # PostgreSQL에 연결
    connection = psycopg2.connect(host=host, dbname=dbname, user=username, password=password)
    cursor = connection.cursor()

    # FileBundle 테이블 생성
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS file_bundles (
            id SERIAL PRIMARY KEY,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    """)

    # Photos 테이블 생성
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS photos (
            id SERIAL PRIMARY KEY,
            file_name VARCHAR(255) NOT NULL,
            file_bundle_id INTEGER NOT NULL,
            FOREIGN KEY (file_bundle_id) REFERENCES file_bundles (id) ON DELETE CASCADE
        )
    """)

    # Audios 테이블 생성
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS audios (
            id SERIAL PRIMARY KEY,
            file_name VARCHAR(255) NOT NULL,
            file_bundle_id INTEGER NOT NULL,
            FOREIGN KEY (file_bundle_id) REFERENCES file_bundles (id) ON DELETE CASCADE
        )
    """)

    # 데이터 삽입 예시
    cursor.execute("INSERT INTO file_bundles (created_at) VALUES (NOW()) RETURNING id;")
    file_bundle_id = cursor.fetchone()[0]

    cursor.execute("INSERT INTO photos (file_name, file_bundle_id) VALUES (%s, %s);", ('photo1.jpg', file_bundle_id))
    cursor.execute("INSERT INTO audios (file_name, file_bundle_id) VALUES (%s, %s);", ('audio1.mp3', file_bundle_id))

    # 변경사항 저장
    connection.commit()

    print("Database setup completed successfully.")

except psycopg2.Error as e:
    print(f"Error: {e}")

finally:
    # 연결 종료
    if connection:
        connection.close()
