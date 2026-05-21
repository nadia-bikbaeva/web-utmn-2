<?php
$regions = [
        "Московская область" => ["Москва", "Зеленоград", "Клин"],
        "Ленинградская область" => ["Санкт-Петербург", "Всеволожск", "Павловск", "Кронштадт"],
        "Рязанская область" => ["Рязань", "Касимов", "Скопин", "Кораблино"]
];

function buildRegion($regions)
{
    $result = "";
    foreach ($regions as $region => $cities) {
        $result .= "<strong>{$region}:</strong><br>";
        $result .= implode(", ", $cities) . ".<br><br>";
    }
    return $result;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Task 2</title>
    <link rel="stylesheet" href="src/styles/styles.css">
</head>
<body>
<h1>Task 2</h1>
<p><?= buildRegion($regions) ?></p>
<a href="index.php">На главную</a>
</body>
</html>
