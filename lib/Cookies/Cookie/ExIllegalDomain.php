<?php
/**
 * 定义当作用域不合法时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Cookies\Cookie;

/**
 * 当作用域不合法时抛出地异常。
 *
 * @version    0.1.0
 *
 * @since      0.1.0
 *
 * @method void __construct(string $name, string $domain, \Exception $prev = null) 构造函数
 */
final class ExIllegalDomain extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = 'COOKIE “%name$s”的作用域不能设置为“%domain$s”。';

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @var string[]
     */
    protected static $contextSequence = array('name', 'domain');
}
