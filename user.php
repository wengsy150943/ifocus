<?php
session_start();

/* ********* 用户类 **********
* 初始化一个用户类提供了一个对已录入用户进行操作的类，包含：
* 查询、修改用户的基础信息，包括头像、昵称、签名、日志；
* 维护该用户的直播状态，包括加入直播列表，检测学习状态，移出直播列表；
* 实例化用户类需要其唯一id，传入信息采用POST方法，传出信息采用函数回调，具体利用control方法进行控制；

*/
class user
{
    // 目前测试的网址
    private $url = "http://192.168.0.102/ifocus-back/";
    public $user_id;
    private $timestamp;
    private $result;
    // 构造/析构函数，需要用户的id作为传入参数
    function __construct()
    {
        $id = $this->get_id();
        $this->user_id = $id;
        $timestamp = time();
        date_default_timezone_set('PRC');
        $conn = new mysqli("localhost", "ifocus", "ifocus", "ifocus");
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
            return;
        }
        $sql = "SELECT * FROM user
            WHERE id=\"{$id}\"";
        $result = $conn->query($sql);
        // 如果查询不到用户 新建一个条目
        if ($result->num_rows == 0) {
            $sql = "INSERT INTO user (id)
        VALUES (\"{$id}\");";
            $conn->query($sql);
            $sql = "SELECT * FROM user
            WHERE id=\"{$id}\"";

            $result = $conn->query($sql);
        }
        $conn->close();

        $this->result = $this->get_result($result)[0];
    }
    function __destruct()
    {
    }
    // 分流入口
    function control()
    {
        $arg = $_REQUEST['argc'];
        switch ($arg) {
            case "edt":
                $this->edt();
                break;
            case "info":
                echo $this->get_user_info();
                break;
            case "log":
                echo $this->get_log();
                break;
            case "start_live":
                echo $this->start_live();
                break;
            case "end_live":
                echo $this->end_live();
                break;
            default:
                break;
        }
    }
    // 与微信服务器交互，获取openid
    function get_id()
    {
        return $_POST['id'];
    }
    // 将sql的返回值转化为数组
    function get_result($result)
    {
        $res = array();
        while ($row = $result->fetch_assoc()) {
            if (isset($row['img'])) {
                $handle = fopen("img/" . $this->user_id . ".jpg", "w");
                fwrite($handle, $row['img']);
                fclose($handle);
                $row['img'] = $this->url . "img/" . $this->user_id . ".jpg";
            }
            $res[] = $row;
        }
        return $res;
    }
    // 记录/读取日志，日志统一存放在../log/中，名称格式为log-user_id.txt，内容为"开始时间 结束时间\n"(unix时间戳)
    function write_log($time)
    {
        $log = $this->url . "log/log-" . $this->user_id . ".txt";
        $handle = fopen($log, "a");
        fwrite($handle, $time);
        fclose($handle);
    }
    function get_log()
    {
        $file = file($this->url . "log/log-" . $this->user_id . ".txt");
        return json_encode($file); // 如果没有日志 返回NO LOG
    }
    // 返回用户信息
    function get_user_info()
    {
        if (isset($_POST['res'])) return $this->result[$_POST['res']];
        else return json_encode($this->result);
    }

