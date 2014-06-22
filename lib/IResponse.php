<?php
/**
 * 声明 Web 应用程序的 HTTP 响应组件规范。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application;

use Zen\Core\Application as ZenApp;

/**
 * Web 应用程序的 HTTP 响应组件规范。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 */
interface IResponse extends ZenApp\IOutput
{
    const STATUS_CONTINUE = 100;

    const STATUS_SWITCHING_PROTOCOLS = 101;

    const STATUS_OK = 200;

    const STATUS_CREATED = 201;

    const STATUS_ACCEPTED = 202;

    const STATUS_NO_NAUTHORITATIVE_INFORMATION = 203;

    const STATUS_NO_CONTENT = 204;

    const STATUS_RESET_CONTENT = 205;

    const STATUS_PARTIAL_CONTENT = 206;

    const STATUS_MULTIPLE_CHOICES = 300;

    const STATUS_MOVED_PERMANENTLY = 301;

    const STATUS_FOUND = 302;

    const STATUS_SEE_OTHER = 303;

    const STATUS_NOT_MODIFIED = 304;

    const STATUS_USE_PROXY = 305;

    const STATUS_TEMPORARY_REDIRECT = 307;

    const STATUS_BAD_REQUEST = 400;

    const STATUS_UNAUTHORIZED = 401;

    const STATUS_PAYMENT_REQUIRED = 402;

    const STATUS_FORBIDDEN = 403;

    const STATUS_NOT_FOUND = 404;

    const STATUS_METHOD_NOT_ALLOWED = 405;

    const STATUS_NOT_ACCEPTABLE = 406;

    const STATUS_PROXY_AUTHENTICATION_REQUIRED = 407;

    const STATUS_REQUEST_TIMEOUT = 408;

    const STATUS_CONFLICT = 409;

    const STATUS_GONE = 410;

    const STATUS_LENGTH_REQUIRED = 411;

    const STATUS_PRECONDITION_FAILED = 412;

    const STATUS_REQUEST_ENTITY_TOO_LARGE = 413;

    const STATUS_REQUEST_URI_TOO_LONG = 414;

    const STATUS_UNSUPPORTED_MEDIA_TYPE = 415;

    const STATUS_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    const STATUS_EXPECTATION_FAILED = 417;

    const STATUS_INTERNAL_SERVER_ERROR = 500;

    const STATUS_NOT_IMPLEMENTED = 501;

    const STATUS_BAD_GATEWAY = 502;

    const STATUS_SERVICE_UNAVAILABLE = 503;

    const STATUS_GATEWAY_TIMEOUT = 504;

    const STATUS_HTTP_VERSION_NOT_SUPPORTED = 505;

    /**
     * 添加 HTTP 响应头信息。
     *
     * @param  string $field    头字段
     * @param  string $value    信息值
     * @param  bool   $multiply 可选。是否发送重名头信息
     * @return self
     */
    public function header($field, $value, $multiply = false);

    /**
     * 结束响应并跳转至指定 URI 。
     *
     * @param  string $uri         目标 URI
     * @param  bool   $permanently 可选。是否为永久跳转
     * @return self
     */
    public function redirect($uri, $permanently = false);

    /**
     * 标记响应状态。
     *
     * @param  int  $code 新状态值
     * @return self
     */
    public function state($code);
}
