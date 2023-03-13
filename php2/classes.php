<?php

class Data 
{
    //define variables
    private $array;
    private $now;
    private $delay;
    private $studentName;

    //this function sets variables
	public function __construct($array, $now, $delay, $studentName) 
    {
		$this->array = $array;
        $this->now = $now;
        $this->delay = $delay;
        $this->studentName = $studentName;
	}

    public function pushData()
    {
	$studentName = trim($this->studentName);
	//arrival time and delay time
    $data = [
        'date'  =>  $this->now,
        'delay' =>  $this->delay,
		'studentName' =>  $studentName
        ];
		
	array_push($this->array, $data);

    file_put_contents('timeLog.txt', json_encode($this->array));

	return $this->array;
    }
}

class Student 
{     
    public static function pushStudent($studentName, $studentNames, $file) 
    {
        $studentName = trim($studentName);

        $array = Student::getStudentNames($file);

        $data = in_array($studentName, $array);

        if (!$data) {
        array_push($studentNames, $studentName);

        file_put_contents('students.json', json_encode($studentNames));
        }

    }

    public static function getStudentNames($file) 
    {
        //get student name from json file
        $dataInFile = file_get_contents($file);

        $array = json_decode($dataInFile, true) ?: [];

        return $array;
    }
}

// Program for instance methods

class Arrivals
{
	//define variables
    private $array;
    private $now;
    private $arrivals;

    //this function sets variables
	public function __construct($array, $now, $arrivals) 
    {
		$this->array = $array;
        $this->now = $now;
        $this->arrivals = $arrivals;
	}
	
	//This function push arrivals into arrivals.json file
	function pushArrivals() 
    {
		array_push($this->arrivals, $this->now);

	    file_put_contents('arrivals.json', json_encode($this->arrivals));	
	}

    //This function return total arrivals
	function getArrivals() 
    {
        $totalArrivals = count($this->array);

        return $totalArrivals;
	}
}