<?php
$menuItems = [
        ['title' => 'Задание 1', 'link' => 'task1.php'],
        ['title' => 'Задание 2', 'link' => 'task2.php'],
        ['title' => 'Задание 3', 'link' => 'task3.php'],
        ['title' => 'Задание 6', 'link' => 'task6.php']
];

function renderMenu($items, $isSub = false)
{
    $html = $isSub ? '<ul class="submenu">' : '<ul class="main-menu">';
    foreach ($items as $item) {
        $html .= '<li>';
        $html .= '<a href="' . htmlspecialchars($item['link']) . '">' . htmlspecialchars($item['title']) . '</a>';
        if (isset($item['sub']) && is_array($item['sub'])) {
            $html .= renderMenu($item['sub'], true);
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Практика 17</title>
    <link rel="stylesheet" href="src/styles/styles.css">
</head>
<body>
<h1>Практика 18 (´• ω •`) </h1>
<?= renderMenu($menuItems); ?>
</body>
</html>