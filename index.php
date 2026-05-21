<?php
$pageTitle = "Практика 16";
$pageHeading = "16 Практика";
$currentYear = date("Y");
function getTimeWithWords() {
    $hours = (int)date('G');
    $minutes = (int)date('i');
    if ($hours % 10 == 1 && $hours % 100 != 11) {
        $hoursWord = "час";
    } elseif (in_array($hours % 10, [2, 3, 4]) && !in_array($hours % 100, [12, 13, 14])) {
        $hoursWord = "часа";
    } else {
        $hoursWord = "часов";
    }
    if ($minutes % 10 == 1 && $minutes % 100 != 11) {
        $minutesWord = "минута";
    } elseif (in_array($minutes % 10, [2, 3, 4]) && !in_array($minutes % 100, [12, 13, 14])) {
        $minutesWord = "минуты";
    } else {
        $minutesWord = "минут";
    }
    return "$hours $hoursWord $minutes $minutesWord";
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="src/styles/styles.css">
</head>
<body>

<header>
    <h1><?php echo $pageHeading; ?></h1>
</header>

<main>
    <p>Страничка охраняется Кошачьей армией!</p>

</main>

</body>
</html>