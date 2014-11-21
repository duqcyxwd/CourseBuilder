<?php 


	/**
	* TimeTable
	*/
	class TimeTable
	{
		function __construct()
		{
			$this->courses = [];
			$this->maxmiumCourseTaking = 6;
		}

		public function duplicate(){
			// TODO check if this works
			$c = new TimeTable();
			$c->courses = $this->courses;
			return $c;
		}

		public function addCourse($c)
		{
			if ($this->isFull()) {
				return false;
			}
			
			if ($this->checkTimeConflict($c)) {
				// add..........
				array_push($this->courses, $c);
				return true;
			} else {
				return false;
			}
		}

		public function isFull()
		{
			return sizeof($this->courses) >= $this->maxmiumCourseTaking;
		}

		public function checkTimeConflict($value='')
		{
			// TODO
			if (sizeof($this->courses) == 0){
				return true;
			}
			// ....
			return true;
		}

		public function getATable()
		{
			$courseSize = sizeof($this->courses);
			$combination = [];
			foreach ($this->courses as $couse) {
				$combination[] = $couse->getCourseCombination();
				// d($couse->getCourseCombination());
			}

			$result = [];
			// foreach ($combination[0] as $key0 => $value0) {
			// 	foreach ($combination[1] as $key1 => $value1) {
			// 		foreach ($combination[2] as $key2 => $value2) {
			// 			foreach ($combination[3] as $key3 => $value3) {
			// 				foreach ($combination[4] as $key4 => $value4) {
			// 					foreach ($combination[5] as $key5 => $value5) {
			// 						$result[] = [$value0, $value1, $value2, $value3, $value4, $value5];
			// 					}
			// 				}
			// 			}
			// 		}
			// 	}
			// }
			
		}
	}

	/**
	* 
	*/
	class Course
	{
		function __construct($nameString = "")
		{
			$this->courseClasses = [];
			$this->name = $nameString;
			$this->courseCombination = 0;
		}

		public function addClass($course)
		{
			array_push($this->courseClasses, new CourseClass($course));
		}

		public function checkConflict($compareCourse)
		{
			// TODO:
			return true;
		}

		// get the combination of labs and lectures
		public function getCourseCombination(){
			$result = [];

			$lectures = [];
			$sectionCode = [];
			$labs = [];

			foreach ($this->courseClasses as $class) {
				if ($class->isLecture()) {
					array_push($lectures, $class);
					$sectionCode[] = $class->section;
				}
			}

			foreach ($this->courseClasses as $key =>$class) {
				if (!$class->isLecture()) {
					// $sec = preg_replace('/[0-9]+/', '', $class->section);
					$labSection = $class->section[0];
					
					if (in_array($labSection, $sectionCode) or $labSection == 'L') {
						array_push($labs, $class);
					} else {
						unset($this->courseClasses[$key]);
					}
				} 
			}

			foreach ($lectures as $lecture) {
				foreach ($labs as $lab) {
					// $labSection = preg_replace('/[0-9]+/', '', $lab->section);
					$labSection = $lab->section[0];
					if ($labSection == 'L' or ($lecture->section) == $labSection) {
						array_push($result, [$lecture, $lab]);
						$this->courseCombination++;
					}
				}
			}

			if (sizeof($labs) == 0) {
				$result = [$labs];
				$this->courseCombination = sizeof($labs);
			}

			d($this->courseClasses);
			d($result);


			return $result;
		}
	}
	
	/**
	* 
	*/
	class CourseClass
	{
		function __construct($course)
		{
			// $this->c = $course;
			$this->courseName = $course['Subject']." ".$course['CourseNumber'];
			$this->section = $course['Section'];
			$this->days = $course['Days'];
			$this->start_Time = intval($course['Start_Time']);
			$this->end_Time = intval($course['End_Time']);
		}

		public function isLecture()
		{
			if (preg_match('([0-9])', $this->section)) {
				// pprint("Lab");
				return false;
			}
			return true;
		}



		public function checkConflict($compareCourse)
		{
			$A = $this->start_Time;
			$B = $this->end_Time;
			$C = $compareCourse->start_Time;
			$D = $compareCourse->end_Time;

			if (($A < $D and $D <= $B) or ($A <= $C and $C < $B)) {
				return false;
			}
			return true;
		}
	}

?>
