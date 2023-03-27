<?php

class Data 
{
    //define variables
    private $timeLog;
    private $now;
    private $studentName;
    private $schoolStartTime;
    private $possibleStartTime;

    //this function sets variables
	public function __construct($timeLog, $now, $studentName, $schoolStartTime = '08:00:00', $possibleStartTime = '07:00:00') 
    {
		$this->timeLog = $timeLog;
        $this->now = $now;
        $this->studentName = $studentName;
        $this->schoolStartTime = $schoolStartTime;
        $this->possibleStartTime = $possibleStartTime;
	}

    public function pushData()
    {
        $studentName = trim($this->studentName);

        //check if student has delay
        $delay = $this->hasDelay($this->now);
        
        //arrival time and delay time
        $data = [
            'date'  =>  $this->now,
            'delay' =>  $delay,
            'studentName' =>  $studentName
            ];
            
        array_push($this->timeLog, $data);

        file_put_contents('timeLog.txt', json_encode($this->timeLog));

        return $this->timeLog;
    }

    private function hasDelay()
    {
        if (isset($this->studentName)) 
        {
            $studentName = trim($this->studentName);
            $schoolStart = date("d.m.Y").$this->schoolStartTime;
                if (strtotime($this->now) > strtotime($schoolStart)) 
                {
                    $origin = new DateTime($schoolStart);
                    $target = new DateTime($this->now);
                    $interval = $origin->diff($target);

                    $delayTime = vsprintf("%02d:%02d:%02d", [$interval->h, $interval->i, $interval->s]);

                    $delay = $studentName.' is '.$delayTime.' late!';
                }
                elseif (strtotime($this->now) < strtotime($this->possibleStartTime)) 
                {
                    $delay = $this->studentName.', did you really come to school before 7:00 AM ?!';
                }
                else 
                {
                    $delay = $this->studentName.' arrived on time!';
                }

            return $delay;
        } 
        else 
        {
            $schoolStart = date("d.m.Y").$this->schoolStartTime;
                if (strtotime($this->now) > strtotime($schoolStart)) 
                {
                    $origin = new DateTime($schoolStart);
                    $target = new DateTime($this->now);
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
    private $timeLog;
    private $now;
    private $arrivals;

    //this function sets variables
	public function __construct($timeLog, $now, $arrivals) 
    {
		$this->timeLog = $timeLog;
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
        $totalArrivals = count($this->timeLog);

        return $totalArrivals;
	}
}