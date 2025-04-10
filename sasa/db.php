<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Site</title>
</head>
<body>
    <h1>File Upload Site</h1>

    <?php
    // PostgreSQL 연결 정보
    $host = 'localhost';
    $dbname = 'yourdbname';
    $username = 'yourusername';
    $password = 'newpassword';

    try {
        // PostgreSQL에 연결
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname;",$username,$password);

        // File Bundles 조회
        $stmt = $pdo->prepare("SELECT * FROM file_bundles");
        $stmt->execute();
        $fileBundles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 각 File Bundle에 대한 정보 출력
        foreach ($fileBundles as $fileBundle) {
            echo '<h2>File Bundle ID: ' . $fileBundle['id'] . '</h2>';
            echo '<p>Created At: ' . $fileBundle['created_at'] . '</p>';

            // Photos 조회
            $stmt = $pdo->prepare("SELECT * FROM photos WHERE file_bundle_id = :fileBundleId");
            $stmt->bindParam(':fileBundleId', $fileBundle['id'], PDO::PARAM_INT);
            $stmt->execute();
            $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($photos)) {
                echo '<h3>Photos:</h3>';
                echo '<ul>';
                foreach ($photos as $photo) {
                    echo '<li>' . $photo['file_name'] . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No photos found for this File Bundle.</p>';
            }

            // Audios 조회
            $stmt = $pdo->prepare("SELECT * FROM audios WHERE file_bundle_id = :fileBundleId");
            $stmt->bindParam(':fileBundleId', $fileBundle['id'], PDO::PARAM_INT);
            $stmt->execute();
            $audios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($audios)) {
                echo '<h3>Audios:</h3>';
                echo '<ul>';
                foreach ($audios as $audio) {
                    echo '<li>' . $audio['file_name'] . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No audios found for this File Bundle.</p>';
            }
        }

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
    ?>
</body>
</html>
