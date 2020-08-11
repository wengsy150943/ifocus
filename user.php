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

        if ($result->num_rows == 0) {
            $sql = "INSERT INTO user (id)
        VALUES (\"{$id}\");";
            $conn->query($sql);
            $sql = "SELECT * FROM user
            WHERE id=\"{$id}\"";

            $result = $conn->query($sql);
        }
        $conn->close();

        $this->result = get_result($result)[0];
        $this->login($this->result);
    }
    function __destruct()
    {
    }
    // 与微信服务器交互，获取openid
    function get_id()
    {
        return $_POST['id'] ;
    }
    // 记录/读取日志，日志统一存放在../log/中，名称格式为log-user_id.txt，内容为"开始时间 结束时间\n"(unix时间戳)
    // 读取得到的日志存放在$_SESSION['daily']中
    function write_log()
    {
        $log = "log-" . $this->user_id . ".txt";
        $handle = fopen($log, "a");
        fwrite($handle, $this->timestamp . " " . time() . "\n");
        fclose($handle);
    }
    function get_log()
    {
        $file = file("./log/log-" . $this->user_id . ".txt");
        return "NO LOG";//$file ?? "NO LOG";
        
    }
    // 返回用户信息
    function get_user_info()
    {
        if(isset($_POST['res'])) return $_SESSION[$_POST['res']];
        else return json_encode($this->result);
    }

    function control()
    {
        $arg = $_GET['argc'] ?? $_POST['argc'];
        echo $arg."<br>";
        switch ($arg) {
            case "edt":
                $this->edt();
                break;
            case "info":
                echo $this->get_user_info();
                break;
            case "log":
                print_r(json_encode($this->get_log()));
            case "start_live":
                echo $this->start_live();
            default:
                break;
        }
    }
    // 登陆函数，更新以下三个参数，$_SESSION['img'](头像)，$_SESSION['slogan'](签名) ，$_SESSION['nickname'] = $row['name'](昵称);
    function login($row)
    {
        $handle = fopen("./img/" . $this->user_id . ".jpg", "w");
        fwrite($handle, $row['img']);
        fclose($handle);
        $_SESSION['img'] = "./img/" . $this->user_id . ".jpg";
        $_SESSION['slogan'] = $row['slogan'];
        $_SESSION['nickname'] = $row['nickname'];
    }
    // 修改函数，接受POST值name,slogan，以及文件pic，分别对应昵称，签名，头像
    function edt()
    {

        $username = $_POST["nickname"];
        $slogan = $_POST['slogan'];
        $img = getImg($_FILES["img"]);
        echo $username;
        print_r($_FILES['img']);
        $id = $this->user_id;
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
        if ($conn->query($sql) === FALSE) {
            $_SESSION['error_msg'] =  $conn->error;
        }
        $conn->close();
        $_SESSION['info'] = $this->result;
        Header("Location:userinfo.php");
    }


    // 直播的生命周期
    function start_live()
    {
        $room_id = $_POST['room_id'];
        $state = $_POST['state'];
        // 如果单纯不是锁屏，开启直播推流
        if($state != 0){
            $livestream = $this->user_id;
            $key = $room_id;
        }
        echo $room_id;
        echo $this->user_id;
        $id = $this->user_id;
        date_default_timezone_set('PRC');
        $conn = new mysqli("localhost", "ifocus", "ifocus", "ifocus");
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
            return;
        }
        $time = date('Y-m-d H:i:s',time());
        $sql = "INSERT INTO livelist(id,room_id,live_state,livestream,live_key,start_time)
            VALUES (\"{$id}\",\"{$room_id}\",\"{$state}\" ,\"{$livestream}\",\"{$key}\",\"{$time}\" );";
        $result = $conn->query($sql);
        $conn->close();
        Header("Location:studylist.php");
    }
    function end_live()
    {
        date_default_timezone_set('PRC');
        $conn = new mysqli("localhost", "ifocus", "ifocus", "ifocus");
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
            return;
        }
        $sql = "DELETE FROM livelist
            WHERE id = \"{$this->id}\"";
        $result = $conn->query($sql);
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
    print_r($imgfile);
    if ($tmpfile and is_uploaded_file($tmpfile))  //判断上传文件是否为空，文件是不是上传的文件
    {
        //读取图片流
        $file = fopen($tmpfile, "rb");
        $imgdata = addslashes(fread($file, $size));
        fclose($file);
    }
    return $imgdata;
}
// 将sql的返回值转化为数组
function get_result($result)
{
    $res = array();
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $res[$count] = ($row);
        $count = $count + 1;
    }
    return $res;
}
