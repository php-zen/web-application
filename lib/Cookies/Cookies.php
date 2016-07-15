<?php
/**
 * 定义 Web 应用程序的 HTTP COOKIE 组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2016 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Cookies;

use Zen\Core as ZenCore;
use Zen\Web\Application as ZenWebApp;

/**
 * Web 应用程序的 HTTP COOKIE 组件。
 *
 * @version 0.1.0
 *
 * @since   0.1.0
 */
class Cookies extends ZenCore\Component implements ZenWebApp\ICookies
{
    /**
     * COOKIE 集合。
     *
     * @var Cookie\Cookie[]
     */
    protected $data;

    /**
     * COOKIE 元信息表。
     *
     * @var array[]
     */
    protected $meta;

    /**
     * COOKIE 变更值集合。
     *
     * @var Cookie\Cookie[]
     */
    protected $diff;

    /**
     * 构造函数.
     */
    public function __construct()
    {
        $this->data = $_COOKIE;
        if (isset($this->data['_zcmt_'])) {
            $this->meta = json_decode($this->data['_zcmt_'], true);
        }
        if (!is_array($this->meta)) {
            $this->meta = array('' => crypt(mt_rand(), mt_rand()));
        }
        $this->diff = array();
    }

    /**
     * 检查 COOKIE 是否存在。
     *
     * @param scalar $offset 名称
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * 获取 COOKIE 值。
     *
     * @param scalar $offset 名称
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            if (!$this->data[$offset] instanceof Cookie\Cookie) {
                if (!isset($this->meta[$offset])) {
                    $this->data[$offset] = new Cookie\Cookie($this->data[$offset]);
                } else {
                    $this->data[$offset] = new Cookie\Cookie(
                        $this->decodeValue($offset, $this->data[$offset]),
                        $this->decodeExpiration($offset, $this->meta[$offset][0]),
                        $this->meta[$offset][1],
                        $this->meta[$offset][2],
                        $this->meta[$offset][3]
                    );
                }
            }

            return $this->data[$offset];
        }
    }

    /**
     * 解码值。
     *
     * @param string $name  名称
     * @param string $value 代码
     *
     * @return mixed
     */
    protected function decodeValue($name, $value)
    {
        if (isset($this->meta[$name])) {
            if (isset($this->meta['']) && 4 & $this->meta[$name][3]) {
                $value = base64_decode($value);
                if ($value) {
                    $i_lend = strlen($value);
                    $i_lens = strlen($this->meta['']);
                    $i_offs = $this->meta[$name][0]
                        ? ord($this->meta[$name][0][1]) % $i_lens
                        : 0;
                    for ($ii = 0; $ii < $i_lend; ++$ii) {
                        $jj = ($ii + $i_offs) % $i_lens;
                        $value[$ii] = chr(ord($value[$ii]) ^ ord($this->meta[''][$jj]));
                    }
                    $value = gzinflate($value);
                }
            }
            if (2 & $this->meta[$name][3]) {
                $value = @unserialize($value);
            }
        }

        return $value;
    }

    /**
     * 解码过期时间。
     *
     * @param string $name COOKIE 名
     * @param string $code 代码
     *
     * @return ZenCore\Type\DateTime
     *
     * @throws ExIllegalExpiration 当过期时间解码失败时
     */
    protected function decodeExpiration($name, $code)
    {
        if ($code) {
            $code = base64_decode($code);
            if (4 != strlen($code)) {
                throw new ExIllegalExpiration($name);
            }
            $a_fields = unpack('V', $code);

            return new ZenCore\Type\DateTime($a_fields[1]);
        }
    }

    /**
     * 添加或更改 COOKIE 。
     *
     * @param scalar $offset 名称
     * @param mixed  $value  新值
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Cookie\Cookie) {
            $value = is_scalar($value)
                ? new Cookie\Cookie($value)
                : new Cookie\SecretCookie($value);
        }
        $this->data[$offset] = $value;
        $this->diff[$offset] = $value;
        $this->meta[$offset] = array(
            $this->encodeExpiration($value->getExpiration()),
            $value->getPath(),
            $value->getDomain(),
            ($value instanceof Cookie\SecretCookie) << 2 | !is_scalar($value->getValue()) << 1 | $value->getSecure(),
        );
    }

    /**
     * 编码过期时间。
     *
     * @param ZenCore\Type\DateTime $expire 过期时间
     *
     * @return string
     */
    protected function encodeExpiration($expire)
    {
        return $expire
            ? rtrim(base64_encode(pack('V', $expire->getTimestamp())), '=')
            : '';
    }

