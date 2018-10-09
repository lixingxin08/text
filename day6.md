# 1.导航中的轮播图

###  1.准备工作

- 1.将admin文件夹下的aside.html修改成aside.php，并修改公共样式

  ~~~php
  <?php include './inc/style.php'?>
  <?php include './inc/script.php'?>
  <?php include './inc/nav.php'?>
  <?php include './inc/aside.php'?>
  ~~~

- 2.修改侧边栏中的链接路径

  ~~~php
  <ul id="menu-settings" class="collapse">
            <li><a href="/admin/nav-menus.php">导航菜单</a></li>
            <li><a href="/admin/slides.php">图片轮播</a></li>
            <li><a href="/admin/settings.php">网站设置</a></li>
  </ul>
  ~~~

- 3.开启session

  ~~~php
  <?php

    require '../functions.php';
    checkLogin();
  ?>
  ~~~

### 2.查询数据渲染页面

- 1.打开此页面的时候，要查询数据库

  ~~~php
  <?php

    require '../functions.php';
    checkLogin();

     $lists = query('SELECT `value` FROM options WHERE `key` = "home_slides"');

     // print_r($lists);
     // exit;
     //将数据转换成json对象 
     $json = json_decode($lists[0]['value'],true);
     // print_r($json);
     // exit;
  ?>
  ~~~

- 2.渲染页面数据

  ~~~php
   <tbody>
          <?php foreach($json as $key => $vals){ ?>
              <tr>
                    <td class="text-center"><input type="checkbox"></td>
                    <td class="text-center"><img class="slide" src="<?php echo $vals['image']?>"></td>
                    <td><?php echo $vals['text']?></td>
                    <td><?php echo $vals['link']?></td>
                    <td class="text-center">
                      <a href="/admin/slides.php?action=delete&index=<?php echo $key?>" class="btn btn-danger btn-xs">删除</a>
                    </td>
               </tr>
          <?php } ?>
   </tbody>
  ~~~

### 3.轮播图中的删除

- 当单击删除按钮时，要发送请求,获取数据,更新数据库

  ~~~php
  <?php

    require '../functions.php';
    checkLogin();

     $lists = query('SELECT `value` FROM options WHERE `key` = "home_slides"');

     // print_r($lists);
     // exit;
     //将数据转换成json对象 
     $json = json_decode($lists[0]['value'],true);
     // print_r($json);
     // exit;

     $action = isset($_GET['action'])?$_GET['action']:'add';

     if($action =='delete'){
        $index = $_GET['index'];

        unset($json[$index]);//从数组中删除此项

        // 将数据转换成字符串
        $data = json_encode_no_zh($json);
        // print_r($data);
        // var_dump($data);
        // exit;
        //更新数据库
        update('options',array('value' => $data),10);
     }
  ?>
  ~~~


### 4.轮播图中的添加

- 1.单击按钮上传图片

  ~~~php
  <script>
    $('#image').on('change',function(){

      //创建对象 
      var data = new FormData();
      data.append('img',this.files[0]);

      //创建异步对象
      var xhr = new XMLHttpRequest;

      xhr.open('post','/admin/slides.php?action=upfile');

      //发送数据
      xhr.send(data);

      //如果成功之后
      xhr.onreadystatechange = function(){
        if(xhr.readyState == 4 && xhr.status == 200){
          // 将返回的数据，存入隐藏域中
          $('.thumbnail').attr('src',xhr.responseText).show();

          //还得给隐藏域赋值
          $('#img').val(xhr.responseText);
        }
      }
    })
  </script>
  ~~~

- 2.接收传递过来的图片，转移到指定的文件夹

  ~~~php
  //接收图片的上传
     if($action =='upfile'){
        // print_r($_FILES);
        // exit;

        if(!file_exists('../uploads/slides')){
          mkdir('../uploads/slides');
        }
        //设置时间戳
        $fileName = time();

        //获取后缀
        $ext = explode('.',$_FILES['img']['name']);

        $path = '../uploads/slides/' . $fileName . '.' . $ext[1];

        //移动到指定文件夹
        move_uploaded_file($_FILES['img']['tmp_name'],$path);

        echo substr($path,2);
        exit;

     }
  ~~~

