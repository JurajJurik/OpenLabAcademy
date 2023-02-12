<?php

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
		die("It is not possible to arrive between 20:00 and 00:00 to school!");
	}
}

function hasDelay($now, $studentName, $schoolStartTime = '08:00:00', $possibleStartTime = '07:00:00')
{
	$schoolStart = date("d.m.Y").$schoolStartTime;
		if (strtotime($now) > strtotime($schoolStart)) 
		{
			$origin = new DateTime($schoolStart);
    		$target = new DateTime($now);
    		$interval = $origin->diff($target);

			$delayTime = vsprintf("%02d:%02d:%02d", [$interval->h, $interval->i, $interval->s]);

			$delay = $studentName.' is '.$delayTime.' late!';
		}
		elseif (strtotime($now) < strtotime($possibleStartTime)) 
		{
			$delay = $studentName.', did you really come to school before 7:00 AM ?!';
		}
		else 
		{
			$delay = '';
		}

	return $delay;
}

function getData($file)
{
	//get data from time log
    $dataInFile = file_get_contents($file);

    //decode data, if time log is empty, it return empty array
    $array = json_decode($dataInFile, true) ?: [];

	return $array;
}

function getStudentNames($file)
{
	//get student name from json file
	$dataInFile = file_get_contents($file);

	$array = json_decode($dataInFile, true) ?: [];

	return $array;
}

function pushData($array, $now, $delay, $studentName, $studentNames)
{
	//arrival time and delay time
    $data = [
        'date'  =>  $now,
        'delay' =>  $delay,
		'studentName' =>  $studentName
        ];
	
	if (is_string($data['studentName'])) 
	{	
	array_push($array, $data);

	$totalArrivals = array_push($studentNames, $data['studentName']);

    file_put_contents('timeLog.txt', json_encode($array));
	file_put_contents('students.json', json_encode($studentNames));

	return $totalArrivals;
	}
	else 
	{
		print_r ('That is not name!');
	}
}

function goToBase ($url)
{
    header("Location: $url/index.php");
    die('success');
}

//check if data are already in array
function isWritten($array, $data)
{
	$intersectArray = [];

	foreach ($array as $value) 
	{
		$arrayDate = new DateTimeImmutable($value['date']);
		$arrayDate = $arrayDate->format('d.m.Y');

		$arrayString = $arrayDate.' '.$value['studentName'];

		array_push($intersectArray, $arrayString);
	}

	$dataDate = new DateTimeImmutable($data['date']);
	$dataDate = $dataDate->format('d.m.Y');

	$dataString = [$dataDate.' '.$data['studentName']];
	
	$compare = array_intersect($intersectArray, $dataString);

	if ($compare) 
	{
		print_r($data['studentName'].', your arrive is already recorded for today!');
	}
}
?>