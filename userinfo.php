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
            include "user.php";
            $_POST['id'] = 2;
            $userA = new user();
            echo $userA->get_user_info()[0];
            $userA->get_log();
            $daily = $_SESSION['daily'];
            foreach($daily as $i){
                echo "<div>".$i."</div>";
            }
        ?>
    </div>
</body>