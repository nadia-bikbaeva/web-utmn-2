<?php
$a = 8;
$b = -4;

if ($a >= 0 && $b >= 0) {
    $result = "Оба положительные (разность):". ($a - $b);
} elseif ($a < 0 && $b < 0) {
    $result = "Оба отрицательные. Произведение: " . ($a * $b);
} else {
    $result = "Разные знаки. Сумма: " . ($a + $b);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Task 1</title>
    <link rel="stylesheet" href="src/styles/styles.css">
</head>
<body>
<h1>Task 1</h1>
<p><strong>a = <?= $a ?>, b = <?= $b ?></strong></p>
<p><?= $result ?></p>
<a href="index.php">На главную</a>
</body>
</html>
