<?php
$phpname = 'live.php';//主要文件所在位置 如果在文件夹里如路径/文件名.php 请改成路径/文件名.php
define('CHECK',0);//0是关闭ua检测 1是开启ua检测
define('UA','testua');//检测的ua


$ch['演示'] = ['id' => 'http://youtube.com/后面的id','qn' => '清晰度//5 1080 4 720 3 480 2 360 1 240 0 144','files' => '可生成的缓存文件个数','name' => '循环出来的列表的频道名'];
$ch['tvbs'] = ['id' => 'YvdcZ_jpLXE','qn' => '0','files' => '500','name' => 'TVBS'];//TVBS
//画质5 1080 4 720 3 480 2 360 1 240 0 144



/**********
 +
 +代码编写 GeJI 恩山论坛
 +听说倒卖代码的人的妈死了
 +!!!!!以下代码不懂不可作修改!!!!!
 +
**********/
$urlhead = 'http://'.$_SERVER['HTTP_HOST'].'/'.$phpname;
if($_GET['api']) {
    foreach($ch as $a => $b) {
        echo $ch[$a]['name'].','.$urlhead.'?id='.$a.PHP_EOL;
    };
};
if($_GET['web']) {
    echo '<h1>';
    foreach($ch as $a => $b) {
        echo '<li><a href="'.$urlhead.'?id='.$a.'">'.$ch[$a]['name'].'</a></li>'.PHP_EOL;
    };
    echo '</h1>';
};