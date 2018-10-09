<div class="aside">
    <div class="profile">
      <img class="avatar" src="<?php echo $_SESSION['user_info']['avatar']?>">
      <h3 class="name"><?php echo $_SESSION['user_info']['nickname']?></h3>
    </div>
    <ul class="nav">
      <li class="active">
        <a href="index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
      </li>
      <li>
        <a href="#menu-posts" class="collapsed" data-toggle="collapse">
          <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-posts" class="collapse">
          <li><a href="/admin/posts.php">所有文章</a></li>
          <li><a href="/admin/post-add.php">写文章</a></li>
          <li><a href="/admin/categories.php">分类目录</a></li>
        </ul>
      </li>
      <li>
        <a href="comments.html"><i class="fa fa-comments"></i>评论</a>
      </li>
      <li>
        <a href="users.php"><i class="fa fa-users"></i>用户</a>
      </li>
      <li>
        <a href="#menu-settings" class="collapsed" data-toggle="collapse">
          <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse">
          <li><a href="/admin/nav-menus.php">导航菜单</a></li>
          <li><a href="/admin/slides.php">图片轮播</a></li>
          <li><a href="/admin/settings.php">网站设置</a></li>
        </ul>
      </li>
    </ul>
  </div>