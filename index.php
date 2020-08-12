<!DOCTYPE html>
<head>


</head>

<body>
    <div>
        创建数据库及表代码如下：
        <br>
    CREATE DATABASE ifocus;
    <br>
    CREATE TABLE `ifocus`.`user` ( `id` CHAR(128) NOT NULL , `img` MEDIUMBLOB NULL , `nickname` VARCHAR(20) NULL , `slogan` VARCHAR(40) NULL, UNIQUE `id` (`id`) ) ENGINE = InnoDB;
    <br>
    CREATE TABLE `ifocus`.`livelist` ( `id` VARCHAR(128) NOT NULL , `room_number` INT(5) NOT NULL , `live_state` INT(2) NOT NULL , `livestream` VARCHAR(30) NULL , `live_key` VARCHAR(10) NULL , `start_time` TIMESTAMP NULL , UNIQUE `id` (`id`)) ENGINE = InnoDB;
    <br>
    CREATE TABLE `ifocus`.`rank_list` ( `id` VARCHAR(128) NOT NULL , `last_active_date` DATE NOT NULL , `time` VARCHAR(10) NOT NULL , `all_time` VARCHAR(10) NOT NULL , UNIQUE `id` (`id`)) ENGINE = InnoDB;
    </div>
    <a href="./studylist.php">传送到自习列表</a>
    <br>
    <a href="./userinfo.php">传送到用户信息</a>
    <br>
    <a href="./rank.php">传送到排行榜</a>
</body>