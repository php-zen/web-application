<?php
/**
 * 声明 Web 应用程序的 HTTP 请求组件规范。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application;

use Zen\Core\Type as ZenType;
use Zen\Core\Application as ZenApp;

/**
 * Web 应用程序的 HTTP 请求组件规范。
 *
 * @version 0.1.0
 *
 * @since   0.1.0
 */
interface IRequest extends ZenApp\IInput
{
    /**
     * 获取请求协议。
     *
     * @return string
     */
    public function getProtocol();

    /**
     * 获取请求主机名称。
     *
     * @return string
     */
    public function getHost();

    /**
     * 获取请求目标端口。
     *
     * @return int
     */
    public function getPort();

    /**
     * 获取请求资源路径。
     *
     * @return string
     */
    public function getPath();

    /**
     * 获取请求查询信息。
     *
     * @return string
     */
    public function getSearch();

    /**
     * 获取请求来源。
     *
     * @return string
     */
    public function getReferer();

    /**
     * 获取请求源域。
     *
     * @return string
     */
    public function getOrigin();

    /**
     * 获取请求时间。
     *
     * @return ZenType\DateTime
     */
    public function getTime();
}
