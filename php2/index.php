<?php
    
    include 'functions.php';
    include 'classes.php';
    include 'form.php';

    $studentName = isset($_GET['studentName']) ? $studentName = $_GET['studentName'] : $studentName = '';

    //check if log files exists
    fileExist();

    //check if arrive time to school is between 20:00 and 00:00, if yes, it is not possible
    checkArrival($now);

    echo "</br>";
    echo "</br>";

    //get data from time log
    $timeLog = getData('timeLog.txt');

    //get student names from students.json
    $studentNames = Student::getStudentNames('students.json');

    //get arrivals from students.json
    $arrivals = getData('arrivals.json');

    //check if student is already written in database
    isWritten($timeLog, $now, $studentName);

    if (is_string($studentName) && !empty($_GET['studentName'])) 
    {
    //write data into timelog
    $objData = new Data($timeLog, $now, $studentName);
    $timeLog = $objData -> pushData($timeLog, $now, $studentName);

    //write data into students json file
    Student::pushStudent($studentName, $studentNames, 'students.json');

    //creating an object of type Arrivals and set variables
    $objArrivals = new Arrivals($timeLog, $now, $arrivals);

    //write data into arrivals json file
    $objArrivals -> pushArrivals($now, $arrivals);

    //get count of total arrivals
    $totalArrivals = $objArrivals -> getArrivals($timeLog);
    }else {
        $totalArrivals = count($timeLog);
    }

    echo "</br>";
    echo "</br>";

    //number of total arrivals
    echo 'Count of arrivals: '.$totalArrivals;

    //get data from time log to display with the newest record
    $timeLog = getData('timeLog.txt');

    //get students names from students map to dispaly with the newest record
    $arrayStudents = getData('students.json');

    //get arrivals from arrivals.json to display with the newest record
    $arrayArrivals = getData('arrivals.json');

    echo "</br>";
    echo "</br>";

    //display arrivals
    echo 'ARRIVALS';
    echo "</br>";
    

    displayArrivals($timeLog);

    echo "</br>";
    echo "</br>";

    //display students
    echo 'STUDENTS';
    echo "</br>";

    displayStudents($arrayStudents);

    echo "</br>";
    echo "</br>";

    //display arrivals
    echo 'ON TIME or LATE';
    echo "</br>";

    displayArrivalTime($arrayArrivals, $timeLog);
?>