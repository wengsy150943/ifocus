<?php
session_start();
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
        <?php
            
        ?>
    </div>
    <h2>进入自习室</h2>
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
        <?php
        
           $data = array('argc'=>'userinfo','room_id'=>'10010','target'=>'room');
            $userlist = request("http://localhost/ifocus-back/control.php",$data);
           $userlist = json_decode($userlist,true);
           print_r($userlist);
           

        ?>
        <br>
        获取某个自习室成员直播信息
        <br>
        <?php
        
           $data = array('argc'=>'live','room_id'=>'10010','target'=>'room','id'=>2);
            $userlist = request("http://localhost/ifocus-back/control.php",$data);
           $userlist = json_decode($userlist,true);
           print_r($userlist);
           

        ?>
    </div>
    <div>
    <h2>退出自习室</h2>
    <form action="control.php" method="post">
        <input type="hidden" name="argc" value="end_live">
        <input type="hidden" name="id" value="2">
        <input type="hidden" name="target" value="user">
        <input type="submit" value="退出自习室">
    </form>

    </div>
</body>