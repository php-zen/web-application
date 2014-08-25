<?php
/**
 * 定义应用程序的控制器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Controller;

use Zen\Core;
use Zen\Web\Application;

/**
 * 应用程序的控制器组件。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 */
abstract class Controller extends Core\Application\Controller\Controller
{
    /**
     * 关联地 COOKIE 组件实例。
     *
     * @var Application\ICookies
     */
    protected $cookies;

    /**
     * {@inheritdoc}
     *
     * @param Core\Application\IApplication $app 应用程序组件实例
     */
    public function __construct(Core\Application\IApplication $app)
    {
        parent::__construct($app);
        $this->cookies = $app->getCookies();
    }
}
