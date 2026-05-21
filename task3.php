<?php
$arg1 = rand(0, 100);
$arg2 = rand(0, 100);
function add($arg1, $arg2) {
    return $arg1 + $arg2;
}

function subtract($arg1, $arg2) {
    return $arg1 - $arg2;
}

function multiply($arg1, $arg2) {
    return $arg1 * $arg2;
}

function divide($arg1, $arg2) {
    if ($arg2 == 0) {
        return "Ошибка: деление на ноль!";
    }
    return $arg1 / $arg2;
}
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
<p><?=$arg1 ?> + <?=$arg2 ?> = <?= add($arg1, $arg2) ?></p>
<p><?=$arg1 ?> - <?=$arg2 ?> = <?= subtract($arg1, $arg2) ?></p>
<p><?=$arg1 ?> * <?=$arg2 ?> = <?= multiply($arg1, $arg2) ?></p>
<p><?=$arg1 ?> / <?=$arg2 ?> = <?= divide($arg1, $arg2) ?></p>
<a href="index.php">На главную</a>
</body>
</html>


