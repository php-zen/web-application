<?php
/**
 * 定义 Web 应用程序组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application;

use Zen\Core\Application as ZenApp;

/**
 * Web 应用程序组件。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 *
 * @property-read IRequest  $input  HTTP 请求组件实例
 * @property-read IResponse $output HTTP 响应组件实例
 */
abstract class Application extends ZenApp\Application
{
    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param  string $class HTTP 请求组件类名
     * @return bool
     */
    final protected function isValidInputType($class)
    {
        return is_subclass_of($class, __NAMESPACE__ . '\IRequest');
    }

    /**
     * {@inheritdoc}
     *
     * @return Request\Request
     */
    protected function newDefaultInput()
    {
        return new Request\Request;
    }

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param  string $class HTTP 响应组件类名
     * @return bool
     */
    final protected function isValidOutputType($class)
    {
        return is_subclass_of($class, __NAMESPACE__ . '\IResponse');
    }

    /**
     * {@inheritdoc}
     *
     * @return Response\Response
     */
    protected function newDefaultOutput()
    {
        return new Response\Response;
    }

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @return void
     */
    final protected function zenExtend()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @param  array         $routes 路由表
     * @return Router\Router
     */
    protected function newDefaultRouter($routes)
    {
        return new Router\Router($routes);
    }
}
