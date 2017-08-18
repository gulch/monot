<?php

require __DIR__ . '/vendor/autoload.php';

// setup logger
$logger = new \Monolog\Logger('MonotApp', [
    /*new \Gulch\MonologTelegram\TelegramHandler($token, $chat_id),*/
    new \Monolog\Handler\StreamHandler(__DIR__ . '/storage/logs/' . date('Ymd') . '.log'),
]);

// register exceptions handler
\Monolog\ErrorHandler::register($logger);

/* Register Dotenv */
$dotenv = new \Dotenv\Dotenv(__DIR__);
$dotenv->load();

$token = getenv('TELEGRAM_BOT_TOKEN');
$chat_id = getenv('TELEGRAM_CHAT_ID');
$notifier = new \Monot\Notification\TelegramNotification($token, $chat_id);

// check availability
(new \Monot\Point\HttpsServer($notifier, 'gulchuk.com'))->check();