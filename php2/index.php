<?php
    include 'functions.php';
    include 'config.php';

    $now = date("d.m.Y H:i:s");

    include 'form.php';

    $studentName = isset($_GET['studentName']) ? $studentName = $_GET['studentName'] : die('Write your name!');

    //check if file exist
    $file = makeFile('timeLog.txt');
    
    //check if arrive time to school is between 20:00 and 00:00, if yes, it is not possible
    checkArrival($now);

    echo "</br>";
    echo "</br>";

    //get data from time log
    $array = getData('timeLog.txt');

    //check if student has delay
    $delay = hasDelay($now, $studentName);

    //write data into timelog
    pushData($array, $now, $delay, $studentName);

    //get data from time log to show with the newest record
    $array = getData('timeLog.txt');

    if ( !empty($array)) 
    {        
        foreach ($array as $data) {
            echo $data->date.' '.$data->delay;
            echo "</br>";
    }
    }
    else 
    {
        echo 'Nothing to display!';
        echo "</br>";
    }
?>