<?php
    include 'functions/functions.php';

    echo 'ahoj Wezeo!'; 
    echo "</br>";

    //check if file exist
    $file = makeFile('time_log.txt');

    //get data from time log
    $dataInFile = file_get_contents('time_log.txt');

    //decode data
    $array = json_decode($dataInFile) ?: [];
    
    //current date and time
    $data = [
        'date' => date("d.m.Y H:i:s"),
        'delay'=>'delay'
        ];

    //array for writing into time log
    array_push($array, $data);

    file_put_contents('time_log.txt', json_encode($array));


    foreach ($array as $data) {

        $day = substr($data, 0, 10);
        $time = substr($data, -8);
        $schoolStart = $day." 08:00:00";

        if (strtotime($data) > strtotime($schoolStart)) {
            echo "MESKANIE";
        }
        echo $data;
        echo "</br>";
        // echo strtotime($data), "\n";
        // echo date("H:i:s", strtotime($data))."\n";
        // echo strtotime($data), "\n";
    }


?>
