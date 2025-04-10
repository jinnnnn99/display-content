<?php
include('config.php');

// 데이터베이스 연결
$connection = new PDO("pgsql:host={$databaseConfig['host']};dbname={$databaseConfig['dbname1']}", $databaseConfig['username'], $databaseConfig['password']);

// 파일 목록 조회 쿼리
$query = "SELECT * FROM file_data";
$stmt = $connection->query($query);
$filesFromDB = $stmt->fetchAll(PDO::FETCH_ASSOC);

// uploads 폴더 내의 파일 목록 읽기
$uploadFolder = 'uploads';
$filesFromFolder = scandir($uploadFolder);
$filesFromFolder = array_diff($filesFromFolder, array('.', '..')); // . 및 .. 제거

// 연결 종료
$connection = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File List</title>
</head>
<body>

    <h2>File List</h2>
    <ul>
        <?php
        $allFiles = array_merge($filesFromDB, $filesFromFolder);
        if (!empty($allFiles)): ?>
            <?php foreach ($allFiles as $file): ?>
                <li>
                    <?php
                    // 파일 이름 또는 데이터베이스에서 가져온 정보에 따라 표시
                    $fileName = isset($file['file_name']) ? $file['file_name'] : $file;
                    ?>
                    <a href="<?php echo "{$uploadFolder}/{$fileName}"; ?>" download><?php echo $fileName; ?></a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No files found.</li>
        <?php endif; ?>
    </ul>

</body>
</html>
