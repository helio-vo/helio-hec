<?php
	# =============================================
	# EGSO 2003 SEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
	# sec_newgui4.php
	# 9-aug-2004, last 24-sep-2004
	# ---------------------------------------------
	# SEC new iterative GUI (step 4)
	# =============================================
	define("DEBUG","0");
	
	require ("sec_global.php");
	import_request_variables("pg");
	
//	$tempdir = "/var/www/html/radiosun/sec/temp";
	$sql=stripslashes($sql);
	$sql=stripslashes($sql);
	echo "<HEAD><meta http-equiv=\"REFRESH\" content=\"0;url=sec_soap.php?qtype=$qtype&sql=$sql\"></HEAD>";

//	echo "$nick<BR>\n";
//	echo "$comment<BR>\n";
//	echo "$qtype<BR>\n";
//	echo "$sql<BR>\n";

	$f1 = fopen("$tempdir/sql.txt",'a');
	$out=$nick."\t".$comment."\t".$sql."\n";
	fwrite($f1,$out);
	fclose($f1);


?>