- 3.注意一定要在页面上添加一个隐藏域，来存储上传的图片路径，以备添加到数据库中

  ~~~php
  <h2>添加新轮播内容</h2>
              <div class="form-group">
                <label for="image">图片</label>
                <!-- show when image chose -->
                <img class="help-block thumbnail" style="display: none">
                <input id="image" class="form-control" type="file">
                <input type='hidden' name='image' value='' id='img'>
  </div>
  ~~~

- 4.点击添加按钮的时候，将页面上的信息添加到数据库中

  ~~~php
   if(!empty($_POST)){
          //接收传过来的数据，添加到数组中，再去更新数据库
          // print_r($_POST);
          // exit;
          $json[] = $_POST;

          //转换成字符串
          $data = json_encode_no_zh($json);

          // print_r($data);
          // exit;
          //  更新数据库
          $result = update('options',array('value' => $data),10);

          if($result){
            header('location:/admin/slides.php');
            exit;
          }
        }
  ~~~


# 2.前台页面中的轮播图

### 1.到数据库中取出数据

- 在根目录下面的index.php页面中先查询数据库

  ~~~php
  <?php
    
    header('Content-type:text/html;charset=utf-8');
    require './functions.php';

     $navs = query('SELECT `value` FROM options WHERE `key` = "nav_menus"');

     // print_r($navs);
     // $navs = json_decode($navs[0]['value']), true);

      $navs = json_decode($navs[0]['value'], true); // 转换成了一个关联数组
      // print_r($navs);
      // exit;

      //查询页面中的数据
      $slides = query('SELECT `value` FROM options WHERE `key` = "home_slides"');
      // print_r($slides);
      // exit;
      $slides = json_decode($slides[0]['value'],true);

      // print_r($slides);
      // exit;
  ?>		
  ~~~

### 2.页面渲染数据

  ~~~php
 <div class="swipe">
        <ul class="swipe-wrapper">
            <?php foreach($slides as $key => $vals){ ?>
              <li>
                <a href="#">
                  <img src="<?php echo $vals['image']?>">
                  <span><?php echo $vals['text']?></span>
                </a>
              </li>
             <?php }?>
        </ul>
        <p class="cursor"><span class="active"></span><span></span><span></span><span></span></p>
        <a href="javascript:;" class="arrow prev"><i class="fa fa-chevron-left"></i></a>
        <a href="javascript:;" class="arrow next"><i class="fa fa-chevron-right"></i></a>
</div>
  ~~~
# 3.模块化

## 1.模块化介绍

- 1.什么是模块化

~~~
定义： 模块化是一种处理复杂系统分解为更好的可管理模块的方式
 	  具有某种功能的单独的js文件，就可以看成是一个小的模块
~~~

- 2.模板化开发的作用

~~~
1.模块化用来分割，组织和打包软件。每个模块完成一个特定的子功能，所有的模块按某种方法组装起来，成为一个整体，完成整个系统所要求的功能。[1] 
2.模块具有以下几种基本属性：接口、功能、逻辑、状态，功能、状态与接口反映模块的外部特性，逻辑反映它的内部特性。[1] 
在系统的结构中，模块是可组合、分解和更换的单元。
3.模块化是一种处理复杂系统分解成为更好的可管理模块的方式。它可以通过在不同组件设定不同的功能，把一个问题分解成多个小的独立、互相作用的组件，来处理复杂、大型的软件。
~~~

- 3.模块化开发的好处

~~~
1. 达到公共模块复用的一个效果
2. 可以解决全局变量污染的问题
3. 可以很好的解决各个文件或功能之间的依赖关系
4. 使层次结构更加的清晰
~~~

