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
        自习室列表
        <?php
            
        ?>
    </div>
    <form action="user.php" method="post">
        <input type="hidden" name="argc" value="start_live">
        <input type="hidden" name="id" value="2">
        <input type="hidden" name="room_id" value="10010">
        <input type="radio" name="state" id="" value="0">锁屏<br>
        <input type="radio" name="state" id="" value="1">机器监督<br>
        <input type="radio" name="state" id="" value="2">互相监督<br>
        <input type="submit" value="进入自习室">
    </form>
    <div>
        自习室内
        <?php
           $data = array('argc'=>'info','room_id'=>'10010','target'=>'room');
           $info = request("http://localhost/ifocus-back/control.php",$data);
           echo "<br>";
           print_r(json_decode($info));
           $data = array('argc'=>'info','id'=>2,'target'=>'user','res'=>'nickname');
           echo "<br>"."?";
           echo request("http://localhost/ifocus-back/control.php",$data);
        ?>
    </div>
</body>