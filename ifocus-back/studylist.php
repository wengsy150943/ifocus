<?php
session_start();
// 目前测试的网址
$url = "http://192.168.0.102/ifocus-back/";
// 封装请求函数
function request($url, $post_data)
{
    $postdata = http_build_query($post_data);
    $options = array(

        'http' => array(
            'method' => 'POST',
            'header' => 'Content-type:application/x-www-form-urlencoded',
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )

    );

    $context = stream_context_create($options);

    $result = file_get_contents($url, false, $context);
    return $result;
}

?>

<!DOCTYPE html>
<head>

</head>
<body>
    <div>
        <h2>自习室列表</h2>
        'target'=>'room_list',返回自习室的列表（房间号:人数）
        <br>
        <?php
            $data = array('target'=>'room_list');
            $list = request($url."control.php",$data);
            echo $list;
        ?>
    </div>
    <h2>检查房间号</h2>
    'target'=>'room','argc'=>'check','room_id'=>'10010'，返回TRUE/FALSE，10010是已有的房间
    <br>
    <?php
            $data = array('target'=>'room','argc'=>'check','room_id'=>'10010');
            $list = request($url."control.php",$data);
            echo $list;
        ?>
    <h2>进入自习室</h2>
    'target'=>'user','argc'=>'start_live','id'=>'2','room_id'=>'10010','state'=>0，使id为2的用户在后端转为直播状态，之后可以在使用该房间号查询到该用户
    <form action="control.php" method="post">
        <input type="hidden" name="argc" value="start_live">
        <input type="hidden" name="id" value="2">
        <input type="hidden" name="target" value="user">
        <input type="hidden" name="room_id" value="10010">
        <input type="radio" name="state" id="" value="0">锁屏<br>
        <input type="radio" name="state" id="" value="1">机器监督<br>
        <input type="radio" name="state" id="" value="2">互相监督<br>
        <input type="submit" value="进入自习室">
    </form>
    <div>
        <h2>自习室内</h2>
        获取同自习室成员基本信息
        <br>
        'argc'=>'userinfo','room_id'=>'10010','target'=>'room'，返回10010房间内成员的基本信息，以下返回值是转化为一维数组的情形
        <br>
        <?php
        
           $data = array('argc'=>'userinfo','room_id'=>'10010','target'=>'room');
            $userlist = request($url."control.php",$data);
           $userlist = json_decode($userlist,true);
           print_r($userlist);
           

        ?>
        <br>
        获取某个自习室成员直播信息
        <br>
        'argc'=>'live','room_id'=>'10010','target'=>'room','id'=>2，返回id=2成员的直播信息，以下返回值是不进行转化的情形（json）
        <br>
        <?php
        
           $data = array('argc'=>'live','room_id'=>'10010','target'=>'room','id'=>2);
            $userlist = request($url."control.php",$data);
           $userlist = ($userlist);
           print_r($userlist);
           

        ?>
    </div>
    <div>
    <h2>退出自习室</h2>
    'target'=>'user','argc'=>'end_live','id'=>'2'，使用户在后端退出直播状态，随后在后端会更新日志和排行榜
    <form action="control.php" method="post">
        <input type="hidden" name="argc" value="end_live">
        <input type="hidden" name="id" value="2">
        <input type="hidden" name="target" value="user">
        <input type="submit" value="退出自习室">
    </form>

    </div>
</body>
