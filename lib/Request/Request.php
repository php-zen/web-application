<?php
/**
 * 定义 Web 应用程序的 HTTP 请求组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Request;

use Zen\Core\Type as ZenType;
use Zen\Core\Application as ZenCoreApp;
use Zen\Web\Application as ZenWebApp;

/**
 * Web 应用程序的 HTTP 请求组件。
 *
 * @property-read string   $protocol
 * @property-read string   $host
 * @property-read int      $port
 * @property-read string   $path
 * @property-read string   $search
 * @property-read string   $referer
 * @property-read string   $origin
 * @property-read DateTime $time
 *
 * @version 0.1.0
 *
 * @since   0.1.0
 */
class Request extends ZenCoreApp\Input\Input implements ZenWebApp\IRequest
{
    /**
     * 请求协议。
     *
     * @var string
     */
    protected $protocol;

    /**
     * 请求主机名称。
     *
     * @var string
     */
    protected $host;

    /**
     * 请求目标端口。
     *
     * @var int
     */
    protected $port;

    /**
     * 请求资源路径。
     *
     * @var string
     */
    protected $path;

    /**
     * 请求查询信息。
     *
     * @var string
     */
    protected $search;

    /**
     * 请求来源。
     *
     * @var string
     */
    protected $referer;

    /**
     * 请求源域。
     *
     * @var string
     */
    protected $origin;

    /**
     * 请求时间。
     *
     * @var DateTime
     */
    protected $time;

    /**
     * {@inheritdoc}
     */
    final public function __construct()
    {
        parent::__construct();
        $this->params['get'] = $_GET;
        if (isset($this->params['server']['HTTP_CONTENT_LENGTH']) &&
            $this->params['server']['HTTP_CONTENT_LENGTH'] &&
            empty($_POST)
        ) {
            if (!isset($this->params['server']['HTTP_CONTENT_TYPE'])) {
                parse_str(file_get_contents('php://input'), $_POST);
            } elseif (preg_match('#^application/json[ ;]?#', $this->params['server']['HTTP_CONTENT_TYPE'])) {
                $_POST = json_decode(file_get_contents('php://input'), true);
            }
            if (!$_POST) {
                $_POST = array();
            }
        }
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
     * @param string $abbr 命名空间缩写
     *
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
     * @param array $value 上传文件信息
     *
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

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getProtocol()
    {
        if (!$this->protocol) {
            $this->protocol = strtolower(preg_replace('#/.*$#', '', $this->params['server']['SERVER_PROTOCOL'])).':';
        }

        return $this->protocol;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getHost()
    {
        if (!$this->host) {
            $this->host = $this->params['server']['HTTP_HOST'];
        }

        return $this->host;
    }

    /**
     * {@inheritdoc}
     *
     * @return int
     */
    public function getPort()
    {
        if (!$this->port) {
            $this->port = (int) $this->params['server']['SERVER_PORT'];
        }

        return $this->port;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getPath()
    {
        if (!$this->path) {
            $this->path = preg_replace('#\?.*$#', '', $this->params['server']['REQUEST_URI']);
        }

        return $this->path;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getSearch()
    {
        if (!is_string($this->search)) {
            $this->search = '?'.$this->params['server']['QUERY_STRING'];
            if ('?' == $this->search) {
                $this->search = '';
            }
        }

        return $this->search;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getReferer()
    {
        if (!is_string($this->referer)) {
            $this->referer = (string) $this->params['server']['HTTP_REFERER'];
        }

        return $this->referer;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getOrigin()
    {
        if (!is_string($this->origin)) {
            $this->origin = (string) $this->params['server']['HTTP_ORIGIN'];
        }

        return $this->origin;
    }

    /**
     * 获取请求时间。
     *
     * @return ZenType\DateTime
     */
    public function getTime()
    {
        if (!$this->time) {
            $this->time = new ZenType\DateTime($this->params['server']['REQUEST_TIME']);
        }

        return $this->time;
    }
}
