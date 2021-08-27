<?php

require_once 'helpers.php';
require_once 'function.php';
require_once 'data.php';
require_once 'init.php';
require_once 'models.php';

$menu = true;

$category_get = filter_input(INPUT_GET, 'category_name');

if (!$category_get) {
    $page_content = include_template(
        '404.php'
    );
} else {
    $category_id = get_id_from_name($categories, $category_get);


    $category = trim($category_get);
    $cur_page = filter_input(INPUT_GET, 'page') ?? 1;
    $page_items = 6;
    $items_count = get_count_ads_category($link, $category_id);
    $pages_count = ceil($items_count['cnt'] / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);
    $ads = get_ads_category($link, $category_id, $page_items, $offset);
}

$page_content = include_template(
    'all_lots.php',
    [
        'ads' => $ads,
        'category_id' => $category_id,
        'category' => $category,
        'categories' => $categories,
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
        'title' => 'Лоты категории',
        'menu' => $menu,
    ]
);

print($layout_content);
