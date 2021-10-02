<?php
require_once'vendor/autoload.php';
require_once 'models.php';

$lotsWithoutWinner = get_winners($link);

if ($lotsWithoutWinner) {

    foreach ($lotsWithoutWinner as $lot) {
        updateWinner($link, $lot['id'], $lot['last_bet_user']);
    }

    // Конфигурация траспорта
    $transport = new Swift_SmtpTransport('smtp.example.org', 25);

// Формирование сообщения
    $message = new Swift_Message("Просмотры вашей гифки");
    $message->setTo(["keks@htmlacademy.ru" => "Кекс"]);
    $message->setBody("Вашу гифку «Кот и пылесос» посмотрело больше 1 млн!");
    $message->setFrom("mail@giftube.academy", "GifTube");

// Отправка сообщения
    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);
}
