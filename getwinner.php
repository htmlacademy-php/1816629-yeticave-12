<?php
require_once 'vendor/autoload.php';
require_once 'models.php';
require_once 'config/email.php';

$lots_without_winner = get_winners($link);


if ($lots_without_winner) {
    foreach ($lots_without_winner as $lot) {
        update_winner($link, $lot['last_bet_user'], $lot['id']);
        $transport = new Swift_SmtpTransport($email['smtp_host'], $email['smtp_port']);
        $transport->setUsername($email['username']);
        $transport->setPassword($email['password']);

        $winner = get_user_by_id($link, $lot['last_bet_user']);

        $mailer = new Swift_Mailer($transport);

        $message = new Swift_Message();
        $message->setSubject("Ваша ставка победила");
        $message->setFrom(['keks@phpdemo.ru' => 'Yeticave']);
        $message->setTo([$winner['email']]);

        $msg_content = include_template('email.php', ['winner' => $winner, 'lot' => $lot]);
        $message->setBody($msg_content, 'text/html');

        $result = $mailer->send($message);
    }
}
