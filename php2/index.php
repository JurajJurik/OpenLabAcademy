<?php
    include 'config.php';
    include 'functions.php';
    include 'classes.php';

    $now = date("d.m.Y H:i:s");

    include 'form.php';

    $studentName = isset($_GET['studentName']) ? $studentName = $_GET['studentName'] : $studentName = '';

    //check if file exist
    $file = makeFile('timeLog.txt');
    
    //check if student file exist
    $students = makeFile('students.json');

    //check if arrivals file exist
    $arrivals = makeFile('arrivals.json');
    
    //check if arrive time to school is between 20:00 and 00:00, if yes, it is not possible
    //checkArrival($now);

    echo "</br>";
    echo "</br>";

    //get data from time log
    $array = getData('timeLog.txt');

    //get student names from students.json
    $studentNames = Student::getStudentNames('students.json');

    //get arrivals from students.json
    $arrivals = getData('arrivals.json');

    //check if student has delay
    $delay = hasDelay($now, $studentName);

    //check if student is already written in database
    if (isWritten($array, $now, $studentName)) 
    {
        goToBase($base_url, 'Your arrival is already written!');
    }

    var_dump(isWritten($array, $now, $studentName));


    if (is_string($studentName) && !empty($_GET['studentName'])) 
    {
    //write data into timelog
    $objData = new Data($array, $now, $delay, $studentName);
    $array = $objData -> pushData($array, $now, $delay, $studentName);

    //write data into students json file
    Student::pushStudent($studentName, $studentNames, 'students.json');

    //creating an object of type Arrivals and set variables
    $objArrivals = new Arrivals($array, $now, $arrivals);

    //write data into timelog and students json file
    $objArrivals -> pushArrivals($now, $arrivals);

    //write data into timelog and students json file
    $totalArrivals = $objArrivals -> getArrivals($array);
    }else {
        $totalArrivals = count($array);
    }

    echo "</br>";
    echo "</br>";

    //number of total arrivals
    echo 'Count of arrivals: '.$totalArrivals;

    //get data from time log to display with the newest record
    $array = getData('timeLog.txt');

    //get students names from students map to dispaly with the newest record
    $arrayStudents = getData('students.json');

    //get arrivals from arrivals.json to display with the newest record
    $arrayArrivals = getData('arrivals.json');

    echo "</br>";
    echo "</br>";

    //display arrivals
    echo 'ARRIVALS';
    echo "</br>";

    if ( !empty($array)) 
    {        
        foreach ($array as $data) 
        {
            echo $data['date'].' '.$data['delay'];
            echo "</br>";
        }
    }
    else 
    {
        echo 'Nothing to display!';
        echo "</br>";
    }

    echo "</br>";
    echo "</br>";

    //display students
    echo 'STUDENTS';
    echo "</br>";

    if ( !empty($arrayStudents)) 
    {        
        foreach ($arrayStudents as $data) 
        {
            echo $data;
            echo "</br>";
        }
    }
    else 
    {
        echo 'Nothing to display!';
        echo "</br>";
    }

    echo "</br>";
    echo "</br>";

    //display arrivals
    echo 'ON TIME or LATE';
    echo "</br>";

    if ( !empty($arrayArrivals)) 
    {        
        foreach ($arrayArrivals as $data) 
        {
            $time = substr($data,-8);
            $delay = hasDelay($time, null);
            echo $time . ' ' .$delay;
            echo "</br>";
        }
    }
    else 
    {
        echo 'Nothing to display!';
        echo "</br>";
    }
?>