<?php

require_once 'helpers.php';
require_once 'init.php';
require_once 'models.php';

$ads = get_ads($link);
$menu = true;

if (!isset($_SESSION['user'])) {
    $message = 'Необходимо <a href="/login.php">войти</a>.';
    $page_content = include_template(
        '403.php',
        [
            'message' => $message,
        ]
    );
    $layout_content = include_template(
        'layout.php',
        [
            'content' => $page_content,
            'categories' => $categories,
            'title' => 'Добавление лота',
            'menu' => $menu,
        ]
    );

    print($layout_content);
    die();
}


$page_content = include_template(
    'add-lot.php',
    [
        'categories' => $categories,
    ]
);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cats_ids = array_column($categories, 'id');

    $ad = $_POST;
    $adf = $_FILES;

    $ad = [
        'lot-name' => $_POST['lot-name'],
        'category' => $_POST['category'],
        'message' => $_POST['message'],
        'lot-rate' => is_numeric($_POST['lot-rate']) ? floatval($_POST['lot-rate']) : $_POST['lot-rate'],
        'lot-step' => is_numeric($_POST['lot-step']) ? $_POST['lot-step'] + 0 : $_POST['lot-step'],
        'lot-date' => $_POST['lot-date'],
        'lot-img' => $_FILES['lot-img'],
        'user-id' => $_SESSION['user']['id']
    ];

    $required_fields = [
        'lot-name',
        'category',
        'message',
        'lot-rate',
        'lot-step',
        'lot-date',
        'lot-img',
    ];
    $rules = [
        'lot-rate' => function ($value) {
            return validate_price($value);
        },
        'lot-date' => function ($value) {
            return validate_current_date($value);
        },
        'lot-step' => function ($value) {
            return validate_step_rate($value);
        },
        'category' => function ($value) use ($cats_ids) {
            return validate_category_id($value, $cats_ids);
        },
        'lot-img' => function ($value) {
            return validate_file($value);
        },
    ];

    $errors = form_validation($ad, $rules, $required_fields);

    if (count($errors) <= 0) {
        $file_name = $_FILES['lot-img']['name'];
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;
        move_uploaded_file($tmp_name, $file_path . $file_name);
        $ad['lot-img'] = $file_url;


        $sql = 'INSERT INTO ads (name, category_id, description, start_price, step_bet, date_end, img, user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt($link, $sql, $ad);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);

            header("Location: lot.php?id=" . $lot_id);
            die();
        }
    }

    $page_content = include_template(
        'add-lot.php',
        [
            'errors' => $errors,
            'categories' => $categories,
        ]
    );
}

$layout_content = include_template(
    'layout.php',
    [
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Добавление лота',
        'menu' => $menu,
    ]
);

print($layout_content);
