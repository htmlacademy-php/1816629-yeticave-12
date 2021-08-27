<?php

require_once 'helpers.php';
require_once 'function.php';
require_once 'data.php';

require_once 'init.php';
require_once 'models.php';

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

$user_id = $_SESSION['user']['id'];
$bets = get_my_bets($link, $user_id);


$page_content = include_template(
    'my_bets.php',
    [
        'bets' => $bets,
        'categories' => $categories,
        'user_id' => $user_id
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Мои ставки',
        'menu' => $menu,
    ]
);

print($layout_content);
