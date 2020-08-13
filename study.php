<?php
session_start();
/** 自习室类
 * 
 * 
 * 
 * 
 */
include "user.php";
class self_study_room
{
    public $room_id;
    private $user_list;
    function __construct()
    {
        $this->room_id = $_POST['room_id'];
        $this->user_list = $this->get_user_list();
    }
    function __destruct()
    {
    }
    // 获取用户列表，返回用户的livelist信息（直播状态/流/id
    function get_user_list()
    {
        date_default_timezone_set('PRC');
        $conn = new mysqli("localhost", "ifocus", "ifocus", "ifocus");
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
            return;
        }
        $sql = "SELECT * FROM livelist
            WHERE room_id=\"{$this->room_id}\"";
        $user_list = $conn->query($sql);
        $conn->close();
        return $user_list;
    }
    // 获取列表里用户的基本信息
    function get_user_info()
    {
        $all_info = array();
        while ($row = $this->user_list->fetch_assoc()) {
            $_POST['id'] = $row['id'];
            $temp = new user();
            $all_info[] = json_encode($temp->get_user_info());
        }
        return json_encode($all_info);
    }
    // 获取某个用户的直播信息
    function get_live()
    {
        while ($row = $this->user_list->fetch_assoc()) {
            if ($row['id'] == $_POST['id']) {
                $ans = json_encode($row);
            }
        }
        return $ans;
    }
    // 返回是否存在房间
    function check_room(){
        return $this->user_list->num_rows == 0?'FALSE':'TRUE';
    }
    // 分流入口
    function control()
    {
        $arg = $_REQUEST['argc'];
        switch ($arg) {
            case "userinfo":
                echo ($this->get_user_info());
                break;
            case "live":
                echo $this->get_live();
                break;
            case "check":
                echo $this->check_room();
            default:
                break;
        }
    }
}

/***
 * list函数
 * 
 * 
 * 
 */
// 返回房间列表
function get_room_list()
{
    date_default_timezone_set('PRC');
    $conn = new mysqli("localhost", "ifocus", "ifocus", "ifocus");
    // 检测连接
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
        return;
    }
    $sql = "SELECT DISTINCT room_id FROM livelist";
    $room_list = $conn->query($sql);
    $ans = array();
    while ($row = $room_list->fetch_assoc()) {
        $id = $row['room_id'];
        $ans[$id] = $conn->query("SELECT COUNT(id) AS num FROM livelist WHERE room_id = \"{$id}\"")->fetch_assoc()['num'];
        echo $conn->error;
    }
    $conn->close();
    return json_encode($ans);
}
// 返回今日排行榜
function get_today_rank()
{
    date_default_timezone_set('PRC');
    $conn = new mysqli("localhost", "ifocus", "ifocus", "ifocus");
    // 检测连接
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
        return;
    }
    $time = date("Y-m-d", time());
    $sql = "SELECT id,time FROM rank_list WHERE last_active_date = \"{$time}\" ORDER BY time+0 DESC";
    $rank = $conn->query($sql);
    $ans = array();
    while ($row = $rank->fetch_assoc()) {
        $ans[] = json_encode($row);
    }
    $conn->close();
    return json_encode($ans);
}
// 返回总排行榜
function get_total_rank()
{
    date_default_timezone_set('PRC');
    $conn = new mysqli("localhost", "ifocus", "ifocus", "ifocus");
    // 检测连接
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
        return;
    }
    $sql = "SELECT id,all_time FROM rank_list ORDER BY all_time+0 DESC";
    $rank = $conn->query($sql);
    $ans = array();
    while ($row = $rank->fetch_assoc()) {
        $ans[] = json_encode($row);
    }
    $conn->close();
    return json_encode($ans);
}
