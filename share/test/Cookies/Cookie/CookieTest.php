<?php
/**
 * 配置 Web 应用程序的 HTTP COOKIE 单项组件的单元测试。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace ZenTest\Web\Application\Cookies\Cookie;

use DateTime;
use Exception;
use PHPUnit_Framework_TestCase;
use Zen\Web\Application as ZenApp;
use Zen\Web\Application\Cookies\Cookie\Cookie as Unit;

/**
 * Web 应用程序的 HTTP COOKIE 单项组件的单元测试。
 */
class CookieTest extends PHPUnit_Framework_TestCase implements ZenApp\IRequest
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

    /**
     * @expectedException Zen\Web\Application\Cookies\Cookie\ExIdRequired
     */
    public function testIdCannotBeEmpty()
    {
        $o_obj = new Unit('', $this);
    }

    public function testPropsDefaultsValues()
    {
        $o_obj = new Unit('foo', $this);
        $this->assertEquals(Unit::SESSION, $o_obj->expire);
        $this->assertEquals('/', $o_obj->path);
        $this->assertEquals('szen.in', $o_obj->domain);
    }

    /**
     * @expectedException Zen\Web\Application\Cookies\Cookie\ExDataTooLong
     */
    public function testHugeValueException()
    {
        $o_obj = new Unit('foo', $this);
        $s_data = '';
        for ($ii = 0; $ii < 192; ++$ii) {
            $s_data .= md5(microtime().$ii);
        }
        $o_obj->value = $s_data;
    }

    public function testExpireAcceptVariantTypesValues()
    {
        $o_obj = new Unit('foo', $this);
        $o_obj->expire = 0;
        $this->assertInstanceOf('Zen\Core\Type\DateTime', $o_obj->expire);
        $this->assertEquals(0, $o_obj->expire->getTimestamp());
        $o_obj->expire = '+1day';
        $this->assertInstanceOf('Zen\Core\Type\DateTime', $o_obj->expire);
        $o_time = new DateTime('+1week');
        $o_obj->expire = $o_time;
        $this->assertEquals($o_time, $o_obj->expire);
        $o_obj->expire = Unit::SESSION;
        $this->assertEquals(Unit::SESSION, $o_obj->expire);
    }

    public function testPathAutoFixLeadingSlash()
    {
        $o_obj = new Unit('foo', $this);
        $o_obj->path = 'bar/';
        $this->assertEquals('/bar', $o_obj->path);
    }

    /**
     * @expectedException Zen\Web\Application\Cookies\Cookie\ExIllegalDomain
     */
    public function testDomainMustBeSubOfRoot()
    {
        $o_obj = new Unit('foo', $this);
        $o_obj->domain = 'dummy.szen.in.local';
    }

    public function testExportingSessionedAndTimed()
    {
        $o_obj = new Unit('foo', $this);
        $a_q = $o_obj->export();
        $this->assertCount(0, $a_q);
        $o_obj->value = ' ';
        $a_q = $o_obj->export();
        $this->assertCount(1, $a_q);
        $this->assertRegExp('#^foo=[\da-zA-Z%/]+; path=/; domain=szen\.in$#', $a_q[0]);
        $o_obj->expire = '+1day';
        $a_q = $o_obj->export();
        $this->assertCount(1, $a_q);
        $this->assertRegExp('#^foo=[\da-zA-Z%/]+; expire=.+; max-age=[1-9]\d+; path=/; domain=szen\.in$#', $a_q[0]);
    }

    public function testImportingExported()
    {
        $o_src = new Unit('foo', $this);
        $o_src->value = microtime();
        $o_src->path = 'bar';
        $o_src->domain = 'demo.szen.in';
        $a_q = $o_src->export();
        $o_dst = Unit::import('bar', preg_replace('#^foo=([^;]+);.*$#', '$1', $a_q[0]), $this);
        $this->assertEquals($o_src->value, $o_dst->value);
        $this->assertEquals($o_src->expire, $o_dst->expire);
        $this->assertEquals($o_src->path, $o_dst->path);
        $this->assertEquals($o_src->domain, $o_dst->domain);
        $o_src->value = microtime();
        $o_src->expire = '+1 week';
        $a_q = $o_src->export();
        $o_dst = Unit::import('bar', preg_replace('#^foo=([^;]+);.*$#', '$1', $a_q[0]), $this);
        $this->assertEquals($o_src->value, $o_dst->value);
        $this->assertEquals($o_src->expire, $o_dst->expire);
        $this->assertEquals($o_src->path, $o_dst->path);
        $this->assertEquals($o_src->domain, $o_dst->domain);
    }

    public function testImportingReferenced()
    {
        $s_lob = 'a:3:{i:0;b:0;i:1;i:1;i:2;s:1:"b";}';
        $o_obj = Unit::import('foo', $s_lob, $this);
        $this->assertEquals($s_lob, $o_obj->value);
        $this->assertEquals(Unit::SESSION, $o_obj->expire);
        $this->assertCount(0, $o_obj->export());
    }

    public function testQuietOnNothingChanged()
    {
        $s_value = microtime();
        $s_path = 'bar';
        $s_domain = 'demo.szen.in';
        $o_src = new Unit('foo', $this);
        $o_src->value = $s_value;
        $o_src->path = $s_path;
        $o_src->domain = $s_domain;
        $a_q = $o_src->export();
        $o_dst = Unit::import('bar', preg_replace('#^foo=([^;]+);.*$#', '$1', $a_q[0]), $this);
        $o_dst->value = microtime();
        $o_dst->path = 'blah';
        $o_dst->domain = 'dummy.szen.in';
        $this->assertCount(2, $o_dst->export());
        $o_dst->value = $s_value;
        $o_dst->path = $s_path;
        $o_dst->domain = $s_domain;
        $this->assertCount(0, $o_dst->export());
    }

    public function testPurgingByEmptyValueOrEpochExpire()
    {
        $o_src = new Unit('foo', $this);
        $o_src->value = microtime();
        $o_src->path = 'bar';
        $o_src->domain = 'demo.szen.in';
        $a_q = $o_src->export();
        $s_data = preg_replace('#^foo=([^;]+);.*$#', '$1', $a_q[0]);
        $s_cmd = '=zen; expire=Thursday, 01-Jan-1970 00:00:00 UTC; max-age=0; path=/bar; domain=demo.szen.in';
        $o_dst = Unit::import('bar', $s_data, $this);
        $o_dst->value = '';
        $a_q = $o_dst->export();
        $this->assertCount(1, $a_q);
        $this->assertEquals('bar'.$s_cmd, $a_q[0]);
        $o_dst = Unit::import('blah', $s_data, $this);
        $o_dst->expire = 0;
        $a_q = $o_dst->export();
        $this->assertCount(1, $a_q);
        $this->assertEquals('blah'.$s_cmd, $a_q[0]);
    }

    public function testOldShouldBePurgedOnChangingDomainOrPath()
    {
        $o_src = new Unit('foo', $this);
        $o_src->value = microtime();
        $o_src->expire = '+1day';
        $o_src->path = 'bar';
        $o_src->domain = 'demo.szen.in';
        $a_q = $o_src->export();
        $s_data = preg_replace('#^foo=([^;]+);.*$#', '$1', $a_q[0]);
        $s_cmd = '=zen; expire=Thursday, 01-Jan-1970 00:00:00 UTC; max-age=0; path=/bar; domain=demo.szen.in';
        $o_dst = Unit::import('bar', $s_data, $this);
        $o_dst->path = '/';
        $a_q = $o_dst->export();
        $this->assertCount(2, $a_q);
        $this->assertEquals('bar'.$s_cmd, $a_q[0]);
        $this->assertRegExp('#^bar=[\da-zA-Z%/]+; expire=.+; max-age=[1-9]\d+; path=/; domain=demo\.szen\.in$#', $a_q[1]);
        $o_dst = Unit::import('bar', $s_data, $this);
        $o_dst->domain = 'dummy.szen.in';
        $a_q = $o_dst->export();
        $this->assertCount(2, $a_q);
        $this->assertEquals('bar'.$s_cmd, $a_q[0]);
        $this->assertRegExp('#^bar=[\da-zA-Z%/]+; expire=.+; max-age=[1-9]\d+; path=/bar; domain=dummy\.szen\.in$#', $a_q[1]);
    }

    public function testOverwritingReferencedOnChanging()
    {
        $s_lob = 'a:3:{i:0;b:0;i:1;i:1;i:2;s:1:"b";}';
        $o_obj = Unit::import('foo', $s_lob, $this);
        $o_obj->value = microtime();
        $a_q = $o_obj->export();
        $this->assertCount(1, $a_q);
        $this->assertRegExp('#^foo=[\da-zA-Z%/]+; path=/; domain=szen\.in$#', $a_q[0]);
        $o_obj = Unit::import('foo', $s_lob, $this);
        $o_obj->path = 'bar';
        $a_q = $o_obj->export();
        $this->assertCount(2, $a_q);
        $this->assertEquals('foo=zen; expire=Thursday, 01-Jan-1970 00:00:00 UTC; max-age=0; path=/; domain=szen.in', $a_q[0]);
        $this->assertRegExp('#^foo=[\da-zA-Z%/]+; path=/bar; domain=szen\.in$#', $a_q[1]);
    }
}
