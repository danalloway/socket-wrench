<?php

namespace DanAlloway\SocketWrench\Tests;

use DanAlloway\SocketWrench\SocketWrench;
use PHPUnit\Framework\TestCase;

final class SocketWrenchTest extends TestCase
{
    const SOCKET_SERVER_ADDRESS = '127.0.0.1';
    const SOCKET_SERVER_PORTNUM = 4096;

    public function testInstantiation()
    {
        $client = new SocketWrench(static::SOCKET_SERVER_ADDRESS, static::SOCKET_SERVER_PORTNUM);
        $this->assertInstanceOf(SocketWrench::class, $client);
    }
}
