<?php
/**
 * 定义当 COOKIE 键名为空时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Cookies\Cookie;

/**
 * 当 COOKIE 键名为空时抛出地异常。
 *
 * @version    0.1.0
 *
 * @since      0.1.0
 */
final class ExIdRequired extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = 'COOKIE 键名不能为空。';
}
