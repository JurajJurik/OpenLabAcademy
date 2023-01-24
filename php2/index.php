<?php
    include 'functions.php';
    include 'config.php';

    echo 'ahoj Wezeo!'; 
    echo "</br>";
    echo "</br>";

    $now = date("d.m.Y H:i:s");

    echo $now;
    echo "</br>";
    echo "</br>";

    include 'form.php';
    
    //check if arrive time to school is between 20:00 and 00:00, if yes, it is not possible
    //checkArrival($now);

    echo "</br>";
    echo "</br>";

    //check if file exist
    $file = makeFile('timeLog.txt');

    //get data from time log
    $array = getData('timeLog.txt');

    //check if student has delay
    $delay = hasDelay($now);

    //write data into timelog
    //pushData($array, $now, $delay);

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