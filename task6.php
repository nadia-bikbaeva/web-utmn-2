<?php
$a = rand(1, 10);
function power($val, $pow): float|int
{
    if ($pow == 0) return 1;
    if ($pow < 0) return 1 / power($val, -$pow);
    return $val * power($val, $pow - 1);
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
<p><?=$a ?> в степени 7 = <?= power($a, 7) ?></p>
<p><?=$a ?> в степени -3 = <?= power($a, -3) ?></p>
<p><?=$a ?> в степени 0 = <?= power($a, 0) ?></p>
<a href="index.php">На главную</a>
</body>
</html>