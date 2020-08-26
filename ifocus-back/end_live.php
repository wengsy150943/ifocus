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

$data = array('target' => 'user', 'argc' => 'end_live','id' => '2');
$rank = request($url . "control.php", $data);

