<?php
/**
 * 定义让路由处理逻辑能够快速输出响应结果的路由规则原型组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Router\Routine;

use Zen\Core\Application\Router\Routine as ZenRoutine;

/**
 * 让路由处理逻辑能够快速输出响应结果的路由规则原型组件。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 */
final class QuickRespondRoutine extends ZenRoutine\Routine
{
    /**
     * {@inheritdoc}
     *
     * @internal
     */
    const PATTERN = '@(?P<status>|\d{3})(?:|:(?P<lob>.+))';

    /**
     * {@inheritdoc}
     *
     * @internal
     */
    const RULE_CLASS = 'Zen\Web\Application\Router\Rule\QuickRespondRule';
}
