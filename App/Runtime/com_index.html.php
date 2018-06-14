<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="/Static/reset.css">
	<link rel="stylesheet" type="text/css" href="/Static/style.css">
</head>
<body>
	<div id="app">
		<div class="book-left">
			<div id="book-search-input" role="search">
			    <input type="text" placeholder="输入并搜索">
			</div>
			<nav>
				<?php echo $this->tpl_vars["menu"]; ?>
				<!-- <ul class="summary">
				    <li class="chapter " data-level="1.3.1" data-path="../java/base.html">
				        <a href="../java/base.html">基础</a>
				    </li>
				    <li class="chapter " data-level="1.3.1" data-path="../java/base.html">
				        <a href="../java/base.html">基础</a>
				    </li>
				    <li class="chapter " data-level="1.3.1" data-path="../java/base.html">
				        <a href="../java/base.html">基础</a>
						<ul class="articles">
						    <li class="chapter " data-level="1.3.1" data-path="../java/base.html">
						        <a href="../java/base.html">基础</a>
						    </li>
						    <li class="chapter " data-level="1.3.1" data-path="../java/base.html">
						        <a href="../java/base.html">基础</a>
						    </li>
						    <li class="chapter " data-level="1.3.1" data-path="../java/base.html">
						        <a href="../java/base.html">基础</a>
						        <ul></ul>
						    </li>
						</ul>
				    </li>
				</ul> -->
			</nav>
		</div>
		<div class="book-right ">
			<div class="markdown-body">
				<?php echo $this->tpl_vars["content"]; ?>
			</div>
		</div>
	</div>
	<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">
    // (function (argument) {
    //     $.get('http://localhost/WORKSTATION/zblogapi/App/DataCenter/menu', function (d) {
    //         $('#error_msg').text(d.msg);
    //         if (d.status) {
    //         	console.log(d);
    //         }
    //     }, 'json')
    // })()
    </script>
</body>
</html>