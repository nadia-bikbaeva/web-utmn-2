<?php
$host = 'MySQL-8.4';
$port = '3306';
$db = 'menu_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}

$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();
$tree = [];
foreach ($categories as $cat) {
    $tree[$cat['parent_id']][] = $cat;
}

function renderMenu($parentId, $tree): string
{
    if (!isset($tree[$parentId])) {
        return '';
    }
    $html = '<ul class="menu-list">';
    foreach ($tree[$parentId] as $node) {
        $hasChildren = isset($tree[$node['id']]);
        $liClass = $hasChildren ? 'menu-item has-children' : 'menu-item';
        $html .= '<li class="' . $liClass . '">';
        $html .= '<div class="menu-link">';
        if ($hasChildren) {
            $html .= '<img src = "img/chevron-down.png" class = "arrow" alt=">">';
        } else {
            $html .= '<span class="arrow-spacer"></span>';
        }
        $html .= '<img src = "img/folder.png" class = "folder-icon" alt="dir">';
        $html .= '<span class="menu-text">' . htmlspecialchars($node['name']) . '</span>';
        $html .= '</div>';
        if ($hasChildren) {
            $html .= '<div class="submenu">' . renderMenu($node['id'], $tree) . '</div>';
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
    <title>Древовидное меню</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="menu-container">
    <?php echo renderMenu(null, $tree); ?>
</div>

<script src="script.js"></script>
</body>
</html>

