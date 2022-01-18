<?php

require_once 'helpers.php';
require_once 'function.php';
require_once 'init.php';
require_once 'models.php';

$lot_id = filter_input(INPUT_GET, 'id');
$menu = true;

if (!$lot_id) {
    $page_content = include_template(
        '404.php'
    );
} else {
    $lots = get_ads($link);
    $lot_in_lots = in_array($lot_id, array_column($lots, 'id'));
    if (!$lot_in_lots) {
        $page_content = include_template(
            '404.php'
        );
    } else {
        $lot = get_lot($link, $lot_id);
        $now_price = ($lot['max_bet']) ? $lot['max_bet'] : $lot['start_price'];
        $min_price = $lot['step_bet'] + $now_price;
        $bets = get_bets($link, $lot_id);
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user']['id'];
        }

        $created_by_current_user = ($user_id ?? 0) == $lot['user_id'];

        $last_bet_by_current_user = ($user_id ?? 0) == ($bets[0]['user_id'] ?? 0);

        $show_bet_form = ($user_id ?? 0) && !$created_by_current_user && !$last_bet_by_current_user;

        $page_content = include_template(
            'lot.php',
            [
                'lot' => $lot,
                'now_price' => $now_price,
                'min_price' => $min_price,
                'bets' => $bets,
                'show_bet_form' => $show_bet_form,
            ]
        );

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user'])) {
                header('Location: lot.php?id=' . $lot_id);
                die();
            }

            $form_add_bet = $_POST;
            $required_fields = [
                'cost'
            ];

            $rules = [
                'cost' => function ($value) use ($min_price) {
                    return validate_bet_add($value, $min_price);
                }
            ];

            $errors = form_validation($form_add_bet, $rules, $required_fields);


            if (!$errors) {
                $sql = 'INSERT INTO bets (date, price, user_id, ad_id) VALUES (NOW(), ?, ?, ?)';
                $stmt = db_get_prepare_stmt(
                    $link,
                    $sql,
                    $data = [$form_add_bet['cost'], $_SESSION['user']['id'], $lot_id]
                );
                $res = mysqli_stmt_execute($stmt);

                if ($res) {
                    header('Location: lot.php?id=' . $lot_id);
                    die();
                }
            }
        }

        $page_content = include_template(
            'lot.php',
            [
                'lot' => $lot,
                'now_price' => $now_price,
                'errors' => $errors,
                'min_price' => $min_price,
                'bets' => $bets,
                'show_bet_form' => $show_bet_form,

            ]
        );
    }
}


$layout_content = include_template(
    'layout.php',
    [
        'content' => $page_content,
        'menu' => $menu,
        'categories' => $categories,
        'title' => 'Страница лота',
    ]
);

print($layout_content);
