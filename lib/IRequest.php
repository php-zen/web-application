<?php
/**
 * 声明 Web 应用程序的 HTTP 请求组件规范。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application;

use Zen\Core\Application as ZenApp;

/**
 * Web 应用程序的 HTTP 请求组件规范。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 */
interface IRequest extends ZenApp\IInput
{
}
