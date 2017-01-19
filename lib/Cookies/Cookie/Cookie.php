<?php
/**
 * 定义 Web 应用程序的 HTTP COOKIE 单项组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Cookies\Cookie;

use DateTime;
use Exception;
use Zen\Core as ZenCore;
use Zen\Web\Application as ZenApp;

/**
 * Web 应用程序的 HTTP COOKIE 单项组件。
 *
 * @property-read string         $id
 * @property      mixed          $value
 * @property      DateTime|const $expire
 * @property      string         $path
 * @property      string         $domain
 *
 * @version 0.1.0
 *
 * @since 0.1.0
 */
class Cookie extends ZenCore\Component
{
    /**
     * 随会话过期。
     *
     * @var int
     */
    const SESSION = 2039;

    /**
     * 请求组件实例。
     *
     * @var ZenApp\IRequest
     */
    protected $request;

    /**
     * 导入时的原始状态。
     *
     * @var array
     */
    protected $state;

    /**
     * 键名。
     *
     * @var string
     */
    protected $id;

    /**
     * 获取键名。
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 真实待保存的值数据。
     *
     * @var string
     */
    protected $package;

    /**
     * 值数据。
     *
     * @var mixed
     */
    protected $value;

    /**
     * 获取值数据。
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * 设置值数据。
     *
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->package = $this->pack();
    }

    /**
     * 过期时间。
     *
     * @var DateTime|const
     */
    protected $expire;

    /**
     * 获取过期时间。
     *
     * @return DateTime|const
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * 设置过期时间。
     *
     * @param DateTime|int|string|const $expire
     */
    public function setExpire($expire)
    {
        if (!$expire instanceof DateTime && static::SESSION != $expire) {
            $expire = new ZenCore\Type\DateTime($expire);
        }
        $this->expire = $expire;
        $this->package = $this->pack();
    }

    /**
     * 生效路径。
     *
     * @var string
     */
    protected $path;

    /**
     * 获取生效路径。
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * 设置生效路径。
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = '/'.trim($path, '/');
        $this->package = $this->pack();
    }

    /**
     * 根域。
     *
     * @var string
     */
    protected $root;

    /**
     * 生效域。
     *
     * @var string
     */
    protected $domain;

    /**
     * 获取生效域。
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * 设置生效域。
     *
     * @param string $domain
     *
     * @throws ExIllegalDomain
     */
    public function setDomain($domain)
    {
        if ($domain != $this->root && '.'.$this->root != substr($domain, -strlen($this->root) - 1)) {
            throw new ExIllegalDomain($this->id, $domain);
        }
        $this->domain = trim($domain, '.');
        $this->package = $this->pack();
    }

    /**
     * 构造函数。
     *
     * @param string $id
     * @param ZenApp\IRequest 请求组件实例
     *
     * @throws ExIdRequired
     */
    public function __construct($id, ZenApp\IRequest $request)
    {
        $this->id = (string) $id;
        if (!$this->id) {
            throw new ExIdRequired();
        }
        $this->request = $request;
        $this->expire = static::SESSION;
        $this->path = '/';
        $this->root =
        $this->domain = implode('.', array_slice(explode('.', $this->request->getHost()), -2));
    }

    /**
     * 生成真实待保存的值数据。
     *
     * @return string
     */
    protected function pack()
    {
        if (null === $this->value || '' === $this->value) {
            return '';
        }
        $s_blob = "\x1";
        if (!is_int($this->expire) || static::SESSION != $this->expire) {
            if (!$this->expire->getTimestamp()) {
                return '';
            }
            $s_blob = pack('N', $this->expire->getTimestamp());
        }
        $s_part = substr($this->path, 1);
        $s_blob .= chr(strlen($s_part)).$s_part;
        $s_part = rtrim(substr($this->domain, 0, -strlen($this->root)), '.');
        $s_blob .= chr(strlen($s_part)).$s_part;
        $s_part = $this->encode(
            is_scalar($this->value) ?
                (string) $this->value :
                serialize($this->value)
        );
        $s_blob .= $s_part;
        $s_part = "\x0".gzdeflate($s_blob, 9);
        if (strlen($s_blob) > strlen($s_part)) {
            $s_blob = $s_part;
        }
        $s_blob = str_replace('+', '%2b', rtrim(base64_encode($s_blob), '='));
        if (4094 < strlen($this->id) + strlen($s_blob)) {
            throw new ExDataTooLong($this->id);
        }

        return $s_blob;
    }

