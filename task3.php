<?php
function transliterate($text) {
    $alphabet = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya'];
    return strtr($text, $alphabet);
}
$string = "привет, мир!";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Task 3</title>
    <link rel="stylesheet" href="src/styles/styles.css">
</head>
<body>
<h1>Task 3</h1>
<p>Исходная строка: <?= $string ?></p>
<p>Транслитерация: <?= transliterate($string) ?></p>
<a href="index.php">На главную</a>
</body>
</html>


