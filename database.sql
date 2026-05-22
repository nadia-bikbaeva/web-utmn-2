CREATE TABLE categories (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            parent_id INT DEFAULT NULL,
                            name VARCHAR(255) NOT NULL,
                            FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
);

INSERT INTO categories (id, parent_id, name) VALUES
                                                 (1, NULL, 'Каталог товаров'),
                                                 (2, 1, 'Мойки'),
                                                 (3, 2, 'Ulgran'),
                                                 (4, 3, 'Smth'),
                                                 (5, 3, 'Smth'),
                                                 (6, 2, 'Vigro Mramor'),
                                                 (7, 2, 'Handmade'),
                                                 (8, 7, 'Smth'),
                                                 (9, 7, 'Smth'),
                                                 (10, 2, 'Vigro Glass'),
                                                 (11, 1, 'Фильтры'),
                                                 (12, 11, 'Ulgran'),
                                                 (13, 12, 'Smth'),
                                                 (14, 12, 'Smth'),
                                                 (15, 11, 'Vigro Mramor');


--для 21 лаб работы были выполнены следующие запросы:
-- создание таблицы товаров:
CREATE TABLE IF NOT EXISTS `products` (
                                          `id` INT AUTO_INCREMENT PRIMARY KEY,
                                          `category_id` INT NOT NULL,
                                          `name` VARCHAR(255) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `description` TEXT,
    `image` VARCHAR(255) DEFAULT 'default-product.png',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- создание таблицы отзывов
CREATE TABLE IF NOT EXISTS `reviews` (
                                         `id` INT AUTO_INCREMENT PRIMARY KEY,
                                         `product_id` INT NOT NULL,
                                         `author` VARCHAR(100) NOT NULL,
    `rating` INT NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
    `text` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- привязка категорий к товарам и добавление их в таблицу
INSERT INTO `products` (`category_id`, `name`, `price`, `description`, `image`) VALUES
(3, 'Мойка Ulgran U-100', 6500.00, 'Односекционная кухонная мойка из искусственного мрамора. Глубокая чаша, устойчивое к царапинам покрытие.', 'ulgran_u100.png'),
(3, 'Мойка Ulgran U-400', 5800.00, 'Компактная мойка, идеально подходит для небольших кухонь. Легко очищается, поглощает шум воды.', 'ulgran_u400.png'),
(6, 'Мойка Vigro M-05', 8200.00, 'Премиальная мойка с крылом для сушки посуды. Выполнена из прочного мраморного композита.', 'vigro_m05.png'),
(7, 'Стальная мойка Handmade 5050', 12500.00, 'Мойка ручной работы из нержавеющей стали толщиной 3 мм. Стильный матовый дизайн, прямоугольная форма.', 'handmade_5050.png'),
(7, 'Мойка Handmade Black PVD', 14200.00, 'Эксклюзивная черная мойка из нержавеющей стали со специальным PVD-покрытием против грязи и разводов.', 'handmade_black.png'),
(10, 'Стеклянная мойка Vigro G-11', 18900.00, 'Дизайнерская мойка: чаша из нержавеющей стали, верхняя панель из закаленного сверхпрочного стекла черного цвета.', 'vigro_g11.png'),
(12, 'Фильтр для воды Ulgran Bio', 4500.00, 'Трехступенчатая система очистки воды под мойку. Удаляет хлор, тяжелые металлы и бактерии.', 'filter_ulgran_bio.png'),
(12, 'Сменный картридж Ulgran Комплект', 1800.00, 'Набор из трех фильтроэлементов для комплексной очистки и умягчения водопроводной воды.', 'cartridge_set.png');