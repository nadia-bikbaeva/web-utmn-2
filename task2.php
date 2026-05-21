<?php
$result = "";
$a = rand(1,15);

switch ($a) {
    case 0: $result .= "0 ";
    case 1: $result .= "1 ";
    case 2: $result .= "2 ";
    case 3: $result .= "3 ";
    case 4: $result .= "4 ";
    case 5: $result .= "5 ";
    case 6: $result .= "6 ";
    case 7: $result .= "7 ";
    case 8: $result .= "8 ";
    case 9: $result .= "9 ";
    case 10: $result .= "10 ";
    case 11: $result .= "11 ";
    case 12: $result .= "12 ";
    case 13: $result .= "13 ";
    case 14: $result .= "14 ";
    case 15: $result .= "15";
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
<p><strong>a = <?= $a ?></strong></p>
<p>Числа от <?= $a ?> до 15: <strong><?= $result ?></strong></p>
<a href="index.php">На главную</a>
</body>
</html>
