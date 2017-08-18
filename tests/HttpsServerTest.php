<?php
declare(strict_types=1);

namespace Monot\Tests;

use PHPUnit\Framework\TestCase;
use Monot\Notification\NullNotification;
use Monot\Point\HttpsServer;

class HttpsServerTest extends TestCase
{
    private $notifier;

    public function setUp()
    {
        $this->notifier = new NullNotification();
    }

    public function testTarget()
    {
        $target = 'gulchuk.com';
        $point = new HttpsServer($this->notifier, $target);

        $this->assertSame('https://' . $target, $point->getTarget());
    }

    public function testSuccessCheck()
    {
        $target = 'gulchuk.com';
        $point = new HttpsServer($this->notifier, $target);

        $this->assertTrue($point->check());
    }

    public function testNotExistHostCheck()
    {
        $target = 'not-exists-domain.com';
        $point = new HttpsServer($this->notifier, $target);

        $this->assertFalse($point->check());
    }

    public function testNot200CodeCheck()
    {
        $target = 'www.gulchuk.com';
        $point = new HttpsServer($this->notifier, $target);

        $this->assertFalse($point->check());
    }
}