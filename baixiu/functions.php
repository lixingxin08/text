<?php 

			require __DIR__ .'/config.php'; // D:\www\baixiu\config.php

			
			//0.判断用户是否登陆的函数
				function checkLogin(){
						session_start();
			    // print_r($_SESSION['user_info']);
			    if(!isset($_SESSION['user_info'])){
			        //如果不存在，说明 还没有登陆，应该跳转到登陆页面
			      header('location:/admin/login.php');
			      exit;
			    }
				}

			//1.定义了一个连接数据库的函数
			function connect(){
				 //1. 先连接数据库服务器  返回一个连接信息
	       $connect = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
	       //2.连接具体的数据库
	       mysqli_select_db($connect,DB_NAME);//

	       //3. 设置编码集
	       mysqli_set_charset($connect,DB_SET_CHARSET);//

	       return $connect;
			}

		//2.封装了一个查询数据信息的函数
		function query($sql){
			 //4.查询数据信息
       // select * from users where email ='';
       // $sql = "SELECT * FROM users where email = 'admin@baixiu.com'";
       // $sql = "SELECT * FROM users where email = '" . $email . "'";
				$connect = connect();
       $result = mysqli_query($connect,$sql);  //根据sql语句查询信息，返回结果集

        $rows =	fetch($result);

        return $rows;
       // return $result;
		}

		//3.获取查询结果集中的数据,
		function fetch($result){
			//存到数组当中
				$rows = array(); //定义一个数据
      	while($row =mysqli_fetch_assoc($result) ){
        	$rows[] = $row; //将结果集中的每一条数据添加到数组当中
      	}

      return $rows;//返回这个数组
		}

		//4.封装一个增加数据的函数
		// function insert($sql){  原来的插入数据的函数
		// 	 $connect = connect();
		// 		$result = mysqli_query($connect,$sql);
		// 	 return $result; //返回添加后的结果
		// }

		function insert($table,$arr){
			 $connect = connect();
			 // 想获取数组中的所有属性
			 $keys = array_keys($arr); 
			 $vals = array_values($arr);			
			 $sql = "INSERT INTO ".$table." (".implode(',',$keys).") VALUES ('".implode("','",$vals)."')";
			 // var_dump($sql);
			 // exit;
				$result = mysqli_query($connect,$sql);
			 return $result; //返回添加后的结果
		}

		//5.封装一个删除数据的函数
		function delete($sql){
			 // $connect = connect();
       // $result = mysqli_query($connect,$sql);
       // return $result;
       // return mysqli_query($connect,$sql);
       return mysqli_query(connect(),$sql);
		}

		//6. 封装一个更新数据的函数
		function update($table,$arr,$id){
				 $connect = connect();

				 $str = "";
          foreach($arr as $key => $val){
            // $str .= $key . "=" .
            $str .= $key ."='".$val."', ";
          }

          $str = substr($str,0,-2);
           $sql = "UPDATE ".$table." SET ". $str. " where id = ".$id;
          $result =  mysqli_query($connect,$sql);

          return $result;
		}
		//7.将数组转换成 json字符串的时候，保证汉字不被unicode编码转化
		 function json_encode_no_zh($arr) {
        $str = str_replace ( "\\/", "/", json_encode ( $arr ) );
        $search = "#\\\u([0-9a-f]+)#ie";
       
        if (strpos (strtoupper(PHP_OS), 'WIN' ) === false) {
          $replace = "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))";//LINUX
        } else {
          $replace = "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))";//WINDOWS
        }
       
        return preg_replace ( $search, $replace, $str );
      }
?>