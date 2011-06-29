<?php
	# =============================================
	# EGSO 2003 SEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
	# sec_newgui3.php
	# 28-jul-2004, last 28-jul-2004
	# ---------------------------------------------
	# SEC new iterative GUI (step 3)
	# =============================================
	define("DEBUG","0");
	
	require ("sec_global.php");
	import_request_variables("pg");
	
//	$tempdir = "/var/www/html/radiosun/sec/temp";

	echo (sec_header2("HELIO HEC GUI"));
//	print_r($_GET);
/*
	echo "<BR><BR>\n";
	foreach ( $_GET as $k=>$v) {
		echo "$k => $v<br>";
	}
*/
//	$sql="SELECT * FROM sgas_event WHERE nar>9500 AND nar<9600";
	$allcat=substr($allcat,0,strlen($allcat)-1);
	$sql="SELECT * FROM ".$allcat." WHERE ";

	
//	echo "<BR><BR>\n";
	$i=0;
	$flag=0;
	foreach ($_GET as $n=>$v) {
		if ($i>0) {
//			echo "$i: $n => $v<br>";
			if (substr($n,0,2)=="y_") $i=7;
			if (strpos($v,"ILIKE")>0) $flag=1;
			$sql.=$v;
			if ($flag and ($i==2)) {
				$flag=0;
			$sql.="%\'";
			}
		}
		if (substr($n,0,1)=="C") {
//			echo "$n => $v<br>";
			$sql.=$v;
			$i=4;
		}
		if ($i>0) $i--;
	}
//	$sql=substr($sql,0,strlen($sql)-5).";";//cut last AND/OR
//    echo ">$sql<";
    $i=strpos($sql,"ORDER");
    if ($i>0) {
        $sql2=substr($sql,0,$i-4)." ".substr($sql,$i,strlen($sql));
        $sql=$sql2;
    } else $sql=substr($sql,0,strlen($sql)-4);//cut last AND/OR
    $sql=$sql.";";
	
	//---------------------------------
//	echo '<form method=get action="sec_soap.php" target="_blank">';
	echo '<form method=get action="sec_newgui4.php" target="_blank">';	
	echo '<p><h4>Generated SQL query:</h4>';
	echo "Note: If you enter a name and a comment the query will be saved to SEC SQL query archive <BR>(after check by SEC administrator).<BR>";
	echo "<p>Enter author name (optional):<br><input type=text name=\"nick\"><br>";
	echo "<p>Enter comment (optional):<br><textarea name=\"comment\" cols=\"80\" rows=\"2\"></textarea><br>";
	
	echo '<input type="hidden" name="qtype" value="0">';
	
	echo "<p>Edit SQL string when nedded:<br>";
	echo "<textarea name=\"sql\" cols=\"80\" rows=\"6\">$sql</textarea>";
//	echo '<br><a href="sec_idiots_sql.html" target="_blank">Examples of how to use SQL on the EGSO Server</a>';

//	echo "<p><a href=\"javascript:history.go(-1)\">PREV</a>&nbsp;";
//	echo "<p><a href=\"javascript:history.go(-1)\"><img src=\"prev.png\" border=0></a>&nbsp;";
//	echo '<input type="submit" value="Search">';
//	echo '&nbsp;<input type="reset" value="Clear">';
	echo "<BR><BR><TABLE>";
	echo "<TD><a href=\"javascript:history.go(-1)\"><img src=\"newgui/prev.gif\" border=0></a>&nbsp;</TD>";
	echo '<TD><input type="submit" value="Search" class="button"></TD>';
	echo '<TD><input type="reset" value="Clear" class="button"></TD>';
	echo "</TABLE>";
	echo '</form>';	
	echo (sec_footer());

?>