    /**
     * 删除 COOKIE 。
     *
     * @param scalar $offset 名称
     *
     * @throws ExCookieMetaMissing 当 COOKIE 元信息丢失时
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            if (!isset($this->meta[$offset])) {
                throw new ExCookieMetaMissing($offset);
            }
            $this->diff[$offset] = new Cookie\Cookie(
                'deleted',
                'epoch',
                $this->meta[$offset][1],
                $this->meta[$offset][2],
                $this->meta[$offset][3]
            );
            unset($this->data[$offset], $this->meta[$offset]);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param ZenWebApp\IResponse $response
     *
     * @return self
     */
    public function save(ZenWebApp\IResponse $response)
    {
        /** @var $jj Cookie\Cookie **/
        foreach ($this->diff as $ii => $jj) {
            $a_cookie = array($ii.'='.$this->encodeValue($ii, $jj->getValue()));
            if (null !== $jj->getExpiration()) {
                $a_cookie[] = 'expire='.$jj->getExpiration()->format(DATE_COOKIE);
                $a_cookie[] = 'Max-age='.max(0, $jj->getExpiration()->getTimestamp() - time());
            }
            if ($jj->getPath()) {
                $a_cookie[] = 'path='.$jj->getPath();
            }
            if ($jj->getDomain()) {
                $a_cookie[] = 'domain='.$jj->getDomain();
            }
            if ($jj->getSecure()) {
                $a_cookie[] = 'secure';
            }
            $response->header('Set-Cookie', implode('; ', $a_cookie), true);
        }
        if (!empty($this->diff)) {
            if (empty($this->meta)) {
                $a_cookie = array('_zcmt_=deleted');
                $a_cookie[] = 'expire=Thu, 01-Jan-1970 00:00:00 GMT';
                $a_cookie[] = 'Max-age=0';
            } else {
                $a_cookie = array('_zcmt_='.$this->quote(json_encode($this->meta)));
                $a_cookie[] = 'expire=Thu, 31-Dec-2037 23:55:55 GMT';
            }
            $response->header('Set-Cookie', implode('; ', $a_cookie), true);
        }

        return $this;
    }

    /**
     * 编码值。
     *
     * @param string $name  名称。
     * @param mixed  $value 值。
     *
     * @return string
     *
     * @throws ExCookieTooLong 当 COOKIE 值过长时
     */
    protected function encodeValue($name, $value)
    {
        if (2 & $this->meta[$name][3]) {
            $value = serialize($value);
        }
        if (4 & $this->meta[$name][3]) {
            if (!isset($this->meta[''])) {
                $this->meta[''] = crypt(mt_rand(), mt_rand());
            }
            $value = gzdeflate($value, 9);
            $i_lend = strlen($value);
            $i_lens = strlen($this->meta['']);
            $i_offs = $this->meta[$name][0]
                ? ord($this->meta[$name][0][1]) % $i_lens
                : 0;
            for ($ii = 0; $ii < $i_lend; ++$ii) {
                $jj = ($ii + $i_offs) % $i_lens;
                $value[$ii] = chr(ord($value[$ii]) ^ ord($this->meta[''][$jj]));
            }
            $value = rtrim(base64_encode($value), '=');
        }
        $value = $this->quote($value);
        if (4094 < strlen($name.$value)) {
            throw new ExCookieTooLong($name);
        }

        return $value;
    }

    /**
     * 转义字符串以确保 COOKIE 读写成功。
     *
     * @param string $clob
     *
     * @return string
     */
    protected function quote($clob)
    {
        return str_replace(
            array(' ', '+', ';', "\r", "\n"),
            array('%20', '%2b', '%3b', '%0d', '%0a'),
            $clob
        );
    }
}
