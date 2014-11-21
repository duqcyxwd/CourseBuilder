<?php 
	/**
	* TimeTable
	*/
	class TimeTable
	{
		function __construct($max="3")
		{
			$this->courses = [];
			$this->maxmiumCourseTaking = $max;
			$this->limit = 100; // limit the operation that program will loop

		}

		public function duplicate(){
			// TODO check if this works
			$c = new TimeTable();
			$c->courses = $this->courses;
			return $c;
		}

		public function toString(){
			$r = "";
			foreach ($this->courses as $var) {
				$r .= $var->name."; ";
			}
			return $r;
		}

		public function addCourse($c)
		{
			if ($this->isFull()) {
				return false;
			}
			
			if ($this->checkTimeConflict($c)) {
				// TODO:add..........
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
			$numberOfCourses = sizeof($this->courses);
			$combination = [];
			foreach ($this->courses as $couse) {
				$combination[] = $couse->getCourseCombination();
				// d($couse->getCourseCombination());
			}

			$result = [];
			if ($numberOfCourses ==6) {
				foreach ($combination[0] as $key0 => $value0) {
					foreach ($combination[1] as $key1 => $value1) {
						foreach ($combination[2] as $key2 => $value2) {
							foreach ($combination[3] as $key3 => $value3) {
								foreach ($combination[4] as $key4 => $value4) {
									foreach ($combination[5] as $key5 => $value5) {
										$possibleSolution = [$value0, $value1, $value2, $value3, $value4, $value5];
										$possibleSolution = flatArray($possibleSolution);
										
										$this->limit--;
										if ($this->limit == 0) 
											return $result;

										if (!hasTimeConflictArray($possibleSolution)) {
											$result[] = $possibleSolution;
											return $result;
										} else {
											// pprint("timeconflict");
											// return $result;
										}
										
									}
								}
							}
						}
					}
				}
			} else if ($numberOfCourses ==3) {
				// 	echo "table size: ".
				// 	sizeof($combination[0])."*".
				// 	sizeof($combination[1])."*".
				// 	sizeof($combination[2])."=".
				// 	sizeof($combination[0])* sizeof($combination[1])* sizeof($combination[2]);

				foreach ($combination[0] as $key0 => $value0) {
					foreach ($combination[1] as $key1 => $value1) {
						foreach ($combination[2] as $key2 => $value2) {
							$possibleSolution = [$value0, $value1, $value2];
							$possibleSolution = flatArray($possibleSolution);
							
							$this->limit--;
							if ($this->limit == 0) 
								return $result;

							if (!hasTimeConflictArray($possibleSolution)) {
								$result = $possibleSolution;
								return $result;
							} else {
								// pprint("timeconflict");
								// return $result;
							}
										
						}
					}
				}

			} else {
				echo "table for this numberOfCourses is not implement yet";
			}
			return $resultInArray;
		}

		public function getATableInArray()
		{
			$result = $this->getATable();
			$resultInArray = [];
			foreach ($result as $class) {
				$resultInArray[] = ($class->toArray());
			}
			return [$resultInArray];
		}
	}



	/*
	 * @return true : there is conflict
	 *         false: there is no conflict		
	 */
	function hasTimeConflictArray($classArray)
	{
		foreach ($classArray as $keyA => $classA) {
			foreach ($classArray as $keyB => $classB) {
				if ($keyA != $keyB and hasClassTimeConflict($classA, $classB)) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * 
	 * taking two courseClasses
	 * @return true : there is conflict
	 *         false: there is no conflict
	 * @author 
	 **/
	function hasClassTimeConflict($classA, $classB) {
		$daysA = str_split($classA->days);
		$daysB = str_split($classB->days);

		if (array_intersect($daysA, $daysB) == []) {
			return false;
		} else {
			$A = $classA->start_Time;
			$B = $classA->end_Time;
			$C = $classB->start_Time;
			$D = $classB->end_Time;

			if (($A < $D and $D <= $B) or ($A <= $C and $C < $B)) {
				return true;
			}
			return false;
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
				$result = [$lectures];
				$this->courseCombination = sizeof($labs);
			}

			if (sizeof($result) == 1) {
				// d($result);
				// d($this);
			}
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

		public function toArray()
		{
			return [$this->courseName, $this->section, $this->days, $this->start_Time, $this->end_Time];
		}

		public function isLecture()
		{
			if (preg_match('([0-9])', $this->section)) {
				// pprint("Lab");
				return false;
			}
			return true;
		}
	}

?>
