<?php
    
    header('Content-type:text/html;charset=utf-8');
    //接收用户提交过来的数据
    // $_POST
    $msg = '' ;//定义了一个提示信息的变量
    if(!empty($_POST)){  //说明$_POST这个数组有内容，不为空，是post过来的数据
      $email = $_POST['email'];
      $password = $_POST['password'];
      
      //接收到用户提交过来的数据之后，要到数据库中先查询一下有无此用户名
      //1. 先连接数据库服务器  返回一个连接信息
       $connect = mysqli_connect('localhost','root','123456');

       //2.连接具体的数据库
       mysqli_select_db($connect,'baixiu');//

       //3. 设置编码集
       mysqli_set_charset($connect,'utf8');//

       //4.查询数据信息
       // select * from users where email ='';
       // $sql = "SELECT * FROM users where email = 'admin@baixiu.com'";
       $sql = "SELECT * FROM users where email = '" . $email . "'";
       $result = mysqli_query($connect,$sql);  //根据sql语句查询信息，返回结果集


      // print_r($result);
      // exit;  //
       $row = mysqli_fetch_assoc($result);//从查询的结果集中，先获取第一条数据

       // print_r($row);
       // exit;
       if(!empty($row)){
          //此处说明用户名是已经真实存在的了
         // 要去判断密码了
        if($row['password'] == $password ){
          // echo '用户名和密码正确，登陆成功。。。';
          // exit;
          session_start();//使用session之前一定要先启用session
          $_SESSION['user_info'] = $row; //把用户的登陆信息存到session当中,随响应头发送给浏览器，存到浏览器的cookie当中
          header('location:/admin');  //php中页面跳转
          exit;
          // window.location.href= 'admin/index.html';  这是js下面的页面跳转
        }else {
          //到了这一步的时候，说明用户名是对的，但是密码错了
          $msg = '用户名或是密码错误...';
        }
         
       }else {
          //说明 用户名是不存在的
        $msg = '用户名不存在...';
       }

    
    }
 ?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <?php include './inc/css.php'?>
</head>
<body>
  <div class="login">
    <form class="login-wrap" action="./login.php" method="post">
      <img class="avatar" src="../assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if(!empty($msg)){ ?>
         <div class="alert alert-danger">
            <strong>错误！</strong> <?php echo $msg?>
          </div> 
      <?php } ?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" value="admin@baixiu.com" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password" value="123456" type="password" class="form-control" placeholder="密码">
      </div>
      <input type="submit" class="btn btn-primary btn-block" value="登陆">
    </form>
  </div>
</body>
</html>
