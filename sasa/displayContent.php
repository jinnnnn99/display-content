<?php
$imageFile = "uploads/" . basename($_FILES["imageInput"]["name"]);
$audioFile = "uploads/" . basename($_FILES["audioInput"]["name"]);
$descriptionFile = "uploads/description.txt";

echo "<div id='uploadedContent'>";

if (file_exists($imageFile)) {
    echo "<img src='$imageFile' alt='アップロードされた画像' />";
}

if (file_exists($audioFile)) {
    echo "<audio controls><source src='$audioFile' type='audio/mpeg'></audio>";
}

if (file_exists($descriptionFile)) {
    $description = file_get_contents($descriptionFile);
    echo "<div>$description</div>";
}

echo "</div>";
?>
