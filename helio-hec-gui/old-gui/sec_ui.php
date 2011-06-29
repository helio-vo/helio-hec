<?php	
	# =============================================
	# EGSO 2003,2004 - by Max Jurcev
	# INAF - Trieste Astronomical Observatory
	# ---------------------------------------------
	# SEC - UI main page
	# sec_ui.php
	# last 09-feb-2005, 22-may-2009
	# =============================================
	require ("sec_global.php");

	echo (sec_header("HELIO HEC - GUI"));	
	//---------------------------------
	// fill list of catalogue	
	$dbconn = pg_connect("dbname=hec");
	if (dbconn) {
		$sql_string="select * from sec_catalogue order by hec_groups_id,cat_id;";
		$result = pg_exec($dbconn,$sql_string);
		if ($result) {
			$catlist = array();
			while ($r = pg_fetch_row($result)) {
				$temparr = array($r[1] => $r[2]);
				$catlist = array_merge($catlist,$temparr);
			}
		}
	}
echo("xxx"); //r($catlist);
	//---------------------------------
  	echo '<form method=get action="sec_soap.php" target="_blank">';	
	echo '<p><h4>Preset search</h4>';
	echo '<input type="hidden" name="qtype" value="1">';
	
	echo '<p>Catalogue #1: ';
	echo '<select name="cat1',"$varname",'">';
        $j=1;
	foreach ($catlist as $k=>$v) {
	  echo "<option value=\"$k\"$s>$v";
          if (($j==5) or ($j==13) or ($j==30) or ($j== 32)) echo "<option disabled='disabled'>---------</option>";
          $j++;
        }
	echo '</select>';
	
	echo '<p><input type="checkbox" name="multi" value="1">Search also in catalogue #2';
	echo '<p>Catalogue #2: ';	
	echo '<select name="cat2',"$varname",'">';
        $j=1;
	foreach ($catlist as $k=>$v) {
	  echo "<option value=\"$k\"$s>$v";
          if (($j==4) or ($j==12) or ($j==18) or ($j== 21)) echo "<option disabled='disabled'>---------</option>";
          $j++;
	}
	echo '</select>';
	
	echo '<p><table><tr>';
	echo '<td><p>Starting date:</td><td>';
	sec_datetimesec("from",TRUE);
	echo '</td></tr><tr><td>Ending date:</td><td>';
	sec_datetimesec("to",FALSE);
	echo '</td></tr></table>';
	
  	echo '<p>NOAA Active region number:&nbsp;&nbsp;<input type="text" name="nar" size="7">';
	echo '<p><input type="submit" value="Search">';
	echo '&nbsp;<input type="reset" value="Reset">';
	echo '</form>';	
	echo '<hr>'; 		
	//---------------------------------
	echo '<p><h4>Advanced search GUI</h4>';
	echo '<a href="sec_newgui.php">Link to advanced search GUI interface</i></a>';
	echo '<hr>'; 
	//---------------------------------
  	echo '<form method=get action="sec_soap.php" target="_blank">';	
	echo '<p><h4>Free SQL query</h4>';
	echo '<input type="hidden" name="qtype" value="0">';
	
	echo '<p><textarea name="sql" cols="80" rows="6">SELECT * FROM sgas_event WHERE xray_class IS NOT NULL ORDER BY	time_start DESC  LIMIT 25</textarea>';
	echo '<br><a href="sec_idiots_sql.html" target="_blank">Examples of how to use SQL on the HELIO HEC Server</a>';

	echo '<p><input type="submit" value="Search">';
	echo '&nbsp;<input type="reset" value="Reset">';
	echo '<br><br><i>Here more details about tables and fields:';
	echo '&nbsp;<a href="sec_doc.htm" target="_blank">HTML documentation</i></a>';
	echo '</form>';	
	echo '<hr>'; 
	//---------------------------------
//	echo '<p><h4>New GUI</h4>';
//	echo '<a href="sec_newgui.php">Link to new GUI interface</i></a>';
//	echo '<hr>'; 
	//---------------------------------
	// insert data ranges
	echo '<p><h4>Catalogue description</h4>';
	$fname = "sec_range.txt";
	$f = fopen($fname,'r');
	$s = fread($f,filesize($fname));
	fclose($f);
	echo $s;
	$s="";
        echo "<br><IMG SRC=\"sec.png\"><br>";
	echo '<br><a href="hec_rev.txt" target="_blank">HEC Revision history</a>';
	echo '<br><br>This page has been visited ';
	echo (sec_counter());
	echo ' times since Jan, 1st 2009';
	
	echo (sec_footer());
?>
