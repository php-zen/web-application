<?php
/**
 * 定义 Web 应用程序的 HTTP COOKIE 组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Cookies;

use Zen\Core as ZenCore;
use Zen\Web\Application as ZenApp;

/**
 * Web 应用程序的 HTTP COOKIE 组件。
 *
 * @version 0.1.0
 *
 * @since   0.1.0
 */
class Cookies extends ZenCore\Component implements ZenApp\ICookies
{
    /**
     * 请求组件实例。
     *
     * @var ZenApp\IRequest
     */
    protected $request;

    /**
     * COOKIE 集合。
     *
     * @var Cookie\Cookie[]
     */
    protected $data;

    /**
     * 已删除 COOKIE 集合。
     *
     * @var Cookie\Cookie[]
     */
    protected $trash;

    /**
     * 数量统计。
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * 获取指定 COOKIE 。
     *
     * @param string $offset
     *
     * @return Cookie\Cookie
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            return;
        }
        if (!$this->data[$offset] instanceof Cookie\Cookie) {
            $this->data[$offset] = Cookie\Cookie::import($offset, $this->data[$offset], $this->request);
        }

        return $this->data[$offset];
    }

    /**
     * 设置 COOKIE。
     *
     * @param string $offset
     * @param mixed  $value
     */
    public function offsetSet($offset, $value)
    {
        $o_cookie = $value;
        if (!$o_cookie instanceof Cookie\Cookie) {
            $o_cookie = new Cookie\Cookie($offset, $this->request);
            $o_cookie->value = $value;
        }
        $this->data[$offset] = $o_cookie;
    }

    /**
     * 检查指定 COOKIE 已否存在。
     *
     * @param string $offset
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * 删除指定 COOKIE。
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        $o_cookie = $this->offsetGet($offset);
        if ($o_cookie) {
            $o_cookie->expire = 0;
            $this->trash[$offset] = $o_cookie;
            unset($this->data[$offset]);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param ZenApp\IRequest $request
     */
    public function __construct(ZenApp\IRequest $request)
    {
        $this->request = $request;
        $this->data = $_COOKIE;
        $this->trash = array();
    }

    /**
     * {@inheritdoc}
     *
     * @param ZenApp\IResponse $response
     *
     * @return self
     */
    public function save(ZenApp\IResponse $response)
    {
        $a_cmds = array();
        foreach ($this->data as $kk => $vv) {
            if ($vv instanceof Cookie\Cookie) {
                $a_tmp = $vv->export();
                if (count($a_tmp)) {
                    $a_cmds = array_merge($a_cmds, $a_tmp);
                }
            }
        }
        foreach ($this->trash as $kk => $vv) {
            $a_tmp = $vv->export();
            if (count($a_tmp)) {
                $a_cmds = array_merge($a_cmds, $a_tmp);
            }
        }
        foreach ($a_cmds as $vv) {
            $response->header('Set-Cookie', $vv, true);
        }

        return $this;
    }
}
