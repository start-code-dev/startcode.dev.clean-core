<?php

use Startcode\CleanCore\Utility\Tools;

class ToolsTest extends \PHPUnit\Framework\TestCase
{

    private Tools $tools;
    private float $requestTimeFloat;

    protected function setUp() : void
    {
        $this->tools = new Tools();
        $this->requestTimeFloat = $_SERVER['REQUEST_TIME_FLOAT'];
    }

    protected function tearDown(): void
    {
        $_SERVER['REQUEST_TIME_FLOAT'] = $this->requestTimeFloat;
    }

    public function testReservedIpPrependHeadersLocalhost() : void
    {
        $_SERVER = [
            'HTTP_X_CLIENT_IP' => '192.168.1.1'
        ];

        $this->assertFalse($this->tools->getRealIP(false, ['HTTP_X_CLIENT_IP']));
    }

    public function testReservedIpPrependHeadersAllowPrivateRangeLocalhost() : void
    {
        $ip = '192.168.1.1';
        $_SERVER = [
            'HTTP_X_CLIENT_IP' => $ip
        ];

        $this->assertEquals($ip, $this->tools->getRealIP(true, ['HTTP_X_CLIENT_IP']));
    }

    public function testReservedIpPrependHeaders() : void
    {
        $ip = '10.1.2.3';
        $_SERVER = [
            'HTTP_X_CLIENT_IP' => $ip
        ];

        $this->assertFalse($this->tools->getRealIP(false, ['HTTP_X_CLIENT_IP']));
    }

    public function testReservedIpPrependHeadersAllowPrivateRange() : void
    {
        $ip = '10.1.2.3';
        $_SERVER = [
            'HTTP_X_CLIENT_IP' => $ip
        ];

        $this->assertEquals($ip, $this->tools->getRealIP(true, ['HTTP_X_CLIENT_IP']));
    }

    public function testIp1() : void
    {
        $ip = '160.99.1.1';

        $_SERVER = [
            'HTTP_X_CLIENT_IP' => $ip,
            'REMOTE_ADDR' => '199.99.99.99'
        ];

        $this->assertEquals($ip, $this->tools->getRealIP(false, ['HTTP_X_CLIENT_IP']));
    }

    public function testIp1NoPrepend() : void
    {
        $ip = '160.99.1.1';

        $_SERVER = [
            'HTTP_X_CLIENT_IP' => $ip,
            'REMOTE_ADDR' => '199.99.99.99'
        ];

        $this->assertEquals('199.99.99.99', $this->tools->getRealIP());
    }

    public function testCfIp1() : void
    {
        $ip = '160.99.1.1';

        $_SERVER = [
            'HTTP_CF_CONNECTING_IP' => $ip,
            'REMOTE_ADDR' => '199.99.99.99'
        ];

        $this->assertEquals($ip, $this->tools->getRealIP());
    }

    public function testClientIp() : void
    {
        $ip = '160.99.1.1';

        $_SERVER = [
            'HTTP_CLIENT_IP' => $ip,
            'REMOTE_ADDR' => '199.99.99.99'
        ];

        $this->assertEquals($ip, $this->tools->getRealIP());
    }

    public function testHttpXForwardedFor() : void
    {
        $ip = '180.99.99.12,123.123.123.123,160.99.1.1';

        $_SERVER = [
            'HTTP_X_FORWARDED_FOR' => $ip,
            'REMOTE_ADDR' => '199.99.99.99'
        ];

        $this->assertEquals('180.99.99.12', $this->tools->getRealIP());
    }

    public function testHttpXForwardedForWithPort() : void
    {
        $ip = '180.99.99.12:7777,123.123.123.123,160.99.1.1';

        $_SERVER = [
            'HTTP_X_FORWARDED_FOR' => $ip,
            'REMOTE_ADDR' => '199.99.99.99'
        ];

        $this->assertEquals('180.99.99.12', $this->tools->getRealIP());
    }

    public function testPort() : void
    {
        $ip = '160.99.1.1:8888';

        $_SERVER = [
            'CLIENT_IP' => $ip,
            'REMOTE_ADDR' => '199.99.99.99'
        ];

        $this->assertEquals('160.99.1.1', $this->tools->getRealIP());
    }

    public function testIpv6() : void
    {
        $ip = '2001:4860:4801:40::32';

        $_SERVER = [
            'CLIENT_IP' => $ip,
            'REMOTE_ADDR' => '199.99.99.99'
        ];

        $this->assertEquals($ip, $this->tools->getRealIP());
    }

    public function testIpv6Private() : void
    {
        $ip = 'fd30::1:ff4e:3e:9:e';    // private range ipv6

        $_SERVER = [
            'CLIENT_IP' => $ip,
        ];

        $this->assertFalse($this->tools->getRealIP());
    }

    public function testIpv6NoPrivatePrivate() : void
    {
        $ip = 'fd30::1:ff4e:3e:9:e';    // private range ipv6

        $_SERVER = [
            'CLIENT_IP' => $ip,
        ];

        $this->assertEquals($ip, $this->tools->getRealIP(true));
    }

}
