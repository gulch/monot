<?php

require __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);

/* Register Dotenv */
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$token = getenv('TELEGRAM_BOT_TOKEN');
$chat_id = getenv('TELEGRAM_CHAT_ID');

// setup logger
$logger = new \Monolog\Logger('MonotBot', [
    new \Gulch\MonologTelegram\TelegramHandler($token, $chat_id),
    new \Monolog\Handler\StreamHandler(__DIR__ . '/storage/logs/' . date('Ymd') . '.log'),
]);

// setup exceptions handler
$runner = new \League\BooBoo\Runner(
    [
        new \League\BooBoo\Formatter\NullFormatter()
    ],
    [
        new \Monot\Handler\MonotLogHandler($logger)
    ]
);
$runner->register();

// check availability
(new \Monot\Point\HttpsServer('gulchuk.com', 2))->check();

echo date('d.m.Y H:i:s') . ': check passed';