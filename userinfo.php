<!DOCTYPE html>

<head>

    

</head>

<body>
    <div>
        <form action="user.php" method="post">
            <input type="hidden" value="edt" name="argc">
            昵称
            <input type="text" name="nickname">
            签名
            <input type="text" name="slogan" id="">
            <input type="submit" value="提交">
        </form>
    </div>

    <div>
        <?php
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
        $data = array('argc'=>'info','id'=>2);
        $html = request("http://localhost/ifocus-back/user.php",$data);//file_get_contents('user.php?argc=info');
        echo $html;
        ?>

    </div>

    <div>

        <?php
        $data = array('argc'=>'log','id'=>2);
        $daily = request("http://localhost/ifocus-back/user.php",$data);
        echo $daily;
        ?>
    </div>
</body>