<?php
require_once("functions.php"); // includes constants.php
$day = "";
$year = "";

$d = validateDate($_GET['d']);

if ( $d != "error" ) {
	$d = explode(".",$d);
	$month = month($d[1]);
	$day = $d[0];
	$year = $d[2];
} else {
	$month = "error";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title>onKawaraUpdate (v2) :: <?php writeDate($month,$day,$year); ?> doesn't exist</title>
	<link rel="stylesheet" href="s.css" type="text/css" media="all" />
	
	<script src="jquery.js" type="text/javascript"></script>
	<script type="text/javascript">
		$(function(){
   			$("h1").fadeIn(1000);
   			$("h1").css("cursor","default");		
 		});
	</script>
</head>
<body>
<div id="archiveWrapper">
	<div id="archive">
	<?php
	include("archives.php");
	?>
	</div>
</div>

<div id="horz">
	<h1 id="date" style="display:none;"><?php writeDate($month,$day,$year); ?> <small>doesn't exist</small></h1>
</div>
</body>
</html>

