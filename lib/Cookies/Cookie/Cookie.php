<?php
/**
 * 定义 Web 应用程序的 HTTP COOKIE 单项组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Cookies\Cookie;

use Zen\Core as ZenCore;

/**
 * Web 应用程序的 HTTP COOKIE 单项组件。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 *
 * @property-read mixed                      $value      值
 * @property-read ZenCore\Type\DateTime|null $expiration 过期时间
 * @property-read string                     $path       有效路径
 * @property-read string                     $domain     有效域名
 * @property-read bool                       $secure     安全模式
 */
class Cookie extends ZenCore\Component
{
    /**
     * 值。
     *
     * @var mixed
     */
    protected $value;

    /**
     * 获取值。
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * 过期时间。
     *
     * @var ZenCore\Type\DateTime|null
     */
    protected $expiration;

    /**
     * 获取过期时间。
     *
     * @return ZenCore\Type\DateTime|null
     */
    public function getExpiration()
    {
        return $this->expiration;
    }

    /**
     * 有效路径。
     *
     * @var string
     */
    protected $path;

    /**
     * 获取有效路径。
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * 有效域。
     *
     * @var string
     */
    protected $domain;

    /**
     * 获取有效域。
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * 安全模式。
     *
     * @var bool
     */
    protected $secure;

    /**
     * 获取安全模式。
     *
     * @return bool
     */
    public function getSecure()
    {
        return $this->secure;
    }

    /**
     * 构造函数
     *
     * @param mixed $value 值
     */
    public function __construct($value, $expire = null, $path = '', $domain = '', $secure = false)
    {
        $this->value = $value;
        if (null !== $expire && !$expire instanceof ZenCore\Type\DateTime) {
            $expire = new ZenCore\Type\DateTime($expire);
        }
        $this->expiration = $expire;
        if ('/' == $path) {
            $path = '';
        }
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = (bool) $secure;
    }

    /**
     * 转换成字符串数据。
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }
}
