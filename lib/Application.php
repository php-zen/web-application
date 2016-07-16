<?php
/**
 * 定义 Web 应用程序组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application;

use Zen\Core\Application as ZenApp;

/**
 * Web 应用程序组件。
 *
 * @property-read IRequest  $input  HTTP 请求组件实例
 * @property-read IResponse $output HTTP 响应组件实例
 *
 * @version 0.1.0
 *
 * @since   0.1.0
 */
abstract class Application extends ZenApp\Application
{
    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param string $class HTTP 请求组件类名
     *
     * @return bool
     */
    final protected function isValidInputType($class)
    {
        return is_subclass_of($class, __NAMESPACE__.'\IRequest');
    }

    /**
     * {@inheritdoc}
     *
     * @return Request\Request
     */
    protected function newDefaultInput()
    {
        return new Request\Request();
    }

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param string $class HTTP 响应组件类名
     *
     * @return bool
     */
    final protected function isValidOutputType($class)
    {
        return is_subclass_of($class, __NAMESPACE__.'\IResponse');
    }

    /**
     * {@inheritdoc}
     *
     * @return Response\Response
     */
    protected function newDefaultOutput()
    {
        return new Response\Response();
    }

    /**
     * {@inheritdoc}
     *
     * @param array $routes 路由表
     *
     * @return Router\Router
     */
    protected function newDefaultRouter($routes)
    {
        return new Router\Router($routes);
    }

    /**
     * COOKIE 组件实例。
     *
     * @internal
     *
     * @var ICookies
     */
    protected $cookies;

    /**
     * 获取 COOKIE 组件。
     *
     * @return ICookies
     */
    final public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * 判断指定类是否为有效的 COOKIE 信息组件。
     *
     * @internal
     *
     * @param string $class 类名
     *
     * @return bool
     */
    protected function isValidCookiesType($class)
    {
        return is_subclass_of($class, __NAMESPACE__.'\ICookies');
    }

    /**
     * 创建默认的 COOKIES 信息组件的实例。
     *
     * @return ICookies
     */
    protected function newDefaultCookies()
    {
        return new Cookies\Cookies($this->input);
    }

    /**
     * {@inheritdoc}
     *
     * @internal
     */
    final protected function zenExtend()
    {
        if (!$this->cookies) {
            $this->cookies =
                isset($this->config['appliance.cookies']) &&
                $this->isValidCookiesType($this->config['appliance.cookies'])
                    ? new $this->config['appliance.cookies']($this->input)
                    : $this->newDefaultCookies();
            $this->output->withCookies($this->cookies);
        }
    }
}
