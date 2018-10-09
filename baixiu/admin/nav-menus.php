<?php 
  header('Content-type:text/html;charset=utf-8');
  require '../functions.php';
  checkLogin();
  // echo phpversion();
  // exit;
  /**
   * 思路：
   * 1. 检测是否登陆
   * 2. 查询数据，渲染页面
   * 3. 删除某条数据
   * 4. 添加新数据   
   * 5. 其实删除和添加都是操作的数据库中的同一条数据中的value的值
   */

  $json = query("SELECT `value` FROM options WHERE `key` = 'nav_menus'");
  // var_dump($json);
  // exit;
  $json = json_decode($json[0]['value'],true); //加第二个参数就可以将原来的字符串转换成数组
  // print_r($json);
  // exit;
  // '[{"icon":"fa fa-glass","text":"奇趣事","title":"奇趣事","link":"/category/funny"},{"icon":"fa fa-phone","text":"潮科技","title":"潮科技","link":"/category/tech"},{"icon":"fa fa-fire","text":"会生活","title":"会生活","link":"/category/living"},{"icon":"fa fa-gift","text":"美奇迹","title":"美奇迹","link":"/category/travel"}]'

  $action = isset($_GET['action'])?$_GET['action']:'add';
  //1.删除功能
  if($action =='delete'){
    $index = $_GET['index'];
    unset($json[$index]); //删除了数组中的某一项之后，代码会继续向下执行
    // print_r($json);
    // exit;
    // $data = json_encode($json ,json);
    $data = json_encode_no_zh($json); //apache版本5.4之前使用的方法

    // var_dump($data);
    // exit;
    // $arr = array('value'=>$data);
    // update('options',$arr,9);
    $result = update('options',array('value'=>$data),9);
    if($result){
      header('location:/admin/nav-menus.php');
      exit;
    }
  }

  //2.添加功能
  if(!empty($_POST)){
    // print_r($json);
    $json[] = $_POST; //将接收过来的数据，追加到数组里面
    // print_r($json);
    // exit;
    $data = json_encode_no_zh($json);
    // var_dump($data);
    // exit;
    $result = update('options',array('value'=>$data),9);
    if($result){
      header('location:/admin/nav-menus.php');
      exit;
    }
  }

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Navigation menus &laquo; Admin</title>
  <?php include './inc/css.php'?>
  
</head>
<body>

  <div class="main">
    <?php include './inc/nav.php'?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>导航菜单</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="row">
        <div class="col-md-4">
          <form action='/admin/nav-menus.php' method='post'>
            <h2>添加新导航链接</h2>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="title">标题</label>
              <input id="title" class="form-control" name="title" type="text" placeholder="标题">
            </div>
            <div class="form-group">
              <label for="icon">图标</label>
              <input id="icon" class="form-control" name="icon" type="text" placeholder="标题">
            </div>
            <div class="form-group">
              <label for="href">链接</label>
              <input id="href" class="form-control" name="link" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>文本</th>
                <th>标题</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($json as $key => $vals){ ?>
              <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td><i class="<?php echo $vals['icon']?>"></i><?php echo $vals['text']?></td>
                <td><?php echo $vals['title']?></td>
                <td><?php echo $vals['link']?></td>
                <td class="text-center">
                  <a href="/admin/nav-menus.php?action=delete&index=<?php echo $key?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <?php include './inc/aside.php'?>

  <?php include './inc/script.php'?>
</body>
</html>
