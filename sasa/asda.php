<?php
// 데이터베이스 연결 함수
function connectDatabase()
{
    $host = 'localhost';
    $dbname = 'yourdbname';
    $username = 'yourusername';
    $password = 'newpassword';

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// 연결
$conn = connectDatabase();

// 테이블 목록 조회 쿼리
$tablesQuery = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'";
$stmt = $conn->query($tablesQuery);

// 테이블 목록 가져오기
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 데이터베이스 연결 종료
$conn = null;

// 테이블 목록 출력
echo '<h2>Tables in the database:</h2>';
echo '<ul>';
foreach ($tables as $table) {
    echo "<li>$table</li>";
}
echo '</ul>';

