<?php
/**
 * 定义 Web 应用程序的 HTTP 响应组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Response;

use Zen\Core\Application as ZenCoreApp;
use Zen\Web\Application as ZenWebApp;

/**
 * Web 应用程序的 HTTP 响应组件。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 */
class Response extends ZenCoreApp\Output\Output implements ZenWebApp\IResponse
{
    /**
     * 待输出地响应头信息集合。
     *
     * @internal
     *
     * @var array[]
     */
    protected $headers;

    /**
     * 待输出地状态。
     *
     * @internal
     *
     * @var int
     */
    protected $status;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->headers = array();
        $this->status = self::STATUS_OK;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $field    头字段名
     * @param  string $value    信息值
     * @param  bool   $multiply 可选。是否发送重名头信息
     * @return self
     */
    final public function header($field, $value, $multiply = false)
    {
        if (!$multiply || !array_key_exists($field, $this->headers)) {
            $this->headers[$field] = array($value);
        } else {
            $this->headers[$field][] = $value;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $uri         目标 URI
     * @param  bool   $permanently 可选。是否为永久跳转
     * @return self
     */
    final public function redirect($uri, $permanently = false)
    {
        return $this->state($permanently ? self::STATUS_MOVED_PERMANENTLY : self::STATUS_FOUND)
            ->header('Location', $uri)
            ->close();
    }

    /**
     * {@inheritdoc}
     *
     * @param  int  $code 新状态值
     * @return self
     */
    final public function state($code)
    {
        $this->status = (int) $code;

        return $this;
    }

    /**
     * COOKIE 信息组件实例。
     *
     * @var ZenWebApp\ICookies
     */
    protected $cookies;

    /**
     * {@inheritdoc}
     *
     * @param  ZenWebApp\ICookies $cookies
     * @return self
     */
    public function withCookies(ZenWebApp\ICookies $cookies)
    {
        $this->cookies = $cookies;

        return $this;
    }

    /**
     * {inheritdoc}
     *
     * @return void
     */
    protected function onClose()
    {
        $s_status = $this->getStatusText();
        header('Status: ' . $s_status, true, $this->status);
        if ($this->cookies) {
            $this->cookies->save($this);
        }
        foreach ($this->headers as $kk => $vv) {
            if (1 == count($vv)) {
                header($kk . ': ' . $vv[0], true, $this->status);
                continue;
            }
            foreach ($vv as $ww) {
                header($kk . ': ' . $ww, false, $this->status);
            }
        }
    }

    /**
     * 获取状态字符串。
     *
     * @return string
     */
    protected function getStatusText()
    {
        switch ($this->status) {
            case 100:
                return '100 Continue';
            case 101:
                return '101 Switching Protocols';
            case 200:
                return '200 OK';
            case 201:
                return '201 Created';
            case 202:
                return '202 Accepted';
            case 203:
                return '203 Non-Authoritative Information';
            case 204:
                return '204 No Content';
            case 205:
                return '205 Reset Content';
            case 206:
                return '206 Partial Content';
            case 300:
                return '300 Multiple Choices';
            case 301:
                return '301 Moved Permanently';
            case 302:
                return '302 Found';
            case 303:
                return '303 See Other';
            case 304:
                return '304 Not Modified';
            case 305:
                return '305 Use Proxy';
            case 306:
                return '306 (Unused)';
            case 307:
                return '307 Temporary Redirect';
            case 400:
                return '400 Bad Request';
            case 401:
                return '401 Unauthorized';
            case 402:
                return '402 Payment Required';
            case 403:
                return '403 Forbidden';
            case 404:
                return '404 Not Found';
            case 405:
                return '405 Method Not Allowed';
            case 406:
                return '406 Not Acceptable';
            case 407:
                return '407 Proxy Authentication Required';
            case 408:
                return '408 Request Timeout';
            case 409:
                return '409 Conflict';
            case 410:
                return '410 Gone';
            case 411:
                return '411 Length Required';
            case 412:
                return '412 Precondition Failed';
            case 413:
                return '413 Request Entity Too Large';
            case 414:
                return '414 Request-URI Too Long';
            case 415:
                return '415 Unsupported Media Type';
            case 416:
                return '416 Requested Range Not Satisfiable';
            case 417:
                return '417 Expectation Failed';
            case 500:
                return '500 Internal Server Error';
            case 501:
                return '501 Not Implemented';
            case 502:
                return '502 Bad Gateway';
            case 503:
                return '503 Service Unavailable';
            case 504:
                return '504 Gateway Timeout';
            case 505:
                return '505 HTTP Version Not Supported';
            default:
                return $this->status . ' Unknown';
        }
    }
}
