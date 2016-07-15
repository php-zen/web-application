<?php
/**
 * 定义当数据过大时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Cookies\Cookie;

/**
 * 当数据过大时抛出地异常。
 *
 * @version    0.1.0
 *
 * @since      0.1.0
 *
 * @method void __construct(string $name, \Exception $prev = null) 构造函数
 */
final class ExDataTooLong extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = 'COOKIE “%name$s”过大，无法保存。';

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @var string[]
     */
    protected static $contextSequence = array('name');
}
