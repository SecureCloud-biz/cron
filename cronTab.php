<?php
/**
 * 
 * @name  cronTab
 * @version 1.0
 * used to write to cron tab 
 * Minute/Hour/Day of Month/Month/Day of Week  
 * Run once a year, "0 0 1 1 *"
 * Run once a month, "0 0 1 * *"
 * Run once a week, "0 0 * * 0"
 * Run once a day, "0 0 * * *".
 * Run once an hour, "0 * * * *"
 * 
 */
class cronTab {
	/**
	 * @var string full path to cron tab
	 */
	var $crontab = 'stevewood';
	/**
	 * minute (0 - 59)
	 * @var string
	 */
	var $minute = 0;
	
	/**
	 * hour (0 - 23)
	 * @var string
	 */
	var $hour = 0;
	
	/**
	 * day of month (1 - 31)
	 * @var string
	 */
	var $dayOfMonth = '*';
	
	/**
	 * month (1 - 12) OR jan,feb,mar,apr...
	 * @var string
	 */
	var $month = '*';
	
	/**
	 * day of week (0 - 6) (Sunday=0 or 7) OR sun,mon,tue,wed,thu,fri,sat
	 * @var string
	 */
	var $dayOfWeek = '*';
	
	/**
	 * @var array
	 */
	var $jobs = array ();
	/**
	 * @param $contab the $contab to set
	 */
	public function setCrontab($crontab) {
		$this->crontab = $crontab;
	}

	/**
	 * @param $minute the $minute to set
	 */
	public function setMinute($minute) {
		$this->minute = $minute;
		return $this;
	}

	/**
	 * @param $hour the $hour to set
	 */
	public function setHour($hour) {
		$this->hour = $hour;
		return $this;
	}

	/**
	 * @param $dayOfMonth the $dayOfMonth to set
	 */
	public function setDayOfMonth($dayOfMonth) {
		$this->dayOfMonth = $dayOfMonth;
		return $this;
	}

	/**
	 * @param $month the $month to set
	 */
	public function setMonth($month) {
		$this->month = $month;
		return $this;
	}

	/**
	 * @param $dayOfWeek the $dayOfWeek to set
	 */
	public function setDayOfWeek($dayOfWeek) {
		$this->dayOfWeek = $dayOfWeek;
		return $this;
	}
	/**
	* save the jobs to disk, remove existing cron
	* @param boolean $includeOldJobs optional
	* @return boolean
	*/
	public function addJobs($includeOldJobs = true) {
		$contents  = implode("\n", $this->jobs);
		$contents .= "\n";
		
		if($includeOldJobs) {
			$contents .= $this->listJobs();
		//var_dump($contents,__LINE__);
		}
		if(is_writable($this->crontab)){
			file_put_contents($this->crontab, $contents, LOCK_EX);
			return true;
		}
		
	}
	/**
	* list current cron jobs
	* @return string
	*/
	function listJobs() {
		$currentjobs =  exec ("crontab -l;");
		return $currentjobs;			
	}
	
/**
	* Set entire time code with one function.
	* @param string $timeCode required
	* @return object
	*/
	function timeCode($timeCode) {
		list(
			$this->minute, 
			$this->hour, 
			$this->dayOfMonth, 
			$this->month, 
			$this->dayOfWeek
		) = explode(' ', $timeCode);
		return $this;
	}
	
	/**
	* Add job to the jobs array. Each time segment should be set before calling this method. The job should include the absolute path to the commands being used.
	* @param string $job required
	* @return object
	*/
		function pathToJob($job) {
			$this->jobs[] =	"{$this->minute} {$this->hour} {$this->dayOfMonth} {$this->month} {$this->dayOfWeek} $job";
			return $this;
		}
	/**
	 * @param string $type 
	 * @example / hour / day / week /month / year
	 * 
	 */
	function usePreset($type) {
		switch ($type) {
			case 'year' :
				$timeCode =  "0 0 1 1 *";
				break;
			case 'month' :
				$timeCode =  "0 0 1 * *";
				break;
			case "week" :
				$timeCode =  "0 0 * * 0";
				break;
			case "day" :
				$timeCode =  "0 0 * * *";
				break;
			case "hour" :
				$timeCode =  "0 * * * *";
				break;
			default :
				$timeCode = "0 * * * *";
		}
		return $this->timeCode($timeCode);
	}
}