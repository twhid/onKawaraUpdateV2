<?php
include("constants.php");

function month($n) 
{
	$timestamp = mktime(0, 0, 0, $n, 1, 2005);
	return date("M", $timestamp);
}

function validateDate($input) 
{
	preg_match('/\d{2}\.\d{2}\.\d{4}/',$input,$output); // this matches 2decimal.2decimal.4decimal and puts it in $output
	if ( count($output) == 1 ) 
	{	
		// there should be exactly 1 value in the array
		return $output[0]; 
	} 
	else
	{
		return "error";
	}
}

function getRandomNewsItem($rssObj) {
	$num = rand(0,(count($rssObj->items)-1));
	$title = $rssObj->items[$num]['title'];
	$link = $rssObj->items[$num]['link'];
	$itemSum = $rssObj->items[$num]['description'];
	return array("title"=>$title,"link"=>$link,"itemSum"=>$itemSum);
}

function writeDate($m,$d,$y) 
{
	if ( $m == "error" ) 
	{
		echo "$m";
	} 
	else 
	{
		echo "$m $d, $y";
	}
}

function getFileName($day,$month,$year)
{
	return pad($day, 2) . "." . pad($month, 2) . "." . pad($year, 4);
}

// This function pads a number to a fixed length by 
// adding leading zeroes. 
// e.g. "3" padded to length "2" returns "03"
function pad($s, $n) 
{ 
	$r = $s; 
	while ( strlen($r) < $n ) 
	{ 
		$r = "0" . $r; 
	} 
	return $r; 
}

?>