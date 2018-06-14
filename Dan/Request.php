<?php
namespace Dan;

class Request
{
    protected $server;
    protected $rule;
    public function __construct($rule) {
        $this->server = $_SERVER;
        $this->rule = $rule;
        $this->checkMethod();
    }
    public function get($offset='')
    {
        return isset($this->server[$offset]) ? $this->server[$offset] : null;
    }
    protected function checkMethod()
    {
        $methods = $this->rule['methods'];
        if (!isset($methods) || empty($methods)) {
            die('路由规则的请求方式没有设置');
        }
        if ( is_array($methods) ) {
            $methods = implode(',', $methods);
        }
        $request_method = $this->get('REQUEST_METHOD');
        if ( strpos($request_method, $methods) === false) {
            die('请求方式错误,只接受:'.$methods);
        }
    }
}
