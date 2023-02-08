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

function hasDelay($now, $studentName, $schoolStartTime = '08:00:00')
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
    $array = json_decode($dataInFile) ?: [];

	return $array;
}

function pushData($array, $now, $delay, $studentName)
{
	//arrival time and delay time
    $data = [
        'date'  =>  $now,
        'delay' =>  $delay,
		'studentName' =>  $studentName
        ];
	//array for writing into time log
    array_push($array, $data);

    file_put_contents('timeLog.txt', json_encode($array));

	return $data;
}

function goToBase ($url)
{
    header("Location: $url/index.php");
    die('success');
}
?>