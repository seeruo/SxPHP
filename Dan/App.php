<?php
namespace Dan;

use Dan\Container;

class App
{
	private $container;
    public function __construct($container = [])
    {
        if (is_array($container)) {
            $container = new Container($container);
        }
        $this->container = $container;
        $this->setDefaultServer();
    }
    public function getContainer()
    {
        return $this->container;
    }
    // 初始必须服务项目
    public function setDefaultServer()
    {
        // 路由
        $this->container['router'] = function ($c){
           $ss = new \Dan\Router($this->container);
           return $ss;
        };
    }
    /**
     * 初始路由表
     */
    public function map($methods, $path, $callable)
    {
        // 参数转大写
        $methods = array_map("strtoupper", $methods);
        $router = $this->container->get('router');
        $router->map($methods, $path, $callable);
    }
    public function run()
    {
        $res = $this->container->get('router')->router();

        // print_r("\n\n<strong>程序执行结束!!!</strong>\n");
    }
    // public function assign($tpl_var, $value = null)
    // {
    //     $this->container->get('view')->assign($tpl_var, $value);
    // }

    // public function display($fileName)
    // {
    //     $this->container->get('view')->display($fileName);
    // }
}
