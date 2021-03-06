<?php
/**
 * 声明 Web 应用程序的 HTTP COOKIE 组件规范。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application;

use Countable;
use ArrayAccess;

/**
 * Web 应用程序的 HTTP COOKIE 组件规范。
 *
 * @version 0.1.0
 *
 * @since   0.1.0
 */
interface ICookies extends Countable, ArrayAccess
{
    /**
     * 构造函数。
     *
     * @param IRequest $request
     */
    public function __construct(IRequest $request);

    /**
     * 保存变更至响应组件。
     *
     * @param IResponse $response 响应组件实例
     *
     * @return self
     */
    public function save(IResponse $response);
}
