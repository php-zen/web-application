<?php
/**
 * 定义用于 Web 应用程序中路由处理逻辑地快速响应的特殊控制器。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Controller;

use Zen\Core\Application;

/**
 * 用于 Web 应用程序中路由处理逻辑地快速响应的特殊控制器。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 */
final class QuickRespondController extends Application\Controller\Controller
{
    /**
     * {@inheritdoc}
     *
     * @param  Application\IRouterToken $token 路由令牌组件实例
     * @return void
     */
    public function act(Application\IRouterToken $token)
    {
        if ('' != $token['status']) {
            $this->output->state($token['status']);
        }
        if (isset($token['lob'])) {
            $s_lob = preg_replace('/\$(\w+)\b/', '{$token[\'\1\']}', addslashes($token['lob']));
            eval('$s_lob="' . $s_lob . '";');
            if (301 == $token['status'] || 302 == $token['status']) {
                $this->output->header('Location', $s_lob);
            } else {
                $this->output->write($s_lob);
            }
        }
        $this->output->close();
    }
}
