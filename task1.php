<?php
function printNumbers(): void
{
    $i = 0;
    do {
        if ($i === 0) {
            echo "$i – это ноль.<br>";
        } elseif ($i % 2 === 0) {
            echo "$i – чётное число.<br>";
        } else {
            echo "$i – нечётное число.<br>";
        }
        $i++;
    } while ($i <= 10);
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
    <p><?php printNumbers() ?></p>
    <a href="index.php">На главную</a>
</body>
</html>
