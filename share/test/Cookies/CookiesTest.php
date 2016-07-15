<?php
/**
 * 配置 Web 应用程序的 HTTP COOKIE 组件的单元测试。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace ZenTest\Web\Application\Cookies;

use PHPUnit_Framework_TestCase;
use Zen\Web\Application as ZenApp;
use Zen\Web\Application\Cookies\Cookies as Unit;

/**
 * Web 应用程序的 HTTP COOKIE 组件的单元测试。
 */
class CookiesTest extends PHPUnit_Framework_TestCase implements ZenApp\IRequest, ZenApp\IResponse
{
    public function offsetGet($offset)
    {
        throw new Exception();
    }

    public function offsetSet($offset, $value)
    {
        throw new Exception();
    }

    public function offsetExists($offset)
    {
        throw new Exception();
    }

    public function offsetUnset($offset)
    {
        throw new Exception();
    }

    public function summarize()
    {
        throw new Exception();
    }

    public function expect($key, $defaults)
    {
        throw new Exception();
    }

    public function expectType($key, $type, $defaults = null)
    {
        throw new Exception();
    }

    public function expectMatch($key, $pattern, $defaults = null)
    {
        throw new Exception();
    }

    public function getProtocol()
    {
        throw new Exception();
    }

    public function getHost()
    {
        return 'test.szen.in';
    }

    public function getPort()
    {
        throw new Exception();
    }

    public function getPath()
    {
        throw new Exception();
    }

    public function getSearch()
    {
        throw new Exception();
    }

    public function getReferer()
    {
        throw new Exception();
    }

    public function getOrigin()
    {
        throw new Exception();
    }

    public function getTime()
    {
        throw new Exception();
    }

    public function write($binary)
    {
        throw new Exception();
    }

    public function close()
    {
        throw new Exception();
    }

    protected $headers;

    public function header($field, $value, $multiply = false)
    {
        if (!$multiply) {
            $this->headers[$field] = $value;

            return;
        }
        if (!array_key_exists($field, $this->headers)) {
            $this->headers[$field] = array();
        } elseif (!is_array($this->headers[$field])) {
            $this->headers[$field] = array($this->headers[$field]);
        }
        $this->headers[$field][] = $value;
    }

    public function redirect($uri, $permanently = false)
    {
        throw new Exception();
    }

    public function state($code)
    {
        throw new Exception();
    }

    public function withCookies(ZenApp\ICookies $cookies)
    {
        throw new Exception();
    }

    protected function setUp()
    {
        $this->headers = array();
        $_COOKIES = array();
    }

    public function testArrayAccess()
    {
        $o_obj = new Unit($this);
        $this->assertCount(0, $o_obj);
        $o_obj['foo'] = 'bar';
        $this->assertCount(1, $o_obj);
        $this->assertTrue(isset($o_obj['foo']));
        $this->assertNotNull($o_obj['foo']);
        unset($o_obj['foo']);
        $this->assertCount(0, $o_obj);
    }

    public function testClonedFromCookies()
    {
        $_COOKIES = array(
            'foo' => 'bar',
        );
        $o_obj = new Unit($this);
        $this->assertTrue(isset($o_obj['foo']));
        unset($o_obj['foo']);
        $this->assertArrayHasKey('foo', $_COOKIES);
    }

    public function testAdding()
    {
        $o_obj = new Unit($this);
        $o_obj['foo'] = 'bar';
        $o_obj->save($this);
        $this->assertArrayHasKey('Set-Cookie', $this->headers);
        $this->assertCount(1, $this->headers['Set-Cookie']);
        $this->assertRegExp('#^foo=[0-9a-zA-Z%]+; path=/; domain=szen\.in$#', $this->headers['Set-Cookie'][0]);
    }

    public function testPurging()
    {
        $_COOKIES = array(
            'foo' => 'bar',
        );
        $o_obj = new Unit($this);
        unset($o_obj['foo']);
        $o_obj->save($this);
        $this->assertArrayHasKey('Set-Cookie', $this->headers);
        $this->assertCount(1, $this->headers['Set-Cookie']);
        $this->assertEquals('foo=zen; expire=Thursday, 01-Jan-1970 00:00:00 UTC; max-age=0; path=/; domain=szen.in', $this->headers['Set-Cookie'][0]);
    }

    public function testAvoidTemporaryDraft()
    {
        $o_obj = new Unit($this);
        $o_obj['foo'] = 'bar';
        unset($o_obj['foo']);
        $o_obj->save($this);
        $this->assertCount(0, $this->headers);
    }
}
