<?php
/**
 * 定义当文件过大时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Request\File;

/**
 * 当文件过大时抛出地异常。
 *
 * @package    Zen\Web\Application
 * @version    0.1.0
 * @since      0.1.0
 *
 * @method void __construct(File $file, \Exception $prev = null) 构造函数
 */
final class ExFileTooLarge extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = '上传文件“%file$s”大小超过“%limit$s”限制。';

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @var string[]
     */
    protected static $contextSequence = array('file', 'limit');

    /**
     * {@inheritdoc}
     *
     * @return scalar[]
     */
    protected function format()
    {
        $a_ret = $this->context;
        if (isset($a_ret['file'])) {
            $a_ret['file'] = $a_ret['file']->name;
        }

        return $a_ret;
    }
}
