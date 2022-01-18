<?php

require_once 'helpers.php';
require_once 'function.php';
require_once 'init.php';
require_once 'models.php';

$menu = true;

if (isset($_SESSION['user'])) {
    $message = 'Вы уже зарегистрированы и вошли на сайт.';
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
            'title' => 'Регистрация',
            'menu' => $menu,
        ]
    );

    print($layout_content);
    die();
}


$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_sign_up = $_POST;

    $required_fields = [
        'email',
        'password',
        'name',
        'message'
    ];

    $rules = [
        'email' => function ($value) use ($link) {
            return validate_email($value, $link);
        }
    ];

    $errors = form_validation($form_sign_up, $rules, $required_fields);

    if (!$errors) {
        $password = password_hash($form_sign_up['password'], PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (date_registration, email, password, name, contacts) VALUES (NOW(), ?, ?, ?, ?)';

        $stmt = db_get_prepare_stmt(
            $link,
            $sql,
            [$form_sign_up['email'], $password, $form_sign_up['name'], $form_sign_up['message']]
        );
        $res = mysqli_stmt_execute($stmt);

        header("Location: /login.php");
        die();
    }
}


$page_content = include_template(
    'sign_up.php',
    [
        'errors' => $errors
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Регистрация',
        'menu' => $menu,
    ]
);

print($layout_content);
