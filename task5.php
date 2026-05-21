<?php
$year1 = date("Y");
$year2 = date("Y", strtotime("now"));
$year3 = new DateTime()->format("Y");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Task 5</title>
    <link rel="stylesheet" href="src/styles/styles.css">
</head>
<body>
<h1>Task 5</h1>
<p>С помощью date('Y'): <?= $year1 ?></p>
<p>С помощью str-to-time: <?= $year2 ?></p>
<p>С помощью DateTime: <?= $year3 ?></p>
<a href="index.php">На главную</a>
</body>
</html>