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

$categories_by_id = [];
foreach ($categories as $cat) {
    $tree[$cat['parent_id']][] = $cat;
    $categories_by_id[$cat['id']] = $cat;
}
function getChildCategoryIds($parentId, $tree): array {
    $ids = [];
    if (isset($tree[$parentId])) {
        foreach ($tree[$parentId] as $child) {
            $ids[] = $child['id'];
            $ids = array_merge($ids, getChildCategoryIds($child['id'], $tree));
        }
    }
    return $ids;
}

$products = [];

if (isset($_GET['category_id'])) {
    $category_id = (int)$_GET['category_id'];

    if (isset($categories_by_id[$category_id])) {
        $current_category_name = $categories_by_id[$category_id]['name'];
        $allowed_ids = array_merge([$category_id], getChildCategoryIds($category_id, $tree));
        $in_clause = implode(',', array_fill(0, count($allowed_ids), '?'));
        $prod_stmt = $pdo->prepare("SELECT * FROM products WHERE category_id IN ($in_clause)");
        $prod_stmt->execute($allowed_ids);
        $products = $prod_stmt->fetchAll();
    } else {
        $current_category_name = "Каталог товаров";
    }
} else {
    $current_category_name = "Все товары";
    $prod_stmt = $pdo->query("SELECT * FROM products");
    $products = $prod_stmt->fetchAll();
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
            $html .= '<img src="assets/images/chevron-down.png" class="arrow" alt=">">';
        } else {
            $html .= '<span class="arrow-spacer"></span>';
        }

        $html .= '<img src="assets/images/folder.png" class="folder-icon" alt="dir">';

        $html .= "<a href='index.php?category_id=" . $node['id'] . "' class='menu-link-item'>";
        $html .= "<span class='menu-text'>" . htmlspecialchars($node['name']) . "</span>";
        $html .= "</a>";

        $html .= '</div>';

        if ($hasChildren) {
            $html .= '<div class="submenu">' . renderMenu($node['id'], $tree) . '</div>';
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

function doFeedbackAction(PDO $pdo, string $action, array $data) {
    switch ($action) {
        case 'create':
            if (empty($data['product_id']) || empty($data['author']) || empty($data['text']) || empty($data['rating'])) {
                return ['success' => false, 'error' => 'Заполните все поля'];
            }
            $stmt = $pdo->prepare("INSERT INTO reviews (product_id, author, rating, text) VALUES (?, ?, ?, ?)");
            $success = $stmt->execute([$data['product_id'], $data['author'], $data['rating'], $data['text']]);
            return ['success' => $success, 'id' => $pdo->lastInsertId()];

        case 'read':
            if (empty($data['product_id'])) return [];
            $stmt = $pdo->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC");
            $stmt->execute([$data['product_id']]);
            return $stmt->fetchAll();

        default:
            return ['success' => false, 'error' => 'Неизвестное действие'];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог товаров</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="main-container">
    <aside class="sidebar">
        <h2>Категории</h2>
        <div class="menu-container">
            <?php echo renderMenu(null, $tree); ?>
        </div>
    </aside>

    <main class="content">
        <h1><?= htmlspecialchars($current_category_name) ?></h1>

        <?php if (empty($products)): ?>
            <p class="empty-message">В этой категории пока нет товаров.</p>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='assets/images/products/default.png'">
                        </div>
                        <div class="product-info">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="price"><?= number_format($product['price'], 2, '.', ' ') ?> руб.</p>
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn-details">Подробнее</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<div class="pashalk-container" title="Хранитель каталога">
    <img src="assets/images/pashalka.png" alt="Пасхалка">
</div>

<script src="script.js"></script>
</body>
</html>