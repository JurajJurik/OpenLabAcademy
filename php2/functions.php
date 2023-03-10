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
		echo "It is not possible to arrive between 20:00 and 00:00 to school!";
	}
}

function hasDelay($now, $studentName, $schoolStartTime = '08:00:00', $possibleStartTime = '07:00:00')
{
	if (isset($studentName)) 
	{
		$studentName = trim($studentName);
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
				$delay = $studentName.' arrived on time!';
			}

		return $delay;
	} 
	else 
	{
		$schoolStart = date("d.m.Y").$schoolStartTime;
			if (strtotime($now) > strtotime($schoolStart)) 
			{
				$origin = new DateTime($schoolStart);
				$target = new DateTime($now);
				$interval = $origin->diff($target);

				$delay = 'Is late!';
			}
			else 
			{
				$delay = 'Arrived on time!';
			}

		return $delay;	
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

function pushData($array, $now, $delay, $studentName)
{
	$studentName = trim($studentName);
	//arrival time and delay time
    $data = [
        'date'  =>  $now,
        'delay' =>  $delay,
		'studentName' =>  $studentName
        ];
		
	array_push($array, $data);

    file_put_contents('timeLog.txt', json_encode($array));

	return $array;
}

function goToBase ($url, $message = 'Success!')
{
    header("Location: $url/index.php");
	echo $message;
    //die($message);
}

//check if data are already in array
function isWritten($array, $now, $studentName)
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
?>