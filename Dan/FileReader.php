<?php
namespace Dan;

class FileReader
{
    private $directory;
    public function __construct($directory) {
        $this->directory = $directory;
    }
    /**
     * 获取文件列表
     * @param  [type] $directory [文件仓库路径]
     * @return [type]            [所有文件列表]
     */
    public function getFiles($directory='') {
        if (empty($directory)) {
            $directory = $this->directory;
        }
        if($dir = opendir($directory)) {
            $tmp = Array();
            while($file = readdir($dir)) {
                if($file != "." && $file != ".." && $file[0] != '.') {
                    if(is_dir($directory . DIRECTORY_SEPARATOR . $file)) {
                        $tmp2 = self::getFiles($directory . DIRECTORY_SEPARATOR . $file);
                        if(is_array($tmp2)) {
                            $tmp[$directory][] = $tmp2;
                        }
                    } else {
                        $path = $directory . DIRECTORY_SEPARATOR . $file;
                        $uuid = md5($path);
                        $tmp[$directory][] = [
                            'uuid'  => $uuid, // 文件唯一标识
                            'file'  => $file,
                            'dire'  => $directory,
                            'path'  => $path,
                            'update'=> date('Y-m-d H:i', filemtime($path)),
                        ];
                    }
                }
            }
            closedir($dir);
            return $tmp;
        }
    }
    /**
     * 格式化文件列表
     * @param  [type] $path [文件仓库地址]
     * @param  [type] $data [需要格式化的文件列表数组]
     * @return [type]       [格式化之后的列表数组]
     */
    public function formatData($path, $data) {
        foreach ($data as $key => &$value) {
            // 处理文件名
            $value['title']  = iconv( "gbk","utf-8", $value['file']);
            $value['title']  = explode('.', $value['title']);
            if (count($value['title'])>1) {
                array_pop($value['title']);
            }
            $value['title'] = implode('.', $value['title']);
            // 处理标签
            // 原理::
            $path_base = iconv( "gbk","utf-8", $path);
            $path_base = explode(DIRECTORY_SEPARATOR, $path_base);
            
            $path_file = iconv( "gbk","utf-8", $value['dire']);
            $path_file = explode(DIRECTORY_SEPARATOR, $path_file);

            $tags = array_diff($path_file, $path_base);
            sort($tags);
            $value['tags'] = implode(',', $tags);
        }
        return $data;
    }

    /**
     * 创建索引
     * @param  [type] $data [文件集]
     * @return [type]       [description]
     */
    public function createIndex($data)
    {
        $ml = '';
        foreach ($data as $key => $value) {
            if (is_dir($key)) {
                $ml .= file_get_contents($key.DIRECTORY_SEPARATOR.'README.md');
            }else{
                debug($value);
                $ml .= self::createIndex($value);
            }
        }
        return $ml;
    }
}