- 4.模块化开发的标准

~~~
js本身不支持模块化开发，由于浏览器的限制，js并不能直接操作文件。但是可以通过一些手段来实现。
可以通过CommonJS的标准或是规范，commonJS是一个规范，用来规定模块化开发的标准。是后端的一种规范，在前端中不太适用。
虽然不能在前端中很好的实现，但是在这个基础上做了改进。
	AMD   Async  Module Define				依赖前置    require.js
	CMD   Common   Module  Define			依赖就近    as  lazy  as  possible   sea.js
	 
require.js本身是一个js文件，这个实现了AMD规范，所以可以帮助我们在前端开发中实现模块化。
~~~

- 5.requirejs的下载地址

~~~javascript
http://requirejs.org/
requirejs本身就是一个js文件，这个文件实现了AMD规范，所以可以帮助我们在前端开发中实现模块化！
~~~

## 2.requirejs的使用介绍

- 1.基本使用   先定义一个模块化的文件，再在使用的地方进行调用

~~~javascript
1. 比如先在a.js中书写如下代码：
define(function (){   //这是定义模块
       alert('我们在使用require.js模块化做东西');
});
2.在需要调用的页面中先引入require.js文件，然后再调用
<script src="require.js"></script>
<script>
    require(['a']); // 这是调用模块
</script>
~~~

- 2.当前模块可以引用其它模块


~~~javascript
1.在js文件当中，定义多个模块 a.js  b.js
2.在静态页面当中加载多个模块，自己的文件当中也有回调用函数
<script src="js/require.js"></script>
  <script>
    require(['js/a.js','js/b.js'],function (){
         alert('这是当前页面调用模块的时候，要执行的代码，是index.html中的代码....');
    });
    // 模块的执行顺序一般是，先加载的哪个，先执行哪个，但是支持异步执行的方式
    // 当所有的模块里面的代码加载完毕之后，自己回调函数内的代码才会执行
  </script>
~~~

- 3.模块当中如果有返回值的话，必须在当前页面的回调函数当中，写上形参来接收对应的返回值 

~~~javascript
 a.js代码如下：
 define(function (){
  alert('这是a模块里面的代码执行了...,没有返回值');
});
b.js代码如下：
define(function (){
       alert('这是b模块的代码开始执行了....');
       return '123456';
})
c.js文件中的代码如下：
define(function (){
  alert ('这是C模块里面的代码在执行...');
      var obj = {
        name:'zhangsan',
        age:20
      } ;
      return obj;
})
主页面调用的时候，代码如下：
 <script src="js/require.js"></script>
  <script>
    require(['js/a','js/b','js/c',],function (o,o1,o2){
      console.log(o1);
      console.log(o2.name);
         alert('这是当前页面调用模块的时候，要执行的代码，是index.html中的代码....');
    });
    // 模块的执行顺序是，先加载的哪个模块，就是哪个模块先执行
    // 当所有的模块里面的代码加载完毕之后，自己回调函数内的代码才会执行
    // 即使是当前的模块没有返回值，如果是在前面加载的话，一定要在回调函数当中，写上一个形参来占一个位置，让模块和形参一一对应起来
  </script>
~~~

- 4.当前的模块依赖其它，及路径的问题一

~~~javascript
1. a.js  b.js  c.js同上
a.js代码如下：
 define(function (){
  alert('这是a模块里面的代码执行了...,没有返回值');
});
b.js代码如下：
define(function (){
       alert('这是b模块的代码开始执行了....');
       return '123456';
})
c.js文件中的代码如下：
define(function (){
  alert ('这是C模块里面的代码在执行...');
      var obj = {
        name:'zhangsan',
        age:20
      } ;
      return obj;
})
main.js中的模块代码如下：在此模块当中，又依赖了a.js  b.js这两个模块
define(['js/a','js/b'], function (o1,o2){
      console.log(o2-400); // '123456' + -*/
     alert('这是一个main模块在执行代码了...');
})
因为是在index.html当中调用main模块,因此a.js b.js的模块路径，仍然是需要相对于index.html来设置
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Title</title>
  <script src="js/require.js" ></script>
  <script>
    require(['js/main']);
    // 虽然main.js模块依赖a.js，但是a.js的加载路径要相对于index.html来设置
  </script>
