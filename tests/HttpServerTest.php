<?php
declare(strict_types=1);

namespace Monot\Tests;

use PHPUnit\Framework\TestCase;
use Monot\Notification\NullNotification;
use Monot\Point\HttpServer;

class HttpServerTest extends TestCase
{
    private $notifier;

    public function setUp()
    {
        $this->notifier = new NullNotification();
    }

    public function testTarget()
    {
        $target = 'www.i.ua';
        $point = new HttpServer($this->notifier, $target);

        $this->assertSame('http://' . $target, $point->getTarget());
    }

    public function testSuccessCheck()
    {
        $target = 'www.i.ua';
        $point = new HttpServer($this->notifier, $target);

        $this->assertTrue($point->check());
    }

    public function testNotExistHostCheck()
    {
        $target = 'not-exists-domain.com';
        $point = new HttpServer($this->notifier, $target);

        $this->assertFalse($point->check());
    }

    public function testNot200CodeCheck()
    {
        $target = 'i.ua';
        $point = new HttpServer($this->notifier, $target);

        $this->assertFalse($point->check());
    }
}