<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload and Download</title>
</head>
<body>
    <h1>File Upload and Download </h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="photos[]" accept="image/*" multiple>
        <input type="file" name="audios[]" accept="audio/*" multiple>
        <input type="submit" value="Upload">
    </form>
    <h2>Uploaded File Bundles</h2>
    <ul>
        <?php
        $uploadFolder = 'uploads';
        $fileBundles = glob("$uploadFolder/file_bundle_*");
        foreach ($fileBundles as $fileBundle) {
            $fileBundleId = basename($fileBundle, ".zip");
        ?>
            <li>
                <a href="download.php?file_bundle_id=<?= $fileBundleId ?>" download="file_bundle_<?= $fileBundleId ?>.zip">Download File Bundle <?= $fileBundleId ?></a>
            </li>
        <?php
        }
        ?>
    </ul>
</body>
</html>
