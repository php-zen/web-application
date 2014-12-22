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
        $this->params['file'] = array();
        foreach ($_FILES as $ii => $jj) {
            list($kk, $ll) = each($jj);
            if (is_array($ll)) {
                foreach ($jj as $kk => $ll) {
                    if (UPLOAD_ERR_NO_FILE != $ll['error']) {
                        $this->params['file'][$ii][$kk] = $ll;
                    }
                }
            } elseif (UPLOAD_ERR_NO_FILE != $jj['error']) {
                $this->params['file'][$ii] = $jj;
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $abbr 命名空间缩写
     * @return string
     */
    protected function guessAbbr($abbr)
    {
        $abbr = parent::guessAbbr($abbr);
        switch ($abbr) {
            case 'g':
            case 'ge':
                return 'get';
            case 'p':
            case 'po':
            case 'pos':
                return 'post';
            case 'f':
            case 'fi':
            case 'fil':
                return 'file';
        }

        return $abbr;
    }

    /**
     * 对象化上传文件信息。
     *
     * @param  array                 $value 上传文件信息
     * @return File\File|File\File[]
     */
    protected function onGetFile($value)
    {
        list($ii, $jj) = each($value);
        if (is_array($jj)) {
            $a_ret = array();
            foreach ($value as $ii => $jj) {
                $a_ret[$ii] = new File\File($jj, $this);
            }

            return $a_ret;
        }

        return new File\File($value, $this);
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
