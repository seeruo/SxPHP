<?php
namespace Dan;

class Router
{
    protected $routes = [];
    protected $ci;
    public function __construct($ci) {
        $this->ci = $ci;
    }
    public function map($methods, $rule, $callable)
    {
        $this->routes[$rule] = [
            'callable' => $callable,
            'methods'  => $methods,
        ];
    }
    public function get($rule)
    {
        if (empty($rule)) {
            die('缺少路由规则');
        }
        if (isset($this->routes[$rule])) {
            return $this->routes[$rule];
        }
        die('没有找到对应的路由规则');
    }
    public function router()
    {
        if (!isset($_SERVER['PATH_INFO'])) {
            $_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'];
        }
        $path = $_SERVER['PATH_INFO'];

        // 如果请求的是markdown文件
        if (substr($path, -3, 3) === '.md') {
            $path = '/markdown';    // 路由规则
        }

        // 根据路由规则, 获取路由规则具体内容
        $rule = $this->get($path);
        $callable = $rule['callable'];
        $methods  = $rule['methods'];

        // 如果是函数，执行函数
        if(is_callable($callable)){
            $callable($this->ci);
            return;
        }

        // 控制器解析
        $params = explode('\\', trim($callable, '\\'));

        // 控制器入口
        $active = empty($params[0]) ? 'Active' : $params[0];
        // 控制器及方法
        $class_method = empty($params[1]) ? 'Index:index' : $params[1];
        $class_array  = explode(':', trim($class_method));
        $class  = $class_array[0];
        
        // 控制器 @TODO::控制器是一个CLASS,需要先实例化
        $app_name = $this->ci->get('setting')['app_name'];
        $file = dirname(__DIR__) .DIRECTORY_SEPARATOR. $app_name . '\\' . $active . '\\' . $class.'.php';
        if (is_file($file)) {
            $controller = $app_name . '\\' . $active . '\\' . $class;
            $Object = new $controller($this->ci);
            # 请求对应的方法:: 参数准备
            $request  = new \Dan\Request($rule);
            $response = new \Dan\Response();
            # 请求对应的方法:: 如果没有设置需要请求的函数, 则尝试以调用函数的方式调用对象, 对象中的__invoke方法会被自动调用
            if (empty($class_array[1])) {
                $Object($request, $response);
            }else{
                $fun = $class_array[1];
                $Object->$fun($request, $response);
            }
        }else{
            die('没有找到控制器');
        }
    }
}
