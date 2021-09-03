<?php

/**
 * Функция получения из БД массива категорий
 * @param $link подключение к БД
 * @return array массив категорий
 */
function get_catigories($link)
{
    $sql_catigories = 'SELECT id, name, code FROM categories';
    $stmt = db_get_prepare_stmt($link, $sql_catigories);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

;


/**
 * Функция получения из БД массива актуальных объявлений
 * @param $link подключение к БД
 * @return array массив актуальных объявлений
 */
function get_ads($link)
{
    $sql_ads = 'SELECT a.id, a.name, a.img, a.date_end, a.category_id, a.start_price
FROM ads AS a
        WHERE a.date_end > NOW()
        GROUP BY a.id; ';

    $stmt = db_get_prepare_stmt($link, $sql_ads);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Функция получения из БД массива актуальных объявлений
 * @param $link подключение к БД
 * @return array массив актуальных объявлений
 */
function get_ads_category($link, $category_id, $page_items, $offset)
{
    $sql_ads = 'SELECT a.id, a.name, a.start_price, a.img, a.date_end, a.start_price
FROM ads AS a
WHERE a.date_end > NOW()
AND a.category_id = ?
        GROUP BY a.id
        LIMIT ?
        OFFSET ?';

    $stmt = db_get_prepare_stmt($link, $sql_ads, $data = [$category_id, $page_items, $offset]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Функция получения из БД массива актуальных объявлений для поиска
 * @param $link подключение к БД
 * @return array массив актуальных объявлений
 */
function get_ads_search($link, $search, $page_items, $offset)
{
    $sql_ads = 'SELECT a.id, a.name, a.start_price, a.img, a.date_end, a.category_id, a.start_price
FROM ads AS a
WHERE MATCH(name, description) AGAINST(?)
        GROUP BY a.id
        LIMIT ?
        OFFSET ?';

    $stmt = db_get_prepare_stmt($link, $sql_ads, $data = [$search, $page_items, $offset]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}


function get_lot($link, $lot_id)
{
    $sql_lot = 'SELECT a.*, c.name AS categories, (SELECT price
                                   FROM bets
                                   WHERE ad_id = a.id
                                   ORDER BY price DESC
                                   LIMIT 1) as max_bet
FROM ads AS a
         INNER JOIN categories AS c ON c.id = a.category_id
WHERE a.id = ?';

    $stmt = db_get_prepare_stmt($link, $sql_lot, $data = [$lot_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}


function get_count_ads($link, $search)
{
    $sql = 'SELECT COUNT(*) as cnt FROM ads

WHERE MATCH(name, description) AGAINST(?)';

    $stmt = db_get_prepare_stmt($link, $sql, $data = [$search]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}


function get_count_ads_category($link, $category_id)
{
    $sql = 'SELECT COUNT(*) as cnt FROM ads AS a
WHERE a.date_end > NOW()
AND a.category_id = ?';

    $stmt = db_get_prepare_stmt($link, $sql, $data = [$category_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res);
}

function get_bets($link, $ad_id)
{
    $sql = 'SELECT
    b.date,
    b.price,
    b.user_id,
        (SELECT u.name
                                   FROM users AS U
                                   WHERE id = b.user_id
) as user
FROM bets AS b
WHERE ad_id = ?';

    $stmt = db_get_prepare_stmt($link, $sql, $data = [$ad_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function get_my_bets($link, $user_id)
{
    $sql = 'SELECT DISTINCT
    a.id,
    a.name,
    a.img,
    u.contacts,
    a.category_id,
    a.date_end,
    b.user_id,
    a.winner_id,
    (SELECT MAX(b.price) FROM bets b WHERE b.ad_id = a.id) max_price,
    (SELECT MAX(b.date) FROM bets b WHERE b.ad_id = a.id) latest_date
FROM ads a
         JOIN bets b ON a.id = b.ad_id
         JOIN users u ON a.user_id = u.id
WHERE b.user_id = ?
ORDER BY latest_date DESC';

    $stmt = db_get_prepare_stmt($link, $sql, $data = [$user_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}
