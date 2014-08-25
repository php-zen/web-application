<?php
/**
 * 定义 Web 应用程序的 HTTP COOKIE 组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Cookies;

use Zen\Core as ZenCore;
use Zen\Web\Application as ZenWebApp;

/**
 * Web 应用程序的 HTTP COOKIE 组件。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 */
class Cookies extends ZenCore\Component implements ZenWebApp\ICookies
{
    /**
     * COOKIE 集合。
     *
     * @var Cookie\Cookie[]
     */
    protected $data;

    /**
     * COOKIE 元信息表。
     *
     * @var array[]
     */
    protected $meta;

    /**
     * COOKIE 变更值集合。
     *
     * @var Cookie\Cookie[]
     */
    protected $diff;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->data = $_COOKIE;
        if (isset($this->data['_zcmt_'])) {
            $this->meta = json_decode($this->data['_zcmt_'], true);
        }
        if (!is_array($this->meta)) {
            $this->meta = array();
        }
        $this->changes = array();
    }

    /**
     * 检查 COOKIE 是否存在。
     *
     * @param  scalar $offset 名称
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * 获取 COOKIE 值。
     *
     * @param  scalar $offset 名称
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            if (!$this->data[$offset] instanceof Cookie\Cookie) {
                if (!isset($this->meta[$offset])) {
                    $this->data[$offset] = new Cookie\Cookie($this->data[$offset]);
                } else {
                    if ($this->meta[$offset][0]) {
                        $a_ts = unpack('V', base64_decode($this->meta[$offset][0]));
                        $o_expire = new ZenCore\Type\DateTime;
                        $o_expire->setTimestamp($a_ts[1]);
                    } else {
                        $o_expire = null;
                    }
                    $this->data[$offset] = new Cookie\Cookie(
                        $this->data[$offset],
                        $o_expire,
                        $this->meta[$offset][1],
                        $this->meta[$offset][2],
                        $this->meta[$offset][3]
                    );
                }
            }

            return $this->data[$offset];
        }
    }

    /**
     * 添加或更改 COOKIE 。
     *
     * @param  scalar $offset 名称
     * @param  mixed  $value  新值
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Cookie\Cookie) {
            $value = new Cookie\Cookie($value);
        }
        $this->data[$offset] = $value;
        $this->diff[$offset] = $value;
        $this->meta[$offset] = array(
            $value->getExpiration()
                ? base64_encode(pack('V', $value->getExpiration()->getTimestamp()))
                : '',
            $value->getPath(),
            $value->getDomain(),
            (int) $value->getSecure()
        );
    }

    /**
     * 删除 COOKIE 。
     *
     * @param  scalar $offset 名称
     * @return void
     *
     * @throws ExCookieMetaMissing 当 COOKIE 元信息丢失时
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            if (!isset($this->meta[$offset])) {
                throw new ExCookieMetaMissing($offset);
            }
            $this->diff[$offset] = new Cookie\Cookie(
                'deleted',
                'epoch',
                $this->meta[$offset][1],
                $this->meta[$offset][2],
                $this->meta[$offset][3]
            );
            unset($this->data[$offset], $this->meta[$offset]);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param  ZenWebApp\IResponse $response
     * @return self
     */
    public function save(ZenWebApp\IResponse $response)
    {
        /** @var $jj Cookie\Cookie **/
        foreach ($this->diff as $ii => $jj) {
            $a_cookie = array($ii . '=' . $jj->value);
            if (null !== $jj->getExpiration()) {
                $a_cookie[] = 'expire=' . $jj->getExpiration()->format(DATE_COOKIE);
                $a_cookie[] = 'Max-age=' . max(0, $jj->getExpiration()->getTimestamp() - time());
            }
            if ($jj->getPath()) {
                $a_cookie[] = 'path=' . $jj->getPath();
            }
            if ($jj->getDomain()) {
                $a_cookie[] = 'domain=' . $jj->getDomain();
            }
            if ($jj->getSecure()) {
                $a_cookie[] = 'secure';
            }
            $response->header('Set-Cookie', implode('; ', $a_cookie), true);
        }
        if (!empty($this->diff)) {
            if (empty($this->meta)) {
                $a_cookie = array('_zcmt_=deleted');
                $a_cookie[] = 'expire=Thursday, 01-Jan-1970 00:00:01 UTC';
                $a_cookie[] = 'Max-age=0';
            } else {
                $a_cookie = array('_zcmt_=' . json_encode($this->meta));
                $a_cookie[] = 'expire=Thursday, 31-Dec-2037 23:55:55 UTC';
                $a_cookie[] = 'Max-age=315360000';
            }
            $response->header('Set-Cookie', implode('; ', $a_cookie), true);
        }

        return $this;
    }
}
