<?php

namespace Dan;
 
//Git操作类
class Git 
{
    private $_path;
    private $_projectName;
    private $_owner;
    private $_username;
    private $_password;
    private $_host;
    private $_suffix = 'git';
    private $_isHttps = false;
     
    private $_url = '';
     
    private $_log;
    private $_output;
     
    private $_descriptorspec;
     
    /**
     * 构造函数
     *
     * 设置git参数
     *
     * @param string $path 项目本地路径
     * @param string $projectName 项目名
     * @param string $owner 拥有者
     * @param string $username 帐号用户名
     * @param string $password 帐号密码
     * @param string $host 项目所在地址
     * @param string $suffix 项目后缀
     * @param boolen $isHttps 项目协议是否为https
     * @param string $log 访问日志文件路径
     * @param string $output 命令日志文件路径
     * @return void
     */
    public function __construct($path = __DIR__, $projectName, $owner, $username, $password, $host, $suffix = false, $isHttps = false, $log = false, $output = false) {
        $configFields = array('path', 'projectName', 'owner', 'username', 'password', 'host');
 
        //设置项目信息
        foreach($configFields as $field) {
            if(empty($$field)) {
                throw new Exception("{$field} is empty!");
                return false;
            }
            $this->{'_' . $field} = $$field;
        }
         
        //设置路径协议
        if($isHttps) {
            $this->_isHttps = true;
        }
         
        //设置项目后缀
        if($suffix) {
            $this->_suffix = $suffix;
        }
         
        //设置log地址
        if($log) {
            $this->_log = $log;
        } else {
            $this->_log = __DIR__ . '/git_log.txt';
        }
         
        //设置git命令返回记录文件地址
        if($output) {
            $this->_output = $output;
        } else {
            $this->_output = __DIR__ . '/git_output.txt';
        }
         
        //设置git命令参数
        $this->_descriptorspec = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('file', $this->_output, 'a')
        );
         
        //生成远程url
        $this->_createUrl();
    }
     
    /**
     * pull方法
     *
     * 从服务器中pull到本地
     * 
     * @param string $branch 默认为 origin master
     * @return boolen
     */
    public function pull($branch = 'origin master') {
        $cmd = 'git pull ' . $this->_url . ' ' . $branch;
        return $this->_runCmd($cmd);
    }
     
    public function fetch() {
         
    }
     
    public function push() {

    }
     
    public function gclone() {
	    $cmd = 'git clone http://'.urlencode($this->_username).':'.urlencode($this->_password).'@'.$this->_host;
	    return $this->_runCmd($cmd);
    }
    public function init() {
        // $cmd = 'D:\Git\cmd\git.exe init';
        $cmd = 'git init';
        return $this->_runCmd($cmd);
    }
     
    /*
     * 生成git地址url
     *
     * git地址格式 [https://][username]:[password]@[host]/[owner]/[projectName].[suffix]
     *
     * @reutrn void
     */
    private function _createUrl() {
        $url = '';
         
        //添加协议
        if($this->_isHttps) {
            $url .= 'https://';
        } else {
            $url .= 'http://';
        }
         
        //添加用户名密码
        $url .= urlencode($this->_username) . ':' . urlencode($this->_password);
 
        //添加地址
        $url .= '@' . $this->_host . '/';
 
        //添加项目地址
        $url .= $this->_owner . '/' . $this->_projectName;
 
        //添加项目后缀
        $url .= '.' . $this->_suffix;
 
        $this->_url = $url;
    }
    
    /*
     * log方法
     *
     * 记录访问日志
     *
     * @param string $msg 日志
     * @return void
     */
    private function _writeLog($msg) {
        $log = date('Y-m-d H:i:s') . ":\n";
        $log .= $msg;
        $log .= "-------------------------------------\n";

        file_put_contents($this->_log, $log, FILE_APPEND | LOCK_EX);
    }

    /*
     * 运行命令行
     *
     * 该方法可以运行命令行, 并会自动记录命令行日志
     *
     * @param string $cmd 要运行的命令行
     * @return boolen
     */
    private function _runCmd($cmd) {
        file_put_contents($this->_output, date('Y-m-d H:i:s') . ":\n", FILE_APPEND);
        debug(getenv('path'));
        $process = proc_open($cmd, $this->_descriptorspec, $pipes, $this->_path, ['path'=>getenv('path')]);
        if(is_resource($process)) {
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
        } else {
            $output = 'no command';
        }
         
        $returnValue = proc_close($process);
        
        file_put_contents($this->_output, "---------------------------------\n", FILE_APPEND);

        
        if($returnValue) {
            $this->_writeLog('Command faild.' . "\n");
            return false;
        } else {
            $this->_writeLog('Command success: ' . $output);
            return true;
        }
    }
}
 
// try {
//     $git = new Git(__DIR__, 'project', 'owner', 'username', 'password', 'host', 'suffix', true);
// } catch(Exception $e) {
//     echo $e->getMessage();
//     die;
// }
// $git->pull('test');
// 
/**
* 
*/