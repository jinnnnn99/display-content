<?php
// download.php

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

// 파일 ID 파라미터 확인
if (isset($_GET['file_id'])) {
    $fileId = $_GET['file_id'];

    // 데이터베이스 연결
    $conn = connectDatabase();

    // File Data 조회
    $stmt = $conn->prepare("SELECT * FROM file_data WHERE id = ?");
    $stmt->execute([$fileId]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    // 데이터베이스 연결 종료
    $conn = null;

    if ($file) {
        $filePath = 'uploads/file_bundle_' . $file['file_bundle_id'] . '/' . $file['file_name'];

        // 파일 다운로드 헤더 설정
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file['file_name'] . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        // 파일 읽기 및 출력
        readfile($filePath);
        exit;
    } else {
        echo 'File not found.';
    }
} else {
    echo 'Invalid file ID.';
}

