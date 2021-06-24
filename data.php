<?php

$is_auth = rand(0, 1);
$user_name = 'Настя';
$categories = [
    'boards' => 'Доски и лыжи',
    'binding' => 'Крепления',
    'boots' => 'Ботинки',
    'clothes' => 'Одежда',
    'tools' => 'Инструменты',
    'other' => 'Разное'
];
$ads = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'category' => $categories['boards'],
        'price' => 10999,
        'img' => 'img/lot-1.jpg',
        'date_end' => '2021-06-19'
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => $categories['boards'],
        'price' => 159999,
        'img' => 'img/lot-2.jpg',
        'date_end' => '2021-10-13'
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => $categories['binding'],
        'price' => 8000,
        'img' => 'img/lot-3.jpg',
        'date_end' => '2021-09-11'
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => $categories['boots'],
        'price' => 10999,
        'img' => 'img/lot-4.jpg',
        'date_end' => '2021-11-11'
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => $categories['clothes'],
        'price' => 7500,
        'img' => 'img/lot-5.jpg',
        'date_end' => '2021-10-21'
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'category' => $categories['other'],
        'price' => 5400,
        'img' => 'img/lot-6.jpg',
        'date_end' => '2021-06-24'
    ]
];
