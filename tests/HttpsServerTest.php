<?php
declare(strict_types=1);

namespace Monot\Tests;

use PHPUnit\Framework\TestCase;
use Monot\Point\HttpsServer;

class HttpsServerTest extends TestCase
{
    public function testTarget()
    {
        $target = 'gulchuk.com';
        $point = new HttpsServer($target);

        $this->assertSame('https://' . $target, $point->getTarget());
    }

    public function testSuccessCheck()
    {
        $target = 'gulchuk.com';
        $point = new HttpsServer($target);

        $this->assertTrue($point->check());
    }
}