<?php

require_once 'helpers.php';
require_once 'function.php';
require_once 'init.php';
require_once 'models.php';
require_once 'getwinner.php';

$ads = get_ads($link);

$page_content = include_template(
    'main.php',
    [
        'categories' => $categories,
        'ads' => $ads
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'content' => $page_content,
        'title' => 'Главная',
        'categories' => $categories,
    ]
);

print($layout_content);
