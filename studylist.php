<!DOCTYPE html>
<head>

</head>
<body>
    <div>
        自习室列表
        <?php
            $_SESSION['room-list']=array(array("roomA","student-number"),array("roomB","student-number"));
            $daily = $_SESSION['room-list'];
            foreach($daily as $i){
                echo "<div>".$i."</div>";
            }
        ?>
    </div>
    <div>
        自习室内
        <?php
            $_SESSION['student-list']=array(array("studentA","slogan"),array("studentB","slogan"));
            $daily = $_SESSION['student-list'];
            foreach($daily as $i){
                echo "<div>".$i."</div>";
            }
        ?>
    </div>
</body>