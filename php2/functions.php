<?php
include 'config.php';

function makeFile( $filename )
{
	// if files doesn't exist, create the file and close it
	if ( ! is_file( $filename ) )
	{
		fclose( fopen($filename, 'x') );
		return true;
	}

	// file already exists
	return false;
}

function checkArrival($now)
{
    $date = new DateTimeImmutable($now);

	if ($date->format('H') < 24 && $date->format('H') >= 20) 
	{
		echo "It is not possible to arrive between 20:00 and 00:00 to school!";
	}
}

function getData($file)
{
	//get data from time log
    $dataInFile = file_get_contents($file);

    //decode data, if time log is empty, it return empty array
    $array = json_decode($dataInFile, true) ?: [];

	return $array;
}

function goToBase ($url, $message = 'Success!')
{
    header("Location: $url/index.php");
	echo $message;
    //die($message);
}

//check if data are already in array
function isDataWritten($array, $now, $studentName)
{
	$intersectArray = [];
	$studentName = trim($studentName);
	$now = new DateTimeImmutable($now);
	$now = $now->format('d.m.Y');

    $data = [
        'date'  =>  $now,
		'studentName' =>  $studentName
        ];
	
	$data = [$data['date'].' '.$data['studentName']];

	foreach ($array as $value) 
	{
		$date = new DateTimeImmutable($value['date']);
		$date = $date->format('d.m.Y');
		$dateStr = [$date.' '.$value['studentName']];

		array_push($intersectArray, implode(" ", $dateStr));
	}

	$data = implode(" ", $data);

	$compare = in_array($data, $intersectArray);

	if ($compare) 
	{
		return true;
	}
	else 
	{
		return false;
	}
}

function fileExist()
{
	//check if file exist
    $file = makeFile('timeLog.txt');
    
    //check if student file exist
    $students = makeFile('students.json');

    //check if arrivals file exist
    $arrivals = makeFile('arrivals.json');
}

function getAllData()
{
	//get data from time log
    $timeLog = getData('timeLog.txt');

    //get student names from students.json
    $studentNames = Student::getStudentNames('students.json');

    //get arrivals from students.json
    $arrivals = getData('arrivals.json');
}

function isWritten($timeLog, $now, $studentName)
{
    if (isDataWritten($timeLog, $now, $studentName)) 
    {
        goToBase($base_url, 'Your arrival is already written!');
    }
}

function displayArrivals($timeLog)
{
	if ( !empty($timeLog)) 
    {        
        foreach ($timeLog as $data) 
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
}

function displayStudents($arrayStudents)
{
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
}

function displayArrivalTime($arrayArrivals, $timeLog)
{
	    if ( !empty($arrayArrivals)) 
    {        
        foreach ($arrayArrivals as $key => $data) 
        {
            $time = substr($data,-8);
            $delay = $timeLog[$key]['delay'];
            echo $time . ' ' .$delay;
            echo "</br>";
        }
    }
    else 
    {
        echo 'Nothing to display!';
        echo "</br>";
    }
}

function totalArrivals($studentName, $objArrivals, $array, $timeLog)
{
    if (is_string($studentName) && !empty($_GET['studentName'])) 
    {
    //get count of total arrivals
    $totalArrivals = $objArrivals -> getArrivals($array);
    }else {
        $totalArrivals = count($timeLog);
    }

	return $totalArrivals;
}
?>