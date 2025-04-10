<?php

$uploadFolder = 'uploads';

function createFileBundle()
{
    global $uploadFolder;
    $fileBundleId = uniqid();
    $fileBundlePath = "$uploadFolder/file_bundle_$fileBundleId";
    mkdir($fileBundlePath);
    return $fileBundleId;
}

function connectDatabase()
{
    $host = '127.0.0.1';
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

function insertFileData($conn, $fileBundleId, $fileName, $fileType, $fileSize, $fileExtension)
{
    $stmt = $conn->prepare("INSERT INTO file_data (file_bundle_id, file_name, file_type, file_size, file_extension) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$fileBundleId, $fileName, $fileType, $fileSize, $fileExtension]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileBundleId = createFileBundle();
    $conn = connectDatabase();

    // 파일 확장자 및 크기 제한 설정
    $allowedPhotoExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $allowedAudioExtensions = ['mp3', 'wav'];
    $maxFileSizeMB = 5000;  // 최대 허용 파일 크기 (MB)

    // Process photo upload
    foreach ($_FILES['photos']['name'] as $key => $photoName) {
        // 파일 확장자 확인
        $fileExtension = pathinfo($photoName, PATHINFO_EXTENSION);
        if (!in_array(strtolower($fileExtension), $allowedPhotoExtensions)) {
            die("Error: Unsupported file extension for photo: $photoName");
        }

        // 파일 크기 확인
        $fileSizeMB = $_FILES['photos']['size'][$key] / (1024 * 1024);
        if ($fileSizeMB > $maxFileSizeMB) {
            die("Error: File size exceeds the maximum allowed size for photo: $photoName");
        }

        // 파일을 업로드된 디렉터리로 이동
        $photoPath = "$uploadFolder/file_bundle_$fileBundleId/$photoName";
        move_uploaded_file($_FILES['photos']['tmp_name'][$key], $photoPath);

        // 파일 메타데이터 데이터베이스에 저장
        $fileSizeBytes = $_FILES['photos']['size'][$key];
        $fileExtension = pathinfo($photoName, PATHINFO_EXTENSION);

        insertFileData($conn, $fileBundleId, $photoName, 'photo', $fileSizeBytes, $fileExtension);
    }

    // Process audio upload
    foreach ($_FILES['audios']['name'] as $key => $audioName) {
        // 파일 확장자 확인
        $fileExtension = pathinfo($audioName, PATHINFO_EXTENSION);
        if (!in_array(strtolower($fileExtension), $allowedAudioExtensions)) {
            die("Error: Unsupported file extension for audio: $audioName");
        }

        // 파일 크기 확인
        $fileSizeMB = $_FILES['audios']['size'][$key] / (1024 * 1024);
        if ($fileSizeMB > $maxFileSizeMB) {
            die("Error: File size exceeds the maximum allowed size for audio: $audioName");
        }

        // 파일을 업로드된 디렉터리로 이동
        $audioPath = "$uploadFolder/file_bundle_$fileBundleId/$audioName";
        move_uploaded_file($_FILES['audios']['tmp_name'][$key], $audioPath);

        // 파일 메타데이터 데이터베이스에 저장
        $fileSizeBytes = $_FILES['audios']['size'][$key];
        $fileExtension = pathinfo($audioName, PATHINFO_EXTENSION);

        insertFileData($conn, $fileBundleId, $audioName, 'audio', $fileSizeBytes, $fileExtension);
    }

    // Close database connection
    $conn = null;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>File Upload</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="photos[]" id="photoInput" accept="image/*" multiple onchange="previewPhotos()">
        <div class="preview-container" id="photoPreviewContainer"></div>
        <input type="file" name="audios[]" id="audioInput" accept="audio/*" multiple>
        <button type="submit">Upload
