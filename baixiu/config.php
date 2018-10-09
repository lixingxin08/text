<?php 
	
	//定义一些连接数据库的信息，比如服务器地址，数据库用户名密码和编码

	//像这种不经常变动的数据用常量来表示 ,php当中定义常量要用define
	
	//1.定义服务器地址
	define('DB_HOST','127.0.0.1');

	//2. 定义服务器的名称
	define('DB_USER','root');

	//3.定义服务器的密码
	define('DB_PASSWORD','123456');

	//4.定义要连接的某个数据库
	define('DB_NAME','baixiu');

	//5.定义数据库的编码格式
	define('DB_SET_CHARSET','utf8');

 ?>