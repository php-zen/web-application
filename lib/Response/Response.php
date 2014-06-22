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
     * {inheritdoc}
     *
     * @return self
     */
    public function close()
    {
        if (!$this->closed) {
            header('Status: ' . $this->status, true, $this->status);
            foreach ($this->headers as $kk => $vv) {
                foreach ($vv as $ww) {
                    header($kk . ': ' . $ww, false, $this->status);
                }
            }
        }

        return parent::close();
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
        if ($multiply || !array_key_exists($field, $this->headers)) {
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
}