</head>
<body>

</body>
</html>
~~~

- 5.路径问题二

~~~javascript
1. a.js b.js  c.js 代码同上
2. 新建aa/bb/kk.js， kk模块的代码如上：
define(function (){
  alert('这是aa文件夹下面的bb文件夹下面的kk模块在执行代码了...');
})
3. main.js中的代码及依赖关系，代码如下：
define(['js/a','js/b','aa/bb/kk'], function (o1,o2){
      console.log(o2-400); // '123456' + -*/
     alert('这是一个main模块在执行代码了...');
})
4.在index.html中的调用代码如下：
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Title</title>
  <script src="js/require.js" ></script>
  <script>
    require(['js/main']);
    // 虽然main.js模块依赖a.js,b.js,kk.js，但是这些模块的加载路径要相对于index.html来设置
  </script>
</head>
<body>

</body>
</html>
~~~

- 6.路径问题三

~~~javascript
1. a.js  b.js  c.js kk.js的代码如上
2. main.js此时的代码如下，它现在是一个调用者,是一个主模块
//当前是一个模块，它可以调用或是加载所有的其它模块，供某些页面来使用
require(['a','b','c','../aa/bb/kk'],function (o1,o2,o3){
       console.log(o2);
       console.log(o3.name);
       alert('这是主模块内的代码执行了....');

       // 此时的路径就是相对于main.js而言了，就不再是相对于index.html而言了
      //当前的模块依赖，始终相对于当前的调用者来设置   谁里面执行了require()函数，谁就是调用者
});
3. 在index.html中的代码如下：
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Title</title>
  <script src="js/require.js"  data-main="js/main.js" ></script>
</head>
<body>

</body>
</html>
~~~

- 7.路径问题四

~~~javascript
1. 如果要是有比较多的模块，都在加载的时候，写上路径的话，会非常的不直接，不简捷，因此需要设置一个配置文件，来统一的管理这些模块的路径，可以先设置一个基础路径，再设置各自文件的路径
2. 代码如下：
requirejs.config({
  baseUrl:'/day5/04-requirejs使用'    //需要查找的最原始的路径   只有服务器才会有根目录
});
require(['test8/js/a','test8/js/b','test8/js/c','test8/aa/bb/kk','test6/aa/bb/mm'],function (o1,o2,o3){
// require(['test8/js/a'],function (o1,o2,o3){
  console.log(o2);
  console.log(o3.name);
  alert('这是主模块内的代码执行了....');

  // 此时的路径就是相对于main.js而言了，就不再是相对于index.html而言了
  //当前的模块依赖，始终相对于当前的调用者来设置   谁里面执行了require()函数，谁就是调用者
});
3.注意：只要设置了根目录，或是与项目的根目录相关，就一定要在服务器下运行软件
~~~

## 3.将现在文件改造成模块化

- 将jquery,cookie,bootstrap改造成模块化
- 在main.js主模块中的代码如下：

~~~javascript
在main.js主模块中的代码如下：
require.config({
  baseUrl:'/',
  paths:{
    'jquery':'views/public/assets/jquery/jquery',  //注意在这个地方，不要加.js的后缀
    'cookie':'views/public/assets/jquery-cookie/jquery.cookie',
    'bootstrap':'views/public/assets/bootstrap/js/bootstrap'
  },
  shim:{    // 小垫片
    'bootstrap':{
      // dependence  dependence 依赖
      deps:['jquery']
    }
    // 让原本不支持模块化的库文件，也变的支持模块化
  }
});
require(['jquery','cookie','bootstrap'],function ($){
  $('p').css({
    width:200,
    height:200,
    backgroundColor:'red'
  })
})
~~~

