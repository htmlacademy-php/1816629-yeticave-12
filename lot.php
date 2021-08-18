<?php
require_once 'helpers.php';
require_once 'function.php';
require_once 'data.php';

require_once 'init.php';
require_once 'models.php';


if (!$link) {
    $error = mysqli_connect_error($link);
    $content = include_template('error.php', ['error' => $error]);
}
else {
    $lot_id = filter_input(INPUT_GET, 'id');
    $categories = get_catigories($link);
    if(!$lot_id) {
        $menu = include_template('menu.php', [
            'categories' => $categories]);

        $page_content = include_template('404.php', [
            'menu' => $menu]);
    } else {
        $lots = get_ads($link);
        $lot_in_lots = in_array($lot_id, array_column($lots, 'id'));
        if (!$lot_in_lots) {
            $menu = include_template('menu.php', [
                'categories' => $categories]);
            $page_content = include_template('404.php', [
                'menu' => $menu]);
        } else {
            $lot = get_lot($link, $lot_id);
            $now_price = ($lot['max_bet']) ? $lot['max_bet'] : $lot['start_price'];
            $min_price = $lot['step_bet'] + $now_price;
            $menu = include_template('menu.php', [
                'categories' => $categories]);
            $page_content = include_template('lot.php', [
                'menu' => $menu,
                'lot' => $lot,
                'now_price' => $now_price,
                'min_price' => $min_price

            ]);

            $errors = [];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                if (!isset($_SESSION['user'])) {
                    header("Location: lot.php?id=" . $lot_id);
                    die();
                }

                $form_add_bet = $_POST;
                $required_fields = [
                    'cost'
                ];

                $rules = [
                    'cost' => function  ($value) use ($min_price) {
                        return validate_bet_add($value, $min_price);
                    }
                ];

                $errors = form_validation($form_add_bet, $rules, $required_fields);


                if (!$errors) {
                    $sql = 'INSERT INTO bets (date, price, user_id, ad_id) VALUES (NOW(), ?, ?, ?)';
                    $stmt = db_get_prepare_stmt($link, $sql, $data = [$form_add_bet['cost'], $_SESSION['user']['id'], $lot_id]);
                    $res = mysqli_stmt_execute($stmt);

                    if ($res) {
                        header("Location: lot.php?id=" . $lot_id);
                        die();
                    }
                }
            }
        }
    }
}

$page_content = include_template('lot.php', [
    'menu' => $menu,
    'lot' => $lot,
    'now_price' => $now_price,
    'errors' => $errors,
    'min_price' => $min_price

]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Страница лота',
    'user_name' => $user_name,
    'is_auth' => $is_auth
]);

print($layout_content);
