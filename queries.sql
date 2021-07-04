-- Добавляем пользователей в таблицу user
INSERT INTO users SET date_registration = NOW(), email = 'masha@mail.ru', name = 'Маша', password = 'qwerty', contacts = '+79211234567';
INSERT INTO users SET date_registration = NOW(), email = 'vanya@mail.ru', name = 'Ваня', password = 'ytrewq', contacts = '+79111234567';
INSERT INTO users SET date_registration = NOW(), email = 'roma@mail.ru', name = 'Рома', password = 'zxcvb', contacts = '+79111234123';
INSERT INTO users SET date_registration = NOW(), email = 'vika@mail.ru', name = 'Вика', password = 'asdfg', contacts = '+79091234123';


-- Добавляем список категорий в таблицу categories
INSERT INTO categories SET code = 'boards', name = 'Доски и лыжи';
INSERT INTO categories SET code = 'attachment', name = 'Крепления';
INSERT INTO categories SET code = 'boots', name = 'Ботинки';
INSERT INTO categories SET code = 'clothing', name = 'Одежда';
INSERT INTO categories SET code = 'tools', name = 'Инструменты';
INSERT INTO categories SET code = 'other', name = 'Разное';


-- Добавляем список объявлений в таблицу ads
INSERT INTO ads SET name = '2014 Rossignol District Snowboard', description = 'Отличное состояние', img = 'img/lot-1.jpg', start_price = '10999', date_create = '2021-06-10', date_end = '2021-06-19', step_bet = '100', category_id = '1', user_id = '1';
INSERT INTO ads SET name = 'DC Ply Mens 2016/2017 Snowboard', description = 'Отличное хорошее', img = 'img/lot-2.jpg', start_price = '159999', date_create = '2021-06-11', date_end = '2021-06-20', step_bet = '100', category_id = '1', user_id = '1';
INSERT INTO ads SET name = 'Крепления Union Contact Pro 2015 года размер L/XL', description = 'Поцарапаны', img = 'img/lot-3.jpg', start_price = '8000', date_create = '2021-05-11', date_end = '2021-10-13', step_bet = '50', category_id = '2', user_id = '2';
INSERT INTO ads SET name = 'Ботинки для сноуборда DC Mutiny Charocal', description = 'Очень теплые', img = 'img/lot-4.jpg', start_price = '10999', date_create = '2021-06-14', date_end = '2021-11-11', step_bet = '200', category_id = '3', user_id = '2';
INSERT INTO ads SET name = 'Куртка для сноуборда DC Mutiny Charocal', description = 'Мембрана супер', img = 'img/lot-5.jpg', start_price = '7500', date_create = '2021-06-18', date_end = '2021-10-21', step_bet = '150', category_id = '4', user_id = '3';
INSERT INTO ads SET name = 'Маска Oakley Canopy', description = 'На узкое лицо', img = 'img/lot-6.jpg', start_price = '5400', date_create = '2021-06-28', date_end = '2021-07-24', step_bet = '100', category_id = '5', user_id = '4';


-- Добавляем ставки
INSERT INTO bets SET date = NOW(), price = '11099', user_id = '4', ad_id = '1';
INSERT INTO bets SET date = NOW(), price = '11199', user_id = '3', ad_id = '1';
INSERT INTO bets SET date = NOW(), price = '5500', user_id = '1', ad_id = '6';


-- Получаем все категории
SELECT * FROM categories;

-- Получаем самые новые, открытые лоты. Каждый лот включает название, стартовую цену, ссылку на изображение, текущую цену, название категории;
SELECT a.id, a.name, a.start_price, a.img, c.name, IFNULL(MAX(b.price), a.start_price) AS price
FROM ads AS a
         INNER JOIN categories AS c ON c.id = a.category_id
         LEFT JOIN bets AS b ON b.ad_id = a.id
WHERE a.date_end > NOW()
GROUP BY a.id;


-- Показываем лот по его id. Получаем название категории, к которой принадлежит лот
SELECT a.*, c.name AS categories
FROM ads AS a
         JOIN categories AS c ON c.id = a.category_id
WHERE a.id = 2;

-- Получаем список ставок для лота по его идентификатору с сортировкой по дате
SELECT *
FROM bets
WHERE ad_id = 1
ORDER BY date;
