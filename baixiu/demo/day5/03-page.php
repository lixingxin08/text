<?php 
		
		$total = 106;// 当前数据库里面有106条数据

		//每页显示7条数据
		$pageSize = 9;

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

		$nextPage = $nextPage > $pageCount ? $pageCount:$nextPage;

		//设置当前显示的每页的编码个数

		$pageLimit = 7;  //1 2 3 4 5 6 7     5 6 7  8 9 10 11    19  20  21 22 23 24 25

		//1 2 3 4 5 6 7	8 9						limit 0, 9,   (当前页-1)*$pageSize
		//10 11 12 13 14 15 16 17 18				9, 9
		//19 20 21 22 23 24 25 26 27				18,9 

		$start = $pageCurrent - floor($pageLimit / 2);
		$start = $start < 1? 1 :$start;

		$end = $start + $pageLimit - 1 ;

		// $end = $end > $pageCount ?$pageCount :$end;
		if($end > $pageCount ){
			$end = $pageCount;
			$start = $end - $pageLimit + 1; // 开始页面要重新计算
		}
		// $pages =range(1,10);
		// $pages =range(1,$pageCount);
		$pages =range($start,$end);
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
	<a href="./03-page.php?page=<?php echo $prevPage?>">上一页</a>
	<?php foreach($pages as $key => $val){?>
		<a href="./03-page.php?page=<?php echo $val?>">第<?php echo $val?>页</a>
	<?php }?>
	<a href="./03-page.php?page=<?php echo $nextPage?>">下一页</a>
</body>
</html>