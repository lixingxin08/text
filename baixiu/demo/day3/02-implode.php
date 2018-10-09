<?php 
		header("Content-type:text/html;charset=utf-8");
		$arr = array('name'=>"张三",'age'=>20,'sex'=>"男",'score'=>100);

		$keys = array_keys($arr);
		$vals = array_values($arr);

		//echo 用于打印或是输出简单的数据  字符串 数字
		//print_r用来输出复杂数据类型  
		//var_dump  是用来输出数据的详细信息
		print_r($keys);
		echo '<br/>';
		print_r($vals);

	echo '<br/>';
		$str = implode(", ",$keys);
		echo $str;

	echo '<br/>';
		$val = implode("', '",$vals);
		// echo "'". implode("', '",$vals)."'";
		echo "'" . $val . "'";
		echo '<br/>';
		$sql = "insert into users (". $str .") values (' ". implode("', '",$vals) ." ') ";
		echo $sql;
 ?>