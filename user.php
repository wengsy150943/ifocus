<?php
session_start();

/* ********* 用户类 **********
* 初始化一个用户类提供了一个对已录入用户进行操作的类，包含：
* 查询、修改用户的基础信息，包括头像、昵称、签名、日志；
* 维护该用户的直播状态，包括加入直播列表，检测学习状态，移出直播列表；
* 实例化用户类需要其唯一id，传入信息采用POST方法，传出信息采用SESSION，具体调用方法尚未完善；

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
        // 暂时用时间戳代替
        return $_POST['id'] ;//?? time();
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
        $file = file("../log/" . $this->user_id . ".txt");
        //$_SESSION['daily'] = $file ?? "AAA";
        echo  "AAA";
    }
    // 返回用户信息
    function get_user_info()
    {
        print_r($this->result);
        $_SESSION['info'] = $this->result;
    }

    function control()
    {
        $arg = $_GET['argc'] ?? $_POST['argc'];
        switch ($arg) {
            case "edt":
                $this->edt();
                break;
            case "info":
                $this->get_user_info();
                break;
            case "log":
                $this->get_log();
            default:
                break;
        }
    }
    // 登陆函数，更新以下三个参数，$_SESSION['img'](头像)，$_SESSION['slogan'](签名) ，$_SESSION['nickname'] = $row['name'](昵称);
    function login($row)
    {
        $handle = fopen("../img/" . $this->user_id . ".jpg", "w");
        fwrite($handle, $row['img']);
        fclose($handle);
        $_SESSION['img'] = "img/" . $this->user_id . ".jpg";
        $_SESSION['slogan'] = $row['slogan'];
        $_SESSION['nickname'] = $row['nickname'];
    }
    // 修改函数，接受POST值name,slogan，以及文件pic，分别对应昵称，签名，头像
    function edt()
    {

        $username = $_POST["nickname"];
        $slogan = $_POST['slogan'];
        $img = getImg($_FILES["pic"]);
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
    function live($state, $room_number, $livestream, $key, $set_time)
    {
        $this->start_live($room_number, $state, $livestream, $key);
        $success_complete = TRUE;
        if ($state == 2) {
            /*
            while is_living
                wait for a while
                result = mechine_checking_function 
                if result is OK
                    continue
                else
                    $success_complete = FALSE
                    break
            */
        }
        $_SESSION['result'] = $success_complete;
        $this->end_live();
    }
    function start_live($room_number, $state, $livestream, $key)
    {

        date_default_timezone_set('PRC');
        $conn = new mysqli("localhost", "ifocus", "ifocus", "ifocus");
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
            return;
        }
        $sql = "INSERT INTO livelist(id,room_number,live_state,livestream,live_key,start_time)
            VALUES (\"{$this->id}\",\"{$room_number}\",\"{$state}\" ,\"{$livestream}\",\"{$key}\",\"{time()}\" );";
        $result = $conn->query($sql);
        $conn->close();
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

echo "A\n";
$user = new user();
$user->control();
echo "Z\n";
