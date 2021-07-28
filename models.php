<?php

/**
 * Функция получения из БД массива категорий
 * @param $link подключение к БД
 * @return array массив категорий
 */
function get_catigories ($link) {
    $sql_catigories = 'SELECT id, `name`, `code` FROM categories';
    $stmt = db_get_prepare_stmt($link, $sql_catigories);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
};


/**
 * Функция получения из БД массива актуальных объявлений
 * @param $link подключение к БД
 * @return array массив актуальных объявлений
 */
function get_ads ($link) {
    $sql_ads = 'SELECT a.id, a.name, a.start_price, a.img, a.date_end, a.category_id, a.start_price
FROM ads AS a
        WHERE a.date_end > NOW()
        GROUP BY a.id; ';

    $stmt = db_get_prepare_stmt($link, $sql_ads);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function get_lot ($link, $lot_id) {
    $sql_lot = 'SELECT a.*, c.name AS categories, IFNULL((SELECT price as max_bet
FROM bets
WHERE ad_id = a.id
ORDER BY price DESC
    LIMIT 1
    ), a.start_price) AS price
FROM ads AS a
         INNER JOIN categories AS c ON c.id = a.category_id
WHERE a.id = ?';

    $stmt = db_get_prepare_stmt($link, $sql_lot, $data = [$lot_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}

