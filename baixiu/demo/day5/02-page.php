<?php 
		
		$total = 1006;// 当前数据库里面有106条数据

		//每页显示7条数据
		$pageSize = 7;

		//计算出总的页数
		$pageCount = ceil($total / $pageSize) ;

		//获取当前页的编码
		$pageCurrent = isset($_GET['page'])?$_GET['page']:'1';
		// print_r($pageCount);
		// exit;
		//设置上一页
		$prevPage = $pageCurrent - 1;

		$prevPage = $prevPage <1? 1 : $prevPage;
		//设置下一页
		$nextPage = $pageCurrent + 1;

		$nextPage = $nextPage > $pageCount ?$pageCount:$nextPage;
		// $pages =range(1,10);
		$pages =range(1,$pageCount);
		// var_dump($pages);
		// exit;
 ?>




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>分页</title>
</head>
<body>
	<!-- <a href="#">第1页</a>
	<a href="#">第2页</a>
	<a href="#">第3页</a> -->
	<a href="./02-page.php?page=<?php echo $prevPage?>">上一页</a>
	<?php foreach($pages as $key => $val){?>
		<a href="./02-page.php?page=<?php echo $val?>">第<?php echo $val?>页</a>
	<?php }?>
	<a href="./02-page.php?page=<?php echo $nextPage?>">下一页</a>
</body>
</html>