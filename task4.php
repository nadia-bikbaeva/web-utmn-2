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

function mathOperation($arg1, $arg2, $operation) {
    switch ($operation) {
        case "сложение":
        case "+":
            return add($arg1, $arg2);

        case "вычитание":
        case "-":
            return subtract($arg1, $arg2);

        case "умножение":
        case "*":
            return multiply($arg1, $arg2);

        case "деление":
        case "/":
            return divide($arg1, $arg2);

        default:
            return "Неизвестная операция";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Task 4</title>
    <link rel="stylesheet" href="src/styles/styles.css">
</head>
<body>
<h1>Task 4</h1>
<p><?=$arg1 ?> + <?=$arg2 ?> = <?= mathOperation($arg1, $arg2, '+') ?></p>
<p><?=$arg1 ?> - <?=$arg2 ?> = <?= mathOperation($arg1, $arg2, '-') ?></p>
<p><?=$arg1 ?> * <?=$arg2 ?> = <?= mathOperation($arg1, $arg2, '*') ?></p>
<p><?=$arg1 ?> / <?=$arg2 ?> = <?= mathOperation($arg1, $arg2, '/') ?></p>
<a href="index.php">На главную</a>
</body>
</html>


