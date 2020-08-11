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
    function get_live(){
        while ($row = $this->user_list->fetch_assoc()) {
            if($row['id'] == $_POST['id']){
                $ans = json_encode($row);
            }
        }
        return $ans;
    }
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
            default:
                break;
        }
    }
}