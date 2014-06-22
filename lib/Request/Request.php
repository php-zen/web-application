<?php
/**
 * 定义 Web 应用程序的 HTTP 请求组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Request;

use Zen\Core\Application as ZenCoreApp;
use Zen\Web\Application as ZenWebApp;

/**
 * Web 应用程序的 HTTP 请求组件。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 */
class Request extends ZenCoreApp\Input\Input implements ZenWebApp\IRequest
{
    /**
     * {@inheritdoc}
     */
    final public function __construct()
    {
        parent::__construct();
        $this->params['get'] = $_GET;
        $this->params['post'] = $_POST;
        $this->params['server'] = $_SERVER;
        $this->params['env'] = $_ENV;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function summarize()
    {
        return $this->params['server']['PATH_INFO'];
    }
}
