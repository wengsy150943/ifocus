<?php

session_start();
// 目前测试的网址
$url = "http://192.168.190.102/ifocus-back/";
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
    <h2>修改用户信息</h2>
    'target'=>'user','argc'=>'edt','id'=>'2','nickname'=>,'slogan'=>,'img'=>，修改用户信息
        <form action="control.php" method="post"enctype="multipart/form-data">
            <input type="hidden" value="edt" name="argc">
            <input type="hidden" value="2" name="id">
            <input type="hidden" name="target" value="user">
            昵称
            <input type="text" name="nickname">
            签名
            <input type="text" name="slogan" id="">
            头像
            <input type="file" name="img" id="" accept="image/*">
            <input type="submit" value="提交">
        </form>
    </div>

    <div>
    <h2>请求用户信息</h2>
    'argc'=>'info','id'=>'2','target'=>'user',返回用户信息，可以追加'res'参数指定具体的信息
    <br>
    'res'=>'nickname'，返回昵称
    <br>
    'res'=>'slogan'，返回签名
    <br>
    'res'=>'img'，返回图片的地址
        <?php
        
        $data = array('argc'=>'info','id'=>'2','res'=>'nickname','target'=>'user');
        $nickname = request($url."control.php",$data);
        $data = array('argc'=>'info','id'=>2,'res'=>'slogan','target'=>'user');
        $slogan = request($url."control.php",$data);
        $data = array('argc'=>'info','id'=>2,'res'=>'img','target'=>'user');
        $img = request($url."control.php",$data);
        ?>
        <p>
        昵称：
        <br>
        <?php echo $nickname;?>
        </p>
        <p>
        签名：
        <br>
        <?php echo $slogan;?>
        </p>
        <p>
        头像：
        <br>
        <img src="<?php echo $img;?>" alt="" width="200px">
        
        </p>
    </div>

    <div>
        <h2>返回用户日志（json）</h2>
        'argc'=>'log','id'=>2,'target'=>'user'，返回用户日志的json
        <br>
        <?php
        $data = array('argc'=>'log','id'=>2,'res'=>'img','target'=>'user');
        $log = request($url."control.php",$data);
        echo $log;
        ?>
    </div>
</body>
