<?php
	// echo "<pre>";
	header("Content-Type: text/html;charset=utf-8");
	$time_start = microtime(true);

	# 引入公共方法
	require './Common/common.php';
	# 自动加载
	spl_autoload_register(function ($class) {
	    $file = dirname(__DIR__).DIRECTORY_SEPARATOR. str_replace("\\","/", $class). '.php';
	    include $file;
	});

	# PDO数据库配置
	$config['db']['host']   = "192.168.5.130";
	$config['db']['user']   = "dev";
	$config['db']['pass']   = "123";
	$config['db']['dbname'] = "db_sycxh";

	# 其他常用配置
	$config['app_name'] 	= 'App';   	// App的目录名称
	$config['website'] 		= '';		// 网站地址
	$config['store'] 		= __DIR__. DIRECTORY_SEPARATOR . "Store"; // 文件仓库地址
	$config['template_dir'] = __DIR__. DIRECTORY_SEPARATOR . "templates"; // 文件仓库地址
	$config['compile_dir']  = __DIR__. DIRECTORY_SEPARATOR . "Runtime"; // 文件仓库地址
	
	$app = new \Dan\App(['setting'=>$config]);

	# 容器:这里用对象的目的主要是为了在任何地方都可以修改容器内容，尽管这里用‘=’赋值，但是却是引用传递
	# @TODO::PHP引用传递并不是完全的引用，是创建了一个真正对象的标识符，通过标识符来访问真正的对象
	$container = $app->getContainer();
	$container['db'] = function($c) {
	    $db = $c['setting']['db'];
	    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'] . ';charset=utf8', $db['user'], $db['pass']);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$model = new \Dan\Db\DbPdo($pdo);
		return $model;
	};
	$container['file'] = function($c) {
		$directory = $c['setting']['store'];
		$file = new \Dan\FileReader($directory);
		return $file;
	};
	$container['view'] = function($c) {
		$view = new \Dan\MyView($c['setting']);
		return $view;
	};
	$container['markdown'] = function($c) {
		$md = new \Dan\MarkDown\Parser();
		return $md;
	};
	$container['git'] = function($c) {
		$view = new \Dan\Git(__DIR__,'project', 'owner', 'root', 'lzjkwo@316508', '119.23.62.21', 'suffix', true);;
		return $view;
	};

	// print_r($container['db']->query('select * from tb_articles'));
	// $files  	= $container['file']->getFiles();
	// $status 	= $container['file']->createIndex($files);

	# 路由表配置
	// $app->map(['POST'], '/', function ($c){
	// 	echo '主页控制';
	// });
	$app->map(['GET'], '/', '\Active\MarkDown:html');
	$app->map(['GET'], '/book', '\Active\Book');
	$app->map(['GET'], '/markdown', '\Active\MarkDown:html');

	# 运行程序
	$app->run();

	# 程序运行时间
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	// print_r("<br><strong>程序执行时间:</strong> $time <strong>Seconds\n</strong>");
?>