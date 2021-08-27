<?php

require_once 'helpers.php';
require_once 'function.php';
require_once 'data.php';

require_once 'init.php';
require_once 'models.php';

$menu = true;

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_login = $_POST;

    $required_fields = [
        'email',
        'password'
    ];

    $rules = [
        'email' => function ($value) {
            return validate_email_login($value);
        }
    ];

    $errors = form_validation($form_login, $rules, $required_fields);

    if (!$errors) {
        $sql = 'SELECT id, email, name, password
            FROM users
            WHERE email = ?
            LIMIT 1';

        $stmt = db_get_prepare_stmt($link, $sql, [$form_login['email']]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);

        if ($user) {
            if (password_verify($form_login['password'], $user['password'])) {
                $_SESSION['user'] = $user;
                header("Location: /index.php");
                die();
            } else {
                $errors['password'] = 'Неверный пароль';
            }
        } else {
            $errors['email'] = 'Такой пользователь не найден';
        }
    }
}


$page_content = include_template(
    'login.php',
    [
        'errors' => $errors
    ]
);

$layout_content = include_template(
    'layout.php',
    [
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Вход на сайт',
        'menu' => $menu,
    ]
);

print($layout_content);
