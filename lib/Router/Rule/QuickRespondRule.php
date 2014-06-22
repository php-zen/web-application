<?php
/**
 * 定义让路由处理逻辑能够快速输出响应结果的路由规则组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Router\Rule;

use Zen\Core\Application\Router\Rule as ZenRule;

/**
 * 让路由处理逻辑能够快速输出响应结果的路由规则组件。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 */
final class QuickRespondRule extends ZenRule\Rule
{
    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @return string
     */
    protected function aim()
    {
        return 'Zen\Web\Application\Controller\QuickRespondController';
    }
}