- 在index.html页面中的代码如下：

~~~html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Title</title>
  <script src="js/require.js" data-main="js/main.js"></script>
</head>
<body>
<span>这是一个普通的文件的span标签，当你看到的时候，说明已经打开成功了 。。。</span>
<p></p>
</body>
</html>
~~~

## 4.其它介绍

### 4.1匿名模块

- 之前使用的除了jquery之外，其它的都是我们自己定义的，这些都是匿名模块(没有自己真正的字)

### 4.2具有模块

~~~javascript
像jquery这样的具名模块必须使用人家定义好的模块名，不能再添加其它的别名
require.config({
  baseUrl:'/',
  paths:{
    'a':'02-具名模块的使用/js/a',
    'bbbb':'02-具名模块的使用/js/b', // 虽然这个模块没有名称，但是前面的bbbb就相当于是一个别名
    'c':'02-具名模块的使用/js/c',
    'd':'02-具名模块的使用/js/module/d',
    'jquery':'02-具名模块的使用/js/jquery/jquery.min'  // 具名模块的使用，不能乱写，必须写之前定义好的名称
  },
  shim:{
    'boostrap':{
      deps:['jquery']
    }
  }
});

require(['a','bbbb','jquery'],function (obj1,obj2,$){
       console.log($('p'));
});
~~~

### 4.3将非模块改成模块化 

~~~javascript
普通函数文件内的代码
// function test(){
//   alert('这是一个正常的函数文件，不是模块化的定义方式....');
// }

var aaa = "这是普通的文件中的变量字符串";
使用模块化加载 ，并使用函数或是变量：
require.config({
  baseUrl:'/',
  paths:{
    'm':'03-非模块改成模块化/js/m'
  },
  shim:{
    'boostrap':{
      deps:['jquery']
    },
    'm':{
      //exports:'test'  // 一定要用双引号引起来，是一个字符串形式,这个名称一定要和非模块文件中的函数名或是变量名称一致
      exports:'aaa'
    }
  }
});

require(['m'],function (fn){
    // fn();
  console.log(fn);
});
~~~

# 5.artTemplate模板

## 5.1原生语法复习

~~~javascript
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>arTemplate</title>
  <script src="../../views/public/assets/artTemplate/template-native.js"></script>
</head>
<body>
<div id="box">
  
</div>
</body>
</html>
<script type="template/html" id="tpl">
  <span>我叫<%= name%>我来自于<%= address%>今年<%= age%>了</span>
  <div>我的手机号是<%= tel%></div>
</script>
<script>
  var person = {
    name:'皮皮虾',
    age:20,
    tel:'123456789212',
    address:'深圳市',
    course: ['html','css','javascript','jquery']
  }
  var html = template('tpl',person);
  document.querySelector("#box").innerHTML = html;
</script>
~~~

## 5.2简洁语法

~~~javascript
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>arTemplate</title>
<!--<script src="../../views/public/assets/artTemplate/template-native.js"></script>-->
  <!--注意简洁语法一定要使用简洁语法的库文件-->
  <script src="../../views/public/assets/artTemplate/template.js"></script>
</head>
<body>
<div id="box">
  
</div>
</body>
</html>
<script type="template/html" id="tpl">
  <span>我叫{{name}}我来自于{{address}}今年{{age}}了</span>
  <div>我的手机号是{{tel}}</div>
  <ul>
    {{each course as value i}}
    <li>{{i+1}}:{{value}}</li>
    {{/each}}
  </ul>
</script>
<script>
  var person = {
    name:'皮皮虾',
    age:20,
    tel:'123456789212',
    address:'深圳市',
    course: ['html','css','javascript','jquery']
  }
  var html = template('tpl',person);
  document.querySelector("#box").innerHTML = html;
</script>
~~~
