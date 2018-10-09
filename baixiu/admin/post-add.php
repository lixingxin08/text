<?php 
    /**
     * 思路：
     * 1. 跳转到当前页面的时候，要检测是否登陆
     * 2. 跳转过来之后，要显示当前页面的数据
     * 3. 做上传缩略图 
     * 4. 要将表单内的所有的内容提交给服务器，存到数据库里面
     *       注意，当前表单里面的name属性值一定要和数据库里面的那些字段一致
     * 5. 上传成功之后要跳转到所有文章的页面   
     *
     * 6. 当在posts.php中单击编辑按钮的时候,要跳转到post-add.php这个页面
     * 7. 查询数据库,渲染当前页面
     * 8. 获取form表单中的信息，提交给服务器
     */


    require '../functions.php';
    checkLogin();//判断是否登陆

    $lists = query('SELECT * FROM categories');
    $action = isset($_GET['action'])?$_GET['action']:'add';
    $msg = '';
    $title = '写文章';
    $btnText = '添加';
    
    //1. 接收用户传过来的图片文件
    if($action == 'upfile'){
      //判断有没有指定的文件夹，如果没有就创建
      if(!file_exists('../uploads')){
          mkdir('../uploads');
      }

      $fileName = time(); //生成一个根据时间的图片名称
       $ext = explode('.',$_FILES['feature']['name']); //获取原图片文件的后缀名称

       $path = '/uploads/'.$fileName . '.' .$ext[1]; //拼接路径

       move_uploaded_file($_FILES['feature']['tmp_name'],'..'.$path); //将临时文件夹中的文件移动到指定的目录里面

       echo $path; //把图片文件的路径发送给前台
       exit;  //一定要加上这句代码，要不然前台接收的字符串多了就乱了
    }

    //2.接收用户post的方式提交过来的表单数据
    if(!empty($_POST)){
      // print_r($_POST);
      // exit;
      if($action =='add'){
        $result = insert('posts',$_POST);
        if($result){
          header('location:/admin/posts.php');
        }else {
          $msg = '添加文章失败...';
        }
      }else if($action =='update'){
        // print_r($_POST);
        // exit;
        $pid = $_POST['id'];
        unset($_POST['id']);
        $result = update('posts',$_POST,$pid);
        if($result){
          header('location:/admin/posts.php');
        }else {
          $msg = '更新失败...';
        }
      }
    }

    //3.如果是编辑操作
    if($action == 'edit'){
      $pid = $_GET['pid'];
      $rows = query('SELECT * FROM posts where id = '.$pid);
      $rows[0]['created'] = str_replace(' ','T',$rows[0]['created']);
      // print_r($rows);
      // exit;
      $title = '编辑文章';
      $btnText = '更新';
      $action = 'update';

    }
 ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <?php include './inc/css.php'?>
  <script src="/assets/vendors/ueditor/ueditor.config.js"></script>
  <script src="/assets/vendors/ueditor/ueditor.all.js"></script>
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php'?>
    <div class="container-fluid">
      <div class="page-title">
        <h1><?php echo $title?></h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if($msg){?>
        <div class="alert alert-danger">
          <strong>错误！</strong>
          <?php echo $msg?>
        </div>
        <?php }?>
      <form class="row" action='./post-add.php?action=<?php echo $action?>' method = 'post'>
        <?php if(isset($rows[0])){ ?>
          <input type="hidden" name="id" value="<?php echo $rows[0]['id']?>">
        <?php }?>
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" value="<?php echo isset($rows[0]['title'])?$rows[0]['title']:''?>" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_info']['id']?>">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea id="content"   name="content" cols="30" rows="10" placeholder="内容"><?php echo isset($rows[0]['content'])?$rows[0]['content']:''?></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" value="<?php echo isset($rows[0]['slug'])?$rows[0]['slug']:''?>" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <?php if(isset($rows[0]['feature'])){?>
              <img class="help-block thumbnail" src="<?php echo $rows[0]['feature']?>">
              <?php }else{ ?>
              <img class="help-block thumbnail" style="display: none">
            <?php }?>
            <input id="feature" class="form-control"   type="file">
            <input type="hidden" name="feature" id="thumb" >
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category_id">
            <?php foreach($lists as $key => $vals){ ?>
                <option <?php if(isset($rows[0])&&$rows[0]['category_id']==$vals['id']){?> selected <?php }?> value="<?php echo $vals['id']?>"><?php echo $vals['name']?></option>
              <?php }?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" value="<?php echo isset($rows[0]['created'])?$rows[0]['created']:''?>" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option <?php if(isset($rows[0])&&$rows[0]['status']=='drafted'){?> selected <?php }?> value="drafted">草稿</option>
              <option <?php if(isset($rows[0])&&$rows[0]['status']=='published'){?> selected <?php }?> value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit"><?php echo $btnText ?></button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include './inc/aside.php'?>

  <?php include './inc/script.php'?>
</body>
</html>
<script>
  UE.getEditor('content',{
    initialFrameHeight:300 //设置原始的高度
  });

  //1. 给file的input的标签注册事件
  $('#feature').on('change',function(){
    var data = new FormData();
    data.append('feature',this.files[0]); //将上传的图片文件转换成二进制

    //1. 创建异步对象 
     var xhr = new XMLHttpRequest();

     //2.设置请求行
     xhr.open('post','/admin/post-add.php?action=upfile');

     //3.发送数据
     xhr.send(data)   ;

     //4. 上传成功之后
     xhr.onreadystatechange = function(){
        if(xhr.readyState ==4 && xhr.status == 200){
          $(".thumbnail").attr('src',xhr.responseText).show();
          //还要把图片在服务器中的路径存在表单的一个文本框内，跟随表单的所有的内容一块提交给服务器
          $('#thumb').val(xhr.responseText);
        }
     }
  })
</script>
