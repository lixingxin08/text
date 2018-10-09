<?php
    
    //判断用户是否登陆,只有登陆了才可以进行页面的访问
    //判断有没有session的信息 
    // session_start();
    // // print_r($_SESSION['user_info']);
    // if(!isset($_SESSION['user_info'])){
    //     //如果不存在，说明 还没有登陆，应该跳转到登陆页面
    //   header('location:/admin/login.php');
    //   exit;
    // }

      require '../functions.php';
      checkLogin();//检测用户是否登陆

      $lists = query('SELECT COUNT(*) as total FROM posts');
      // print_r($lists);
      // exit;
      $rows = query("SELECT COUNT(*) as total FROM posts where status = 'drafted'");
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
   <?php include './inc/css.php'?>
  <?php include './inc/script.php'?>
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php'?>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.html" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $lists[0]['total']?></strong>篇文章（<strong><?php echo $rows[0]['total']?></strong>篇草稿）</li>
              <li class="list-group-item"><strong>6</strong>个分类</li>
              <li class="list-group-item"><strong>5</strong>条评论（<strong>1</strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>

  <?php include './inc/aside.php' ?>

  
 
</body>
</html>
