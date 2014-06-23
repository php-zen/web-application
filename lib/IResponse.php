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
    /**
     * 100 状态码。
     *
     * @var int
     */
    const STATUS_CONTINUE = 100;

    /**
     * 101 状态码。
     *
     * @var int
     */
    const STATUS_SWITCHING_PROTOCOLS = 101;

    /**
     * 200 状态码。
     *
     * @var int
     */
    const STATUS_OK = 200;

    /**
     * 201 状态码。
     *
     * @var int
     */
    const STATUS_CREATED = 201;

    /**
     * 202 状态码。
     *
     * @var int
     */
    const STATUS_ACCEPTED = 202;

    /**
     * 203 状态码。
     *
     * @var int
     */
    const STATUS_NO_NAUTHORITATIVE_INFORMATION = 203;

    /**
     * 204 状态码。
     *
     * @var int
     */
    const STATUS_NO_CONTENT = 204;

    /**
     * 205 状态码。
     *
     * @var int
     */
    const STATUS_RESET_CONTENT = 205;

    /**
     * 206 状态码。
     *
     * @var int
     */
    const STATUS_PARTIAL_CONTENT = 206;

    /**
     * 300 状态码。
     *
     * @var int
     */
    const STATUS_MULTIPLE_CHOICES = 300;

    /**
     * 301 状态码。
     *
     * @var int
     */
    const STATUS_MOVED_PERMANENTLY = 301;

    /**
     * 302 状态码。
     *
     * @var int
     */
    const STATUS_FOUND = 302;

    /**
     * 303 状态码。
     *
     * @var int
     */
    const STATUS_SEE_OTHER = 303;

    /**
     * 304 状态码。
     *
     * @var int
     */
    const STATUS_NOT_MODIFIED = 304;

    /**
     * 305 状态码。
     *
     * @var int
     */
    const STATUS_USE_PROXY = 305;

    /**
     * 307 状态码。
     *
     * @var int
     */
    const STATUS_TEMPORARY_REDIRECT = 307;

    /**
     * 400 状态码。
     *
     * @var int
     */
    const STATUS_BAD_REQUEST = 400;

    /**
     * 401 状态码。
     *
     * @var int
     */
    const STATUS_UNAUTHORIZED = 401;

    /**
     * 402 状态码。
     *
     * @var int
     */
    const STATUS_PAYMENT_REQUIRED = 402;

    /**
     * 403 状态码。
     *
     * @var int
     */
    const STATUS_FORBIDDEN = 403;

    /**
     * 404 状态码。
     *
     * @var int
     */
    const STATUS_NOT_FOUND = 404;

    /**
     * 405 状态码。
     *
     * @var int
     */
    const STATUS_METHOD_NOT_ALLOWED = 405;

    /**
     * 406 状态码。
     *
     * @var int
     */
    const STATUS_NOT_ACCEPTABLE = 406;

    /**
     * 407 状态码。
     *
     * @var int
     */
    const STATUS_PROXY_AUTHENTICATION_REQUIRED = 407;

    /**
     * 408 状态码。
     *
     * @var int
     */
    const STATUS_REQUEST_TIMEOUT = 408;

    /**
     * 409 状态码。
     *
     * @var int
     */
    const STATUS_CONFLICT = 409;

    /**
     * 410 状态码。
     *
     * @var int
     */
    const STATUS_GONE = 410;

    /**
     * 411 状态码。
     *
     * @var int
     */
    const STATUS_LENGTH_REQUIRED = 411;

    /**
     * 412 状态码。
     *
     * @var int
     */
    const STATUS_PRECONDITION_FAILED = 412;

    /**
     * 413 状态码。
     *
     * @var int
     */
    const STATUS_REQUEST_ENTITY_TOO_LARGE = 413;

    /**
     * 414 状态码。
     *
     * @var int
     */
    const STATUS_REQUEST_URI_TOO_LONG = 414;

    /**
     * 415 状态码。
     *
     * @var int
     */
    const STATUS_UNSUPPORTED_MEDIA_TYPE = 415;

    /**
     * 416 状态码。
     *
     * @var int
     */
    const STATUS_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    /**
     * 417 状态码。
     *
     * @var int
     */
    const STATUS_EXPECTATION_FAILED = 417;

    /**
     * 500 状态码。
     *
     * @var int
     */
    const STATUS_INTERNAL_SERVER_ERROR = 500;

    /**
     * 501 状态码。
     *
     * @var int
     */
    const STATUS_NOT_IMPLEMENTED = 501;

    /**
     * 502 状态码。
     *
     * @var int
     */
    const STATUS_BAD_GATEWAY = 502;

    /**
     * 503 状态码。
     *
     * @var int
     */
    const STATUS_SERVICE_UNAVAILABLE = 503;

    /**
     * 504 状态码。
     *
     * @var int
     */
    const STATUS_GATEWAY_TIMEOUT = 504;

    /**
     * 505 状态码。
     *
     * @var int
     */
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