    // 修改函数，接受POST值name,slogan，以及文件pic，分别对应昵称，签名，头像
    function edt()
    {

        $username = $_POST["nickname"];
        $slogan = $_POST['slogan'];
        $img = getImg($_FILES["img"]);
        $id = $this->user_id;

        // 更新用户信息
        date_default_timezone_set('PRC');
        $conn = new mysqli("localhost", "ifocus", "ifocus", "ifocus");
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
            return;
        }
        $sql = "UPDATE user SET nickname=\"{$username}\", 
                  img=\"{$img}\",slogan=\"{$slogan}\"
                WHERE id=\"{$id}\"";
        $conn->query($sql);
        $conn->close();
        Header("Location:userinfo.php");
    }


    // 直播的生命周期
    function start_live()
    {
        $room_id = $_POST['room_id'];
        $state = $_POST['state'];
        // 如果不是单纯锁屏，开启直播推流
        if ($state != 0) {
            $livestream = $this->user_id;
            $key = $room_id;
        }
        $id = $this->user_id;
        // 更新livelist状态
        date_default_timezone_set('PRC');
        $conn = new mysqli("localhost", "ifocus", "ifocus", "ifocus");
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
            return;
        }
        $time = date('Y-m-d H:i:s', time());
        $sql = "INSERT INTO livelist(id,room_id,live_state,livestream,live_key,start_time)
            VALUES (\"{$id}\",\"{$room_id}\",\"{$state}\" ,\"{$livestream}\",\"{$key}\",\"{$time}\" );";
        $conn->query($sql);
        $conn->close();
        // 检查是否失败
        // check_alive($livestream);
        // 跳转回原页面
        //Header("Location:studylist.php");
    }
    function end_live()
    {
        // 更新直播状态
        $_SESSION['alive'] = FALSE;
        date_default_timezone_set('PRC');
        $conn = new mysqli("localhost", "ifocus", "ifocus", "ifocus");
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
            return;
        }
        // 更新日志 格式为 "开始时间 结束时间"（yyyy-mm-dd hh:ii:ss）
        $sql = "SELECT * FROM livelist
            WHERE id = \"{$this->user_id}\"";
        $result = $conn->query($sql);
        $time = $this->get_result($result)[0]['start_time'];
        $this->write_log($time . " " . date('Y-m-d H:i:s', time()) . "\n");
        // 更新rank_list内容
        $time = time() - strtotime($time);

        $sql = "SELECT all_time FROM rank_list WHERE id = \"{$this->user_id}\"";
        $result = $conn->query($sql)->fetch_assoc()[0]['all_time'];
        echo $conn->error;

        $sql = "UPDATE rank_list SET all_time = ($result+$time) WHERE id = \"{$this->user_id}\"";
        $conn->query($sql);
        echo $conn->error;

        $sql = "SELECT time,last_active_date FROM rank_list WHERE id = \"{$this->user_id}\"";
        $result = $conn->query($sql)->fetch_assoc()[0];
        echo $conn->error;
        $date = date('Y-m-d', time());
        if ($result['last_active_date'] == $date) $result = $result['today_time'] + $time;
        else $result = $time;
        $sql = "UPDATE rank_list SET time = $result,last_active_date = \"{$date}\" WHERE id = \"{$this->user_id}\"";
        $conn->query($sql);
        echo $conn->error;

        // 从直播名单中删除
        $sql = "DELETE FROM livelist
            WHERE id = \"{$this->user_id}\"";
        $result = $conn->query($sql);
        echo $conn->error;
        $conn->close();
    }
}
/* 辅助函数
 * 包括录入用户，检测用户是否已录入，读入图像等函数
 * 
 * 
 * 
 */


// 从本地读取图像
function getImg($imgfile)
{
    $name = $imgfile['name'];  //取得图片名称
    $size = $imgfile['size'];  //取得图片长度
    $tmpfile = $imgfile['tmp_name'];  //图片上传上来到临时文件的路径
    if ($tmpfile and is_uploaded_file($tmpfile))  //判断上传文件是否为空，文件是不是上传的文件
    {
        //读取图片流
        $file = fopen($tmpfile, "rb");
        $imgdata = addslashes(fread($file, $size));
        fclose($file);
    }
    return $imgdata;
}
// 检查是否离开，需要调用算法
function check_alive($livestream)
{
    $path = "srs/trunk/objs/nginx/html/" . $livestream;
    $_SESSION['alive'] = TRUE;
    // session 可能不可用 可参考https://www.jb51.net/article/160818.htm 进行调整
    while ($_SESSION['alive'] == TRUE) {
        $name = exec("ls " . $path . "/ -l | grep [*.]ts$ |tail -n 1|awk '{print $9}'");
        $_SESSION['alive'] = exec("python check" . $name);
    }
}
