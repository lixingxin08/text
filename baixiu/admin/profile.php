<?php 
  require '../functions.php';
  checkLogin();//判断是否登陆

  $user_id = $_SESSION['user_info']['id'];
  // print_r($user_id);
  // exit;
  $rows = query('SELECT * FROM users WHERE id = '.$user_id);
  // print_r($rows);
  // exit;
  $msg = '';
  if(!empty($_POST)){
    // print_r($_POST);
    // exit;

    $result = update('users',$_POST,$user_id);
    if($result){
      //刷新当前的页面
      header('location:/admin/profile.php');
    }else {
      $msg = '数据更新失败...';
    }
  }

  /**
   * 1. 跳到此页面后，首先先判断是否登陆
   * 2. 去查询当前用户的数据，渲染在当前页面上,一定要查询最新的数据
   */
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
      <div class="page-title">
        <h1>我的个人资料</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($msg)){ ?>
      <div class="alert alert-danger">
        <strong>错误！</strong>
        <?php echo $msg?>
      </div>
      <?php } ?>
      <form class="form-horizontal" action='./profile.php' method='post'>
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file" >
              <img  src="<?php echo isset($rows[0]['avatar'])?$rows[0]['avatar']:'/assets/img/default.png'?>">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="<?php echo $rows[0]['email']?>" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="slug" class="col-sm-3 control-label">别名</label>
          <div class="col-sm-6">
            <input id="slug" class="form-control" name="slug" type="type" value="<?php echo $rows[0]['slug']?>" placeholder="slug">
            <p class="help-block">https://zce.me/author/<strong>zce</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="<?php echo $rows[0]['nickname']?>" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" name="bio" class="form-control" placeholder="Bio" cols="30" rows="6"><?php echo $rows[0]['bio']?></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">更新</button>
            <a class="btn btn-link" href="password-reset.html">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include './inc/aside.php'?>

</body>
</html>
<script>
  //1. 给input标签注册事件，选择图片进行上传
  $('#avatar').on('change',function(){
    // alert(23);
     // for(var k in this){
     //  // console.log(k+"===="+this[k]);
     //  //files====[object FileList] 它是所有要上传文件的一个列表，里面有所有的上传文件
     //  console.log(this['files'])
     // } 
    // console.log(this['files']);
    //所有的文件要上传的话，必须转换成二进制
    // var date = new Date();
    // date.getFullYear()
    // date.getMon
    // console.log(this['files'][0]);
    var data = new FormData();
    data.append('avatar',this['files'][0]); //this.files[0]

      //1.创建一个异步对象 
      var xhr = new XMLHttpRequest(); //如果构造函数的括号中没有参数的话，括号 是可以省略的

      //2. 设置请求行
      xhr.open('post','/admin/upfile.php');

      //3. 发送数据
      xhr.send(data);

      //4.如果发送和响应都成功了，需要接收传回来的数据
      xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
          //接收传回来的图片路径，渲染在页面指定的图片上
          $('#avatar').next().attr('src',xhr.responseText);
        }
      }

      /**
       * 1. 给上传的input标签注册事件，选择上传的图片
       * 2. 将上传的图片转换成二进制
       * 3. 创建异步对象，发送数据
       * 4. 上传成功之后，根据返回来的数据，重新渲染头像
       */
  })

</script>
