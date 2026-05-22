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

if (!isset($_GET['id'])) {
    die("Товар не указан.");
}

$product_id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die("Товар не найден.");
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

        case 'delete':
            if (empty($data['review_id'])) return ['success' => false];
            $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
            $success = $stmt->execute([$data['review_id']]);
            return ['success' => $success];

        default:
            return ['success' => false, 'error' => 'Неизвестное действие'];
    }
}

$form_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_review'])) {
    $review_data = [
        'product_id' => $product_id,
        'author' => trim($_POST['author']),
        'rating' => (int)$_POST['rating'],
        'text' => trim($_POST['text'])
    ];

    $result = doFeedbackAction($pdo, 'create', $review_data);

    if (isset($result['success']) && $result['success']) {
        header("Location: product.php?id=" . $product_id);
        exit;
    } else {
        $form_error = $result['error'] ?? 'Ошибка добавления отзыва';
    }
}

if (isset($_GET['delete_review_id'])) {
    $delete_id = (int)$_GET['delete_review_id'];
    doFeedbackAction($pdo, 'delete', ['review_id' => $delete_id]);
    header("Location: product.php?id=" . $product_id);
    exit;
}

$reviews = doFeedbackAction($pdo, 'read', ['product_id' => $product_id]);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="product-page-container">

    <a href="index.php" class="btn-back">← Назад в каталог</a>

    <section class="product-detail">
        <div class="product-detail-image">
            <img src="assets/images/products/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='assets/images/products/default.png'">
        </div>
        <div class="product-detail-info">
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <p class="detail-price"><?= number_format($product['price'], 2, '.', ' ') ?> руб.</p>
            <div class="detail-description">
                <h3>Описание товара:</h3>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <section class="reviews-section">
        <h2>Отзывы покупателей (<?= count($reviews) ?>)</h2>

        <div class="review-form-block">
            <h3>Оставить отзыв</h3>
            <?php if ($form_error): ?>
                <p class="error-msg"><?= $form_error ?></p>
            <?php endif; ?>

            <form action="" method="POST" class="review-form">
                <div class="form-group">
                    <label for="author">Ваше имя:</label>
                    <input type="text" id="author" name="author" required>
                </div>

                <div class="form-group">
                    <label for="rating">Оценка:</label>
                    <select id="rating" name="rating" required>
                        <option value="5">5 ★★★★★</option>
                        <option value="4">4 ★★★★</option>
                        <option value="3">3 ★★★</option>
                        <option value="2">2 ★★</option>
                        <option value="1">1 ★</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="text">Текст отзыва:</label>
                    <textarea id="text" name="text" rows="4" required></textarea>
                </div>

                <button type="submit" name="add_review" class="btn-submit">Отправить отзыв</button>
            </form>
        </div>

        <div class="reviews-list">
            <?php if (empty($reviews)): ?>
                <p class="no-reviews">У этого товара пока нет отзывов. Будьте первым!</p>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <span class="review-author"><?= htmlspecialchars($review['author']) ?></span>
                            <span class="review-rating"><?= str_repeat('★', $review['rating']) ?><?= str_repeat('☆', 5 - $review['rating']) ?></span>
                            <span class="review-date"><?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></span>
                        </div>
                        <p class="review-text"><?= nl2br(htmlspecialchars($review['text'])) ?></p>
                        <a href="product.php?id=<?= $product_id ?>&delete_review_id=<?= $review['id'] ?>" class="btn-delete-review" onclick="return confirm('Удалить этот отзыв?')">Удалить</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

</div>
</body>
</html>