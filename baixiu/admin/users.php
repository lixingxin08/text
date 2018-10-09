<?php 
    header('Content-type:text/html;charset=utf-8');
    require '../functions.php';
    //一定要注意文件的引入，是相对于当前的文件路径
    $msg = '';
    checkLogin();//判断用户是否登陆  这个函数当中就包含着上面判断登陆的代码
    $action = isset($_GET['action'])?$_GET['action']:'add';
     $id = isset($_GET['user_id'])?$_GET['user_id']:''; //获取url中的用户id
    $title = '添加新用户';
    $btnText = '添加';
    /**
     * 1. 验证用户是否登陆
     * 2. 跳转到这个页面之后，应该把当前页面的信息渲染出来
     */  
      $lists = query('SELECT * FROM users');
      
      //post提交过来的数据
     if(!empty($_POST)){ //接收post提交过来的数据      
         if($action =='add'){
             $_POST['status'] = 'unactivated';
            $result = insert('users',$_POST);
            if($result){
              header('location:/admin/users.php');//相当于刷新 当前的页面
            }else {
              $msg = '添加数据失败...';
            }
         }else if($action =='update'){
            //
            //更新数据   $_POST
            // print_r($_POST);
            // exit;
          $user_id = $_POST['id'];
          
          unset($_POST['id']);//删除掉这个id，因为更新的时候是不能更新id的，得根据id的条件去更新其它的字段值
          // echo $str;
          // exit;
          // $sql = "update users set ". "aa=bb,cc=dd"
         
          $result = update('users',$_POST,$user_id);
          // print_r($result);
          // exit;
          if($result ){
            header('location:/admin/users.php');
          }else {
            $msg = '更新数据失败...';
          }
         }else if($action=='deleteAll') {
            // echo '123';
            // exit;
            // $arr1 = $_POST['ids'];
            // // print_r($arr1);
            // // exit;
            // // $str = implode(',',$arr1);
            // $str = implode(',',$_POST['ids']);
            // echo $str;
            // exit;
            // $sql = "DELETE FROM users where id in (".$str.")";
            $sql = "DELETE FROM users where id in (".implode(',',$_POST['ids']).")";
           $result = delete($sql);

            header('Content-type:application/json');
           if($result) {
             //向前前台发送一条删除 成功的信息
             $arr = array('code'=>10000,'msg'=>'删除成功');
             echo json_encode($arr);//转换成字符串输出到前台
           }else {
              $arr = array('code'=>10001,'msg'=>'删除失败...');
             echo json_encode($arr);//转换成字符串输出到前台
           }
           exit;
         }


     }

     //isset() empty()
     //empty()主要侧重于判断之前声明的变量是否为空，如果没有声明的话，会报错的
     //isset()

     // if(isset($_GET['action'])){
        // $action = $_GET['action'];//获取url中的action值
       
        if($action =='edit'){
          //编辑的操作  需要查询数据，渲染在左侧表单当中  
            $sql = "SELECT * FROM users where id = ".$id;
            $rows = query($sql);
            $action = 'update';
            $title = '修改用户';
            $btnText = '更新';
          // print_r($row);
          // exit;
          }else if($action=='delete'){
          //删除  
          // $connect = connect();
          // $result = mysqli_query($connect,"DELETE FROM users where id = ".$id);
          // print_r($result);
          // exit;
          $result= delete("DELETE FROM users where id = ".$id);
          if($result){
            header('location:/admin/users.php');//刷新页面
          }else {
            $msg = '删除数据失败...';
          }
        }
     // }
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title><?php echo '这是用户界面'?></title>
  <?php include './inc/css.php'?>
  <?php include './inc/script.php'?>
