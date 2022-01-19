<?php

require_once 'helpers.php';
require_once 'function.php';
require_once 'init.php';
require_once 'models.php';


if (!$link) {
    $error = mysqli_connect_error($link);
    $content = include_template('error.php', ['error' => $error]);
} else {
    $categories = get_catigories($link);
    $ads = get_ads($link);
}

$search_get = filter_input(INPUT_GET, 'search');

if ($search_get) {
    $search = trim($search_get);
    $cur_page = filter_input(INPUT_GET, 'page') ?? 1;
    $page_items = 2;
    $items_count = get_count_ads($link, $search);
    $pages_count = ceil($items_count['cnt'] / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);
    $ads = get_ads_search($link, $search, $page_items, $offset);
}


$page_content = include_template(
    'search.php',
    [
        'categories' => $categories,
        'ads' => $ads,
        'search' => $search,
        'pages_count' => $pages_count,
        'pages' => $pages,
        'cur_page' => $cur_page
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Поиск',
        'user_name' => $user_name,
        'is_auth' => $is_auth
    ]
);

print($layout_content);
