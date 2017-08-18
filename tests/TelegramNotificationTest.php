<?php
declare(strict_types=1);

namespace Monot\Tests;

use PHPUnit\Framework\TestCase;
use Monot\Notification\TelegramNotification;

class TelegramNotificationTest extends TestCase
{
    public function setUp()
    {
        /* Register Dotenv */
        (new \Dotenv\Dotenv(__DIR__ . '/../'))->load();
    }

    public function testSuccesNotify()
    {
        $notifier = new TelegramNotification(
            getenv('TELEGRAM_BOT_TOKEN'),
            getenv('TELEGRAM_CHAT_ID')
        );

        $this->assertTrue($notifier->notify('PHPUnit test success notify'));
    }

    public function testApiWrongTokenError()
    {
        $notifier = new TelegramNotification(
            'wrong-token',
            getenv('TELEGRAM_CHAT_ID')
        );

        $this->expectException(\ErrorException::class);
        $this->expectExceptionMessageRegExp('/Telegram API error*/');

        $notifier->notify('test message');
    }

    public function testApiCurlError()
    {
        $notifier = new TelegramNotification(
            "\n\r\t\0",
            getenv('TELEGRAM_CHAT_ID')
        );

        $this->expectException(\RuntimeException::class);

        $notifier->notify('test message');
    }
}