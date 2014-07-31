<?php
/**
 * 定义 Web 应用程序的上传文件信息组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Web\Application\Request\File;

use Zen\Core as ZenCore;
use Zen\Web\Application;

/**
 * Web 应用程序的上传文件信息组件。
 *
 * @package Zen\Web\Application
 * @version 0.1.0
 * @since   0.1.0
 *
 * @property-read string $name 文件名
 * @property-read string $type 文件类型
 * @property-read int    $size 文件大小
 */
class File extends ZenCore\Component
{
    /**
     * 文件名。
     *
     * @internal
     *
     * @var string
     */
    protected $name;

    /**
     * 类型。
     *
     * @internal
     *
     * @var string
     */
    protected $type;

    /**
     * 文件大小。
     *
     * @internal
     *
     * @var int
     */
    protected $size;

    /**
     * 临时文件路径。
     *
     * @internal
     *
     * @var string
     */
    protected $path;

    /**
     * 构造函数
     *
     * @param array                $value   $_FILES 结构数组
     * @param Application\IRequest $request 所属地 HTTP 请求组件实例
     *
     * @throws ExFileTooLarge 当文件过大时
     * @throws ExFileHalt     当上传被中止时
     * @throws ExFileBroken   当临时数据写入失败时
     */
    public function __construct($value, Application\IRequest $request)
    {
        $this->name = $value['name'];
        $this->type = $value['type'];
        $this->size = $value['size'];
        $this->path = $value['tmp_name'];
        switch ($value['error']) {
            case UPLOAD_ERR_INI_SIZE:
                throw new ExFileTooLarge($this, ini_get('upload_max_filesize'));
            case UPLOAD_ERR_FORM_SIZE:
                throw new ExFileTooLarge($this, $request['post:MAX_FILE_SIZE']);
            case UPLOAD_ERR_PARTIAL:
            case UPLOAD_ERR_EXTENSION:
                throw new ExFileHalt($this);
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
                throw new ExFileBroken($this);
            default:
        }
    }

    /**
     * 字符串类型转换。
     *
     * @return string
     */
    public function __toString()
    {
        return file_get_contents($this->path);
    }

    /**
     * 移动文件至指定位置。
     *
     * @param  string $directory 目标文件夹
     * @param  string $name      可选。新文件名
     * @return bool
     */
    public function move($directory, $name = '')
    {
        if (!$name) {
            $name = $this->name;
        }
        if (!is_dir($directory) && !@mkdir($directory, 0775, true)) {
            return false;
        }
        $p_file = $directory . '/' . $name;
        if (!move_uploaded_file($this->path, $p_file)) {
            return false;
        }
        @chmod($p_file, 0664);

        return true;
    }
}
