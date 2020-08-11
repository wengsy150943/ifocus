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
    default:
        break;
};
