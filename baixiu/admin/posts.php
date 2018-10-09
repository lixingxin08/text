<?php 
  /**
   * 思路：
   * 1.检测用户是否已经登陆
   * 2.显示当前页面的内容
   * 3.当数据量很大的时候，要实现一个分布的处理
   * 4.当单击编辑按钮的时候，要跳转到post-add.php页面
   */
    
  require '../functions.php';
  checkLogin();

  // $total = 106;// 当前数据库里面有106条数据
    $total = query('SELECT count(*) AS total FROM posts');
    // print_r($total);
    // exit;
    $total = $total[0]['total'];

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

    //1 2 3 4 5 6 7 8 9           limit 0, 9,   (当前页-1)*$pageSize
    //10 11 12 13 14 15 16 17 18        9, 9
    //19 20 21 22 23 24 25 26 27        18,9 

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


    //设置当前页面中显示数据的起始编号
    $offset  = ($pageCurrent -1) * $pageSize;
  // $lists = query('SELECT * FROM posts');
  // $lists = query('SELECT * FROM posts LEFT JOIN users on posts.user_id = users.id LEFT JOIN categories on  posts.category_id = categories.id');
  $lists = query("SELECT posts.id,posts.title,posts.category_id,posts.created,posts.status,users.nickname,categories.name FROM posts LEFT JOIN users on posts.user_id = users.id LEFT JOIN categories on  posts.category_id = categories.id limit ".$offset.",".$pageSize.""); //精确查询,可解决覆盖的问题

  // print_r($lists);
  // exit;
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <?php include './inc/css.php'?>
</head>
<body>
  <div class="main">
    <?php include './inc/nav.php'?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="" class="form-control input-sm">
            <option value="">所有分类</option>
            <option value="">未分类</option>
          </select>
          <select name="" class="form-control input-sm">
            <option value="">所有状态</option>
            <option value="">草稿</option>
            <option value="">已发布</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="/admin/posts.php?page=<?php echo $prevPage?>">上一页</a></li>
          <?php foreach($pages as $key => $val){?>
              <?php if($pageCurrent == $val){ ?>
              <li class="active"><a href="/admin/posts.php?page=<?php echo $val?>"><?php echo $val?></a></li>
              <?php }else { ?>
              <li><a href="/admin/posts.php?page=<?php echo $val?>"><?php echo $val?></a></li>
              <?php }?>
          <?php }?>
          <li><a href="/admin/posts.php?page=<?php echo $nextPage?>">下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40">
             编号
            </th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($lists as $key => $vals){ ?>
          <tr>
            <td class="text-center">
              <?php echo $vals['id']?>
            </td>
            <td><?php echo $vals['title']?></td>
            <td><?php echo $vals['nickname']?></td>
            <?php if(empty($vals['name'])){ ?>
              <td>未分类</td>
            <?php }else { ?>
              <td><?php echo $vals['name']?></td>
            <?php }?>
            <td class="text-center"><?php echo $vals['created']?></td>
            <?php if($vals['status']=='published'){?>
              <td class="text-center">已发布</td>
            <?php }else{ ?>
              <td class="text-center">草稿</td>
            <?php }?>
            <td class="text-center">
              <a href="/admin/post-add.php?action=edit&pid=<?php echo $vals['id']?>" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr>
         <?php }?>
        </tbody>
      </table>
    </div>
  </div>
  <?php include './inc/aside.php'?>

  <?php include './inc/script.php'?>
</body>
</html>
