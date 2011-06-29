<?php
	# =============================================
	# EGSO 2003 SEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
	# sec_newgui.php
	# 28-jul-2004, last 28-jul-2004
	# ---------------------------------------------
	# SEC new iterative GUI (select catalogs)
	# =============================================
	define("DEBUG","0");
	
	require ("sec_global.php");
//	$tempdir = "/var/www/html/radiosun/sec/temp";

	echo (sec_header2("HELIO HEC GUI"));
	
//	echo '<form method=get action="sec_soap.php" target="_blank">';	
	echo '<form method=get action="http://hec.ts.astro.it/sec_soap.php" target="_blank">';	
	echo '<p><h2>HEC SQL query archive:</h2>';
	echo '<input type="hidden" name="qtype" value="0">';
	
	echo '<select name="sql">';
	echo "<option value=\"SELECT * FROM sgas_event LIMIT 30;\">Select first 30 from sgas_event";
	echo "<option value=\"SELECT * FROM sgas_event WHERE nar=9503;\">Select nar=9503 from sgas_event";
	echo '</select>';
//	echo '&nbsp;<input type="submit" value="submit">';
	echo '&nbsp;&nbsp;<input type="submit" name="Submit" value="Submit" class="button">';
	echo '</form>';
	
		
	//---------------------------------
	// fill list of catalogue	
	$dbconn = pg_connect("dbname=hec");
	if (dbconn) {
		$sql_string="SELECT * FROM sec_catalogue ORDER by cat_id;";
		$result = pg_exec($dbconn,$sql_string);
		if ($result) {
			$catlist = array();
			while ($r = pg_fetch_row($result)) {
//				$temparr = array($r[0] => $r[2]);
				$temparr = array($r[2]);
				$catlist = array_merge($catlist,$temparr);
			}
		}
	}
//	print_r($catlist);
	//---------------------------------
	echo '<p><br><h2>OR</h2><br>';
//	echo "<H2>Select one or more catalogs:<BR></H2>";
	echo "<H2>Select catalog:<BR></H2>";
	echo "<form method=get action=\"sec_newgui2.php\">\n";
	$ch="checked=\"true\"";
	foreach ($catlist as $k=>$v) {
		$k++;
//		echo "<p><input type=\"checkbox\" name=\"$k\" value=\"1\">$v &nbsp;($k)<BR>";
//		echo "<p><input type=\"checkbox\" name=\"$k\" value=\"1\">$v<BR>";
		echo "<input type=\"radio\" $ch name=\"x\" value=\"$k\">$v<BR>\n";
		$ch="";
	}
	echo "<BR><TABLE>";
//	echo '<p><input type="submit" value="Next">';
	echo '<TD><input type="submit" value="Next" class="button"></TD>';
//	echo '&nbsp;<input type="reset" value="Clear">';
	echo '<TD><input type="reset" value="Clear" class="button"></TD>';
	echo "</TABLE>";
	echo '</form>';	
        
echo '<center><a href="sec_ui.php">Back</a></center>';

	echo (sec_footer());

?>
