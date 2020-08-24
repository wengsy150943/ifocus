<?php

include "study.php";


$tar = $_REQUEST['target'];
switch ($tar) {
    case "user":
        $user = new user();
        $user->control();
        break;
    case "room":
        $room = new self_study_room();
        $room->control();
        break;
    case "room_list":
        print_r(get_room_list());
        break;
    case "today_rank":
        echo (get_today_rank());
        break;
    case "total_rank":
        echo (get_total_rank());
        break;
    default:
        break;
};
