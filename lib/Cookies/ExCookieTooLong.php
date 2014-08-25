<?php
/**
 * 定义当 COOKIE 值过长时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Cookies;

/**
 * 当 COOKIE 值过长时抛出地异常。
 *
 * @package    Zen\Web\Application
 * @version    0.1.0
 * @since      0.1.0
 *
 * @method void __construct(string $name, \Exception $prev = null) 构造函数
 */
final class ExCookieTooLong extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = 'COOKIE “%name$s”过长。';

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @var string[]
     */
    protected static $contextSequence = array('name');
}
