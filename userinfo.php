<!DOCTYPE html>
<head>

</head>
<body>
    <div>
        <form action="user.php" method="post">
            昵称
            <input type="text" name="nickname">
            密码
            <input type="password" name="pw" id="">
            签名
            <input type="text" name="slogan" id="">
            <input type="submit" value="提交">
        </form>
    </div>

    <div>

        <?php
            $_SESSION['daily']=array(array("a","2020/08/01"),array("b","2020/08/02"));
            $daily = $_SESSION['daily'];
            foreach($daily as $i){
                echo "<div>".$i."</div>";
            }
        ?>
    </div>
</body>