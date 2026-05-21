<?php
$dir = 'img/';
$thumbDir = 'img/thumbs/';
$logFile = 'log.txt';
$currentDateTime = date('Y-m-d H:i:s') . PHP_EOL;

file_put_contents($logFile, $currentDateTime, FILE_APPEND);
$lines = file($logFile);

if (count($lines) >= 10) {
    $x = 0;
    while (file_exists("log$x.txt")) {
        $x++;
    }
    rename($logFile, "log$x.txt");
    file_put_contents($logFile, "");
}
if (!is_dir($dir)) mkdir($dir, 0777, true);
if (!is_dir($thumbDir)) mkdir($thumbDir, 0777, true);

function renderGallery($dir, $thumbDir): void
{
    $files = array_diff(scandir($dir), ['.', '..', 'thumbs']);

    if (empty($files)) {
        echo "<p>Галерея пуста. Загрузите первую картинку!</p>";
        return;
    }
    echo "<div class='gallery'>";
    foreach ($files as $file) {
        $original = $dir . $file;
        $thumb = $thumbDir . $file;
        $imageSrc = file_exists($thumb) ? $thumb : $original;
        echo "
            <a href='$original' target='_blank'>
                <img src='$imageSrc' width='150' alt='Изображение'>
            </a>";
    }
    echo "</div>";
}

function createThumbnail($src, $dest, $targetWidth = 150): bool
{
    $type = strtolower(pathinfo($src, PATHINFO_EXTENSION));
    if ($type === 'jpeg' || $type === 'jpg') {
        $sourceImage = imagecreatefromjpeg($src);
    } elseif ($type === 'png') {
        $sourceImage = imagecreatefrompng($src);
    } else {
        return false;
    }

    $width = imagesx($sourceImage);
    $height = imagesy($sourceImage);
    $targetHeight = floor($height * ($targetWidth / $width));
    $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

    if ($type === 'png') {
        imagealphablending($thumbnail, false);
        imagesavealpha($thumbnail, true);
    }
    imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

    if ($type === 'jpeg' || $type === 'jpg') {
        imagejpeg($thumbnail, $dest, 85);
    } elseif ($type === 'png') {
        imagepng($thumbnail, $dest, 8);
    }

    imagedestroy($sourceImage);
    imagedestroy($thumbnail);
    return true;
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $file = $_FILES['photo'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $type = mime_content_type($file['tmp_name']);
        $fileSize = $file['size'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!in_array($type, $allowedTypes)) {
            $message = "Ошибка: Можно загружать только картинки формата JPG, JPEG или PNG.";
        }
        elseif ($fileSize > 2097152) {
            $message = "Ошибка: Размер файла не должен превышать 2 МБ.";
        }
        else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('img_', true) . '.' . $ext;
            $uploadPath = $dir . $fileName;
            $thumbPath = $thumbDir . $fileName;
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                createThumbnail($uploadPath, $thumbPath);
                header("Location: gallery.php");
                exit;
            } else {
                $message = "Ошибка при сохранении файла.";
            }
        }
    } else {
        $message = "Файл не был загружен или произошла ошибка сервера.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Фотогалерея</title>
    <link rel="stylesheet" href="src/styles/styles.css">
</head>
<body>
<h1>Моя фотогалерея</h1>
<?php renderGallery($dir, $thumbDir); ?>
<hr>
<div class="form-box">
    <h3>Загрузить новое изображение</h3>
    <?php if (!empty($message)): ?>
        <p class="error"><?= $message ?></p>
    <?php endif; ?>
    <form action="gallery.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="photo" id="file-upload" class="input-file" required>
        <label for="file-upload" class="input-file-btn">
            Выбрать изображение
        </label>
        <button type="submit">Загрузить</button>
    </form>
</div>
</body>
</html>