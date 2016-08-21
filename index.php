<?php
require_once("functions.php"); // includes constants.php
//
$today = date("M j, Y"); // today's date
$todayFn = date("d.m.Y"); // today's filename

if ( !isset($_GET['d']) ) 
{
	// if no date passed, redirect to TODAY
	header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?d=" . $todayFn);
	exit;
} 
else 
{
	// date was passed, get it
	$d = validateDate($_GET['d']);
	$file = DATE_FILES_DIR . $d . ".php";
	if ( file_exists($file) ) 
	{
		// FILE EXISTS -- SHOW IT AND EXIT
		include($file);
		exit;
	} 
	else if ( $d == $todayFn )
	{	
/*
================================================================================
REQUESTING TODAY, BUT IT DOESN'T EXIST -- BUILD IT, SAVE IT AND SHOW IT
================================================================================
*/

// get the parser
require_once(MAGPIE_DIR.'rss_fetch.inc');
// turn off error reporting
error_reporting(E_ERROR);

// DEFINE FEED URLS
require_once('news_source.php'); // news sources

// GET FEEDS
$topRSS = false;
$techRSS = false;
$cultRSS = false;

// try 5 times to score a feed
$topI = 0;
while ( !$topRSS ) {
	$topURL = $top[rand(0,(count($top)-1))];
	$topRSS = fetch_rss($topURL);
	$topI++;
	if ( $topI == 5 ) break;
}

$techI = 0;
while ( !$techRSS ) {
	$techURL = $tech[rand(0,(count($tech)-1))];
	$techRSS = fetch_rss($techURL);
	$techI++;
	if ( $techI == 5 ) break;
}

$cultI = 0;
while ( !$cultRSS ) {
	$cultURL = $cult[rand(0,(count($cult)-1))];
	$cultRSS = fetch_rss($cultURL);
	$cultI++;
	if ( $cultI == 5 ) break;
}

/* GET RANDOM TOP NEWS ITEM*/
$top = getRandomNewsItem($topRSS);

/* GET RANDOM TECH ITEM*/
$tech = getRandomNewsItem($techRSS);

/* GET RANDOM CULTURE ITEM*/
$cult = getRandomNewsItem($cultRSS);

$data = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>onKawaraUpdate (v2) $today</title>
	<link rel="stylesheet" href="s.css" type="text/css" media="all" />
	
	<script src="jquery.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function(){
			// date
   			$("#date").fadeIn(1000);
   			$("#date").click(function(){
   				$("#newsWrapper").fadeIn(500);
   				$("#nav").fadeIn(500);
   				$(this).fadeTo(500,0.2);
   			});
   			$("#closeNews").click(function(){
   				$("#date").fadeTo(500,1.0);
   				$("#newsWrapper").fadeOut(500);
   				$("#nav").fadeOut(500);
   			});
   			
   			// archives
   			$("#more").click(function(){
   				$("#archiveWrapper").fadeIn(500);
 				$("#archive").load("archives.php");
   			});
   			$("#closeArchive").click(function(){
   				$("#archiveWrapper").fadeOut(500);
   			});
   			
   			// about
   			$("#about").click(function(){
				$("#newsWrapper").fadeOut(500);
   				$("#aboutWrapper").fadeIn(500);
   			});
   			$("#closeAbout").click(function(){
   				$("#aboutWrapper").fadeOut(500);
   				$("#newsWrapper").fadeIn(500);
   			});
 		});
	</script>
</head>
<body>
<div id="nav" style="display:none;">
	<a id="more" href="#" onclick="return false;">more</a> | <a id="about" onclick="return false;" href="#">about</a>
</div>

<div id="archiveWrapper" style="display:none;">
	<div id="closeArchive" class="close">
		<a class="closeBtn" href="#" onclick="return false;">close</a>
	</div>
	<div id="archive">
	</div>
</div>

<div id="horz">
	
	<h1 id="date" style="display:none;">$today</h1>
	
	<div id="aboutWrapper" style="display:none;">
		<div id="closeAbout" class="close">
			<a class="closeBtn" href="#" onclick="return false;">close</a>
		</div>
		<div id="aboutSection">
			<p>
				<strong>onKawaraUpdate (v2) by <a href="http://mtaa.net">MTAA</a></strong>
			</p>
			<p>
				This art work updates and automates (via software) the process-oriented nature of 
				On Kawara's date paintings. The artist's labor is essential to process-oriented art. 
				What happens when that labor is removed?
			</p>			
			<p>
				<strong>instruction</strong><br />
				If this web site is visited by anyone on a particular day, a date 
				page is created. If no one visits on a particular day, no 
				date page is created. Click the large date for news clips from that day. 
				Click the 'more' link for archives.
			</p>
			<p>
				This work is licensed under the <a rel="license" href="http://creativecommons.org/licenses/GPL/2.0/">CC-GNU GPL</a>.
				<a href="oku_source.zip">Download the source code</a>.
			</p>
		</div>
	</div>
	
	<div id="newsWrapper" style="display:none;">
		<div class="close">
			<a id="closeNews" class="closeBtn" href="#" onclick="return false;">close</a>
		</div>
			<div id="news">
				<p>
					<a href="{$top['link']}">{$top['title']}</a>
					<br />
					<small class="newsTitle">{$topRSS->channel['title']}</small>
					<br />
					{$top['itemSum']}
				</p>
				<p>
					<a href="{$tech['link']}">{$tech['title']}</a>
					<br />
					<small class="newsTitle">{$techRSS->channel['title']}</small>
					<br />
					{$tech['itemSum']}
				</p>
				<p>
					<a href="{$cult['link']}">{$cult['title']}</a>
					<br />
					<small class="newsTitle">{$cultRSS->channel['title']}</small>
					<br />
					{$cult['itemSum']}
				</p>
			</div>
		</div>
	</div>
</body>
</html>
HTML;

$fh = fopen(DATE_FILES_DIR . $todayFn . ".php", "w");
fwrite($fh,$data);
fclose($fh);
include(DATE_FILES_DIR . $todayFn . ".php");
exit;

/*
================================================================================
END
================================================================================
*/
	} 
	else 
	{
		/* it's not today & the file doesn't exist -- show the error page */
		include( $_SERVER["DOCUMENT_ROOT"] . "/update/onkawara/thisdaydidnotexist.php" );
		exit;
	}
}
?>