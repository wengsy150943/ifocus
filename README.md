# ifocus-back

the back end of ifocus

这里存放ifocus项目的后端代码及其简单演示，并附有简短说明。

## 运行前

需要提前创建需要数据库及对应账号

创建数据库及表代码如下：

```mysql

CREATE DATABASE ifocus;


CREATE TABLE `ifocus`.`user` ( `img` MEDIUMBLOB NULL DEFAULT NULL , `nickname` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL , `slogan` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL , `id` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , UNIQUE `id` (`id`)) ENGINE = InnoDB;


CREATE TABLE `ifocus`.`livelist` ( `id` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `livestream` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `live_key` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL , `start_time` TIMESTAMP on update CURRENT_TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , `room_id` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `live_state` INT(10) NOT NULL , UNIQUE `id` (`id`)) ENGINE = InnoDB;


CREATE TABLE `ifocus`.`rank_list` ( `id` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `last_active_date` DATE NOT NULL , `time` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , `all_time` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL , UNIQUE `id` (`id`)) ENGINE = InnoDB;

```

所需账号

账号名： `ifcocus`
密码： `ifocus`

由于涉及网络通信，请保证运行时项目的根目录为`http://localhost/ifocus-back/`

如果需要修改上述内容，请参照./index.html的说明进行修改