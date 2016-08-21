<?php
require_once("functions.php"); // includes constants.php
include("calendar.php");

class OKUpdateCal extends Calendar
{ 	
	// create date links if the file exists
	function getDateLink($day, $month, $year) 
	{ 
		$file = DATE_FILES_DIR . getFileName($day, $month, $year) . ".php"; 
		if ( file_exists($file) ) 
		{ 
			return LOCAL_PATH . "/index.php?d=" . getFileName($day, $month, $year); 
		} 
		else 
		{ 
			return ""; 
		} 
	}
} 

// create calendar object
$cal = new OKUpdateCal;

$curMonth = date("m");
$curYear = date("Y");

// build calendars going back to June 2007
while ( $curYear >= 2007 ) 
{
	if ( $curMonth < 6 && $curYear == 2007 ) break;
	echo $cal->getMonthView($curMonth,$curYear);
	$curMonth--;
	if ( $curMonth < 1 ) 
	{
		$curMonth = 12;
		$curYear--;
	}
	
}



?>