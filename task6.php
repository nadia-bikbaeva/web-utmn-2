<?php
$regions = [
        "Московская область" => ["Москва", "Зеленоград", "Клин"],
        "Ленинградская область" => ["Санкт-Петербург", "Всеволожск", "Павловск", "Кронштадт"],
        "Рязанская область" => ["Рязань", "Касимов", "Скопин", "Кораблино"]
];

function searchRegionK($regions){
    $result = "";
    foreach ($regions as $region => $cities) {
        $filteredCities = [];
        foreach ($cities as $city) {
            if (mb_substr($city, 0, 1, "UTF-8") === "К") {
                $filteredCities[] = $city;
            }
        }
        if (!empty($filteredCities)) {
            $result .= "<strong>{$region}:</strong><br>";
            $result .= implode(", ", $filteredCities) . ".<br><br>";
        }
    }
    return $result;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Task 6</title>
    <link rel="stylesheet" href="src/styles/styles.css">
</head>
<body>
<h1>Task 6</h1>
<p><?= searchRegionK($regions) ?></p>
<a href="index.php">На главную</a>
</body>
</html>