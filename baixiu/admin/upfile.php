<?php 
	
	require '../functions.php';
	//提交过来的文件用$_FILES;

	// print_r($_FILES);
	// exit;
	// php语言有一个特点，就是会将上传过来的图片文件先存在一个临时的文件夹内

	//判断有没有指定的文件uploads，如果没有的话，则要进行创建
	if(!file_exists("../uploads/")){ // file_exists()是用来判断文件夹是否存在
		mkdir('../uploads'); //  如果不存的话，则先创建文件夹
	}

	$time = time();

	// echo $_FILES['avatar']['name'];
	$ext = explode('.',$_FILES['avatar']['name']);
	// print_r($ext[1]);

	$path = "/uploads/".$time.".".$ext[1];
	// echo $path;

	move_uploaded_file($_FILES['avatar']['tmp_name'],"../".$path);
	//第一个参数是文件的临时路径，第二个参数是文件的指定路径及名称

	//把上传的图片路径存到数据库里面
	$arr = array('avatar'=>$path);
	session_start(); //用到session的时候，一定要开启session
	$id = $_SESSION['user_info']['id'];

	update('users',$arr,$id);

	echo $path;
	/**
	 * 1. $_FILES可以获取上传过来的图片文件
	 * 2. 上传过来的图片有可能和原文件夹中的图片重名
	 * 3. 为了保证不重名,需要对上传的图片进行重新命名
	 * 4. 根据上传的时间进行命名
	 * 5. 上传上来的图片名称就变成  时间戳+图片原来的后缀
	 * 6. 将图片从临时文件里面移动到指定的文件里面
	 * 7. 将现在的图片路径更新到数据库
	 * 8. 将图片在数据库中的路径发给前台一份,重新渲染
	 */
 ?>