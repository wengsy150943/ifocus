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
    
<h2>获取当日排行</h2>
<div>
    <?php
        $data = array('target'=>'today_rank');
        $rank = request("http://localhost/ifocus-back/control.php",$data);
        echo $rank;
    ?>
</div>
<h2>获取总排行</h2>
<div>
    <?php
        $data = array('target'=>'total_rank');
        $rank = request("http://localhost/ifocus-back/control.php",$data);
        echo $rank;
        echo "<br>";
        print_r(json_decode(json_decode($rank,true)[0],true));
    ?>
</div>
</body>