</head>
<body>
 

  <div class="main">
    <?php include './inc/nav.php'?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>用户</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($msg)){ ?>
        <div class="alert alert-danger">
          <strong>错误！</strong>
          <?php echo $msg ?>
        </div> 
      <?php } ?>
      <div class="row">
        <div class="col-md-4">
          <form action="./users.php?action=<?php echo $action?>" method='post'>
            <h2><?php echo $title?></h2>
            <div class="form-group">
              <label for="email">邮箱</label>
              <?php if($action!='add'){ ?>
               <input type="hidden" name="id" value="<?php echo $rows[0]['id'] ?>"> 
              <?php } ?>
              <input id="email" class="form-control" value="<?php echo isset($rows[0]['email'])?$rows[0]['email']:'' ?>" name="email" type="email" placeholder="邮箱">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" value="<?php echo isset($rows[0]['slug'])?$rows[0]['slug']:'' ?>" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" value="<?php echo isset($rows[0]['nickname'])?$rows[0]['nickname']:'' ?>" class="form-control" name="nickname" type="text" placeholder="昵称">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" value="<?php echo isset($rows[0]['password'])?$rows[0]['password']:'' ?>" class="form-control" name="password" type="text" placeholder="密码">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit"><?php echo $btnText?></button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm deleteAll" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <th class="text-center" width="40">
                  <input type="checkbox" class="toggleChk">
                </th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($lists as $key=>$val){ ?>
                <tr>
                  <td class="text-center">
                    <input type="checkbox" class="chk" value="<?php echo $val['id']?>">
                  </td>
                  <td class="text-center"><img class="avatar" src="../assets/img/default.png"></td>
                  <td><?php echo $val['email']?></td>
                  <td><?php echo $val['slug']?></td>
                  <td><?php echo $val['nickname']?></td>
                  <?php if($val['status']=='activated'){ ?>
                  <td>激活</td>
                  <?php }else if($val['status']=='unactivated'){ ?>
                  <td>未激活</td>
                  <?php }else if($val['status']=='forbidden'){ ?>
                  <td>禁用</td>
                  <?php }else {?>
                  <td>删除</td>
                  <?php }?>
                  <td class="text-center">
                    <a href="/admin/users.php?action=edit&user_id=<?php echo $val['id']?>" class="btn btn-default btn-xs">编辑</a>
                    <a href="/admin/users.php?action=delete&user_id=<?php echo $val['id']?>" class="btn btn-danger btn-xs">删除</a>
                  </td>
                </tr>
             <?php }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php include './inc/aside.php'?>
</body>
</html>
<script>
  //1.当单击总的小方块的时候，让要下面的的小方块都选中
  $('.toggleChk').on('click',function(){
    // 在jquery的事件当中，this表示DOM对象
      // if($(this).prop('checked')){

      // }

      if(this.checked){
        $('.chk').prop('checked',true); // checked selected disabled
        $('.deleteAll').show();
      }else {
        $('.chk').prop('checked',false);  
        $('.deleteAll').hide();
      }
  })
  //2.给小按钮注册事件，当一个或多个被选中的时候，也要让批量删除的按钮显示
  $('.chk').on('click',function(){
    var size = $('.chk:checked').size();
    if(size>0){
      $('.deleteAll').show();
      return;
      //1. 如果函数中有数据需要返回的话，得需要使用return关键字，将数据返回后，跳出当前函数，return关键字后面的代码不会执行
      //2. 如果函数中没有数据要返回，但是也使用了return关键字,就表示直接跳出当前函数，return关键字后面的代码也不会执行
      //3. 也就是说，在函数中只要使用了return关键字后，不管有没有返回数据，最终都会跳出当前的函数，return关键字后面的代码不会执行。
    }

     $('.deleteAll').hide();
  })
  //3.给批量删除按钮注册事件，批量的删除数据
  $('.deleteAll').on('click',function(){
      //1.获取所有被选中的小按钮的id,存到数组当中
      var ids = [];
      $('.chk:checked').each(function(){
          ids.push($(this).val());
      })

     
      //2.发送ajax请求到后台接口
      $.ajax({
        url:'/admin/users.php?action=deleteAll', //虽然是post的提交，但是仍然可以在 url中拼接参数，只要是URL中的参数，都可以在后端通过$_GET的方式来获取
        type:'post',
        data:{ids:ids},
        success:function(info){
          // console.log(typeof info);
          if(info.code == 10000){
            location.reload(true);
          }else {
            alert('删除失败...');
          }
        }
      })


      /**
       * 1. 当单击删除的时候,要获取所有的小按钮的id id在原来拼接模板的时候，对象($val)中就有,直接添加上就可以了
       * 2. 发送ajax请求
       */
  })
  
</script>
