<?php
/**********
 +!!!!!必须要不是在中国内网GFW的服务器!!!!!
 +代码编写 GeJI 恩山论坛
 +本代码经过几次更新迭代 以下是修复历代版本的错误和功能增加的解说
 +BUG1 修复前一个版本之前所有版本存在的ts文件获取问题
 +增加 逻辑增加 对获取的ts文件进行分文件夹编号管理 如果已经获取过这个ts文件（多个观众观看的时候）会直接跳转到文件缓存地 不会进行复写 可一定程度缓解服务器压力
 +增加 channels.php可以对观看的频道的清晰度以及可缓存文件最大个数分开设置而不是像之前的版本统一设置频道清晰度和获取文件最大个数 channels.php内有详细说明
 +增加 UA检测功能 可以放在如DIYP等可以设置ua的直播软件中 设置好与软件相同的ua可播放 外部用户没有使用设置的ua值将无法播放 可以在channels.php里进行设置开关检测和ua值
 +有什么问题或者是遇到了服务器无法运行的情况请询问qq213906631
 +听说倒卖代码的人的妈死了
 +!!!!!以下代码重要勿动!!!!!
**********/
error_reporting(0);
include('channels.php');
$ts = $_GET['ts'];
$id = $ch[$_GET['id']]['id'];
if(CHECK) {
    if(!$id || $_SERVER['HTTP_X_REQUESTED_WITH'] != '' || $_SERVER['HTTP_USER_AGENT'] != UA) {
        header('HTTP/1.1 403');
        exit();
    };
};
$qn = $ch[$_GET['id']]['qn'];
$delfiles = $ch[$_GET['id']]['files'];
$data = file_get_contents('http://youtube.com/watch?v='.$id);
preg_match('/hlsManifestUrl":"(.*?)"/',$data,$m3u8url);
$data = file_get_contents($m3u8url[1]);
preg_match_all('/http(.*?)\n/',$data,$m3u8url);
$m3u8data = file_get_contents($m3u8url[0][$qn]);
if ($ts == '') {
    header('content-type:application/x-mpegURL');
    $r = urldecode(preg_replace('/http(.*?)sq\//','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'&ts='.date('Ymd_'),$m3u8data));
    $r = str_replace('/file/seg.ts','',$r);
    echo $r;
} else {
    @mkdir('cache/'.$id,0777,true);
    preg_match_all('/clen=(.*?);|lmt=(.*?)\/|dur\/(.*?)$|(.*?)\//',$ts,$ids);
    if(array_flip(scandir('cache/'.$id))[$ids[4][0].'.ts']) {
        header('location:cache/'.$id.'/'.$ids[4][0].'.ts');
    } else {
        preg_match_all('/http(.*?)sq\//',$m3u8data,$urlhead);
        $url = $urlhead[0][0].preg_replace('/(.*?)_/','',$ids[4][0]).'/goap/clen%3D'.$ids[1][2].'%3Blmt%3D'.$ids[2][3].'/govp/clen%3D'.$ids[1][5].'%3Blmt%3D'.$ids[2][6].'/dur/'.$ids[3][7].'/file/seg.ts';
        $data = file_get_contents($url);
        file_put_contents('cache/'.$id.'/'.$ids[4][0].'.ts',$data);
        header('location:cache/'.$id.'/'.$ids[4][0].'.ts');
    };
};
$list = @scandir('cache/'.$id);
if (count($list) - 2 >= $delfiles) {
    for ($i = 2; $i <= count($list) - 1; $i++) {
        unlink('cache/'.$id.'/'.$list[$i]);
    };
    header('location:'.basename(__FILE__));
};
?>