    /**
     * 对数据编码。
     *
     * @param string $blob
     *
     * @return string
     */
    protected function encode($blob)
    {
        $s_ret = '';
        for ($ii = 0, $jj = strlen($blob); $ii < $jj; ++$ii) {
            $s_ret .= chr(0x81 ^ ord($blob[$ii]));
        }

        return $s_ret;
    }

    /**
     * 对数据解码。
     *
     * @param string $code
     *
     * @return string
     */
    protected function decode($code)
    {
        return $this->encode($code);
    }

    /**
     * 导出 Set-Cookie 条目.
     *
     * @return string[]
     */
    public function export()
    {
        $o_0 = new ZenCore\Type\DateTime(0);
        $o_1 = new ZenCore\Type\DateTime();
        $o_1->setTimestamp(1);
        if (!$this->package) {
            if (!$this->state) {
                return array();
            }

            return array($this->format('zen', $o_0, $this->path, $this->domain));
        }
        $a_cmds = array();
        if ($this->state) {
            $b_cond = $this->path == $this->state['path'] && $this->domain == $this->state['domain'];
            if (!$b_cond) {
                $a_cmds[] = $this->format('zen', $o_0, $this->state['path'], $this->state['domain']);
            } elseif ($this->value == $this->state['value'] && $this->expire == $this->state['expire']) {
                return array();
            }
        }
        $a_cmds[] = $this->format(
            $this->package,
            is_int($this->expire) && static::SESSION == $this->expire ?
                $o_1 :
                $this->expire,
            $this->path,
            $this->domain
        );

        return $a_cmds;
    }

    /**
     * 排版 Set-Cookie 条目.
     *
     * @param mixed    $value
     * @param DateTime $expire
     * @param string   $path
     * @param string   $domain
     *
     * @return string
     */
    protected function format($value, DateTime $expire, $path, $domain)
    {
        $a_parts = array($this->id.'='.$value);
        if (1 != $expire->getTimestamp()) {
            $a_parts[] = 'expire='.$expire->format(DATE_COOKIE);
            $a_parts[] = 'max-age='.max(0, $expire->getTimestamp() - time());
        }
        $a_parts[] = 'path='.$path;
        $a_parts[] = 'domain='.$domain;

        return implode('; ', $a_parts);
    }

    /**
     * 导入还原 COOKIE 项。
     *
     * @param string          $id
     * @param string          $package
     * @param ZenApp\IRequest $request
     *
     * @return self
     */
    public static function import($id, $package, ZenApp\IRequest $request)
    {
        $package = str_replace('%2b', '+', $package);
        $o_ret = new static($id, $request);
        $o_ret->package = $package;
        try {
            $s_raw = base64_decode($package, true);
            if (!$s_raw) {
                throw new Exception();
            }
            if ("\x0" == $s_raw[0]) {
                $s_raw = gzinflate(substr($s_raw, 1));
                if (!$s_raw) {
                    throw new Exception();
                }
            }
            if ("\x1" == $s_raw[0]) {
                $s_raw = substr($s_raw, 1);
            } else {
                $m_tmp = unpack('N', substr($s_raw, 0, 4));
                $o_ret->expire = new ZenCore\Type\DateTime($m_tmp[1]);
                $s_raw = substr($s_raw, 4);
            }
            $m_tmp = ord($s_raw[0]);
            if ($m_tmp) {
                $o_ret->path .= substr($s_raw, 1, $m_tmp);
            }
            $s_raw = substr($s_raw, 1 + $m_tmp);
            $m_tmp = ord($s_raw[0]);
            if ($m_tmp) {
                $o_ret->domain = substr($s_raw, 1, $m_tmp).'.'.$o_ret->root;
            }
            $s_raw = $o_ret->decode(substr($s_raw, 1 + $m_tmp));
            if (isset($s_raw[1]) && ':' == $s_raw[1]) {
                $m_tmp = @unserialize($s_raw);
                if (!$m_tmp) {
                    $m_tmp = $s_raw;
                }
            } else {
                $m_tmp = $s_raw;
            }
            $o_ret->value = $m_tmp;
        } catch (Exception $ee) {
            $o_ret->value = $package;
        }
        $o_ret->state = array(
            'value' => $o_ret->value,
            'expire' => $o_ret->expire,
            'path' => $o_ret->path,
            'domain' => $o_ret->domain,
        );

        return $o_ret;
    }
}
