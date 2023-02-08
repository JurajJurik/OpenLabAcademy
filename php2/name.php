<?php

    $studentName = isset($_GET['studentName']) ? $studentName = $_GET['studentName'] : die('Write your name!');



    //get data from time log
    $array = getData('timeLog.txt');

    goToBase($base_url);
?>
