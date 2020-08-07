<?php

/** 自习室类
 * 
 * 
 * 
 * 
 */
include "user.php";
class self_study_room
{
    public $room_number;
    private $user_list = array();
    function __construct()
    {
        $this->get_user_list();
    }
    function __destruct()
    {
    }
    function get_user_list()
    {
        session_start();
        date_default_timezone_set('PRC');
        $conn = new mysqli("localhost", "root", "ifocus", "mydb");
        // 检测连接
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
            return;
        }
        $sql = "SELECT * FROM livelist
            WHERE room_number=\"{$this->room_number}\"";
        $user_list = $conn->query($sql);
        $conn->close();
        $_SESSION['student-list'] = $user_list;

    }
    
}
