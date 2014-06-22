<?php
/**
 * 定义 Web 应用程序的路由组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Router;

use Zen\Core\Application;

/**
 * Web 应用程序的路由组件。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 */
class Router extends Application\Router\Router
{
    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @return Application\Router\Routine\Routine[]
     */
    protected function initRoutines()
    {
        return array_merge(
            array(
                Routine\QuickRespondRoutine::singleton()
            ),
            parent::initRoutines()
        );
    }
}
