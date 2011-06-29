<?php
	# =============================================
	# EGSO 2003 SEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
	# sec_newgui2.php
	# 28-jul-2004, last 28-jul-2004, 22-may-2009
	# ---------------------------------------------
	# SEC new iterative GUI (step2)
	# =============================================
	define("DEBUG","0");
	
	require ("sec_global.php");
	import_request_variables("pg");
	
//	$tempdir = "/var/www/html/radiosun/sec/temp";

	function t_op($i) {
		echo "<select name=\"t_op$i\">\n";
//	echo "<option value=\"\">\n";
		echo "<option value=\"=\">equal\n";
		echo "<option value=\">\">greater than\n";
		echo "<option value=\">=\">greater than or equal\n";
		echo "<option value=\"<\">less than\n";
		echo "<option value=\"<=\">less than or equal\n";
		echo "<option value=\"<>\">not equal\n";
		echo "</select>\n";
	}
	
	function b_op($i,$r) {
		echo "<select name=\"t_op$i\">\n";
		echo "<option value=\"=\">equal\n";
		echo "<option value=\"<>\">not equal\n";
		echo "</select>\n";
		
		echo "&nbsp;</TD><TD><select name=\"$r\">\n";
		echo "<option value=\"TRUE\">TRUE\n";
		echo "<option value=\"FALSE\">FALSE\n";
		echo "</select>\n";
	}
	
	function s_op($i) {
		echo "<select name=\"t_op$i\">\n";
//	echo "<option value=\"\">\n";
//		echo "<option value=\"ILIKE\">String match\n";
		echo "<option value=\" ILIKE '%\">Substring match\n";
		echo "</select>\n";
	}
	
	function andor_op($i) {
		echo "<select name=\"andor_op$i\">\n";
		echo "<option value=\" AND \">AND\n";
		echo "<option value=\" OR  \">OR\n";
		echo "</select>\n";
	}
	function orderby_op($param) {
// ORDER BY "column_name" [ASC, DESC] ascending descending
		echo "<select name=\"orderby_op\">\n";
        foreach ( $param as $k=>$v) {
	    	echo "<option value=\" $v \">$v\n";
        }
		echo "</select>\n";
		echo "<select name=\"dir_op\">\n";
    	echo "<option value=\" ASC \">ascending\n";
    	echo "<option value=\" DESC \">descending\n";
		echo "</select>\n";
	}

	function sec_datetimesec2($varname,$isBegin) {
		# Show a date time control
		# If $varname='xxx' then put results into variables
		# $y_xxx, $mo_xxx, $d_xxx, $h_xxx, $mi_xxx, $s_xxx
		# Preselect date with current date
		# If IsBegin==TRUE then preselect time with 00:00:00
		# else with 23:59:59

		$now = getdate();
		$arrmonth = array ('January','February','March','April','May','June',
			'July','August','September','October','November','December');
//		echo "&nbsp;";

		echo '<select name="y_',"$varname",'">';
		if ($isBegin) {
			if ($now['mon']==1) {
				$yy=-1;
				$mm=11;
			} else {
				$yy=0;
				$mm=-1;
			}
		} else {
			$yy=0;
			$mm=0;
		}
                $curyear=date("Y");
		for ($i=1975; $i<=$curyear; $i++) {
			if ($i==$now['year']+$yy)
				echo "<option value=\"'",$i,'-" selected>',$i;
			else
				echo "<option value=\"'",$i,'-">',$i;
		}
		echo '</select>';

		echo '<select name="mo_',"$varname",'">';
		for ($i=0; $i<12; $i++) {
			if (($i)==$now['mon']+$mm) //was ($i+1)==
				echo '<option value="',$i+1,'-" selected>',$arrmonth[$i];
			else
				echo '<option value="',$i+1,'-">',$arrmonth[$i];
		}
		echo '</select>';

		echo '<select name="d_',"$varname",'">';
		for ($i=1; $i<=31; $i++) {
			if ($i==$now['mday'])
				echo '<option value="',$i,' " selected>',$i;
			else
				echo '<option value="',$i,' ">',$i;
		}
		echo '</select>';

		echo "&nbsp;&nbsp;";

		if ($isBegin) {
			$hDef = 0;
			$miDef = 0;
			$sDef = 0;
		} else {
			$hDef = 23;
			$miDef = 59;
			$sDef = 59;
		}

		echo '<select name="h_',"$varname",'">';
		for ($i=0; $i<=23; $i++) {
			if ($i==$hDef) 
				echo '<option value="',$i,':" selected>';
			else
				echo '<option value="',$i,':">';
			echo sprintf('%02d',$i);
		}
		echo '</select>';

		echo '<select name="mi_',"$varname",'">';
		for ($i=0; $i<=59; $i++) {
			if ($i==$miDef)
				echo '<option value="',$i,':" selected>';
			else
				echo '<option value="',$i,':">';
			echo sprintf('%02d',$i);
		}
		echo '</select>';
		
		echo '<select name="s_',"$varname",'">';
		for ($i=0; $i<=59; $i++) {
			if ($i==$sDef)
				echo '<option value="',$i,"'",'" selected>';
			else
				echo '<option value="',$i,"'",'">';
			echo sprintf("%02d",$i);
		}
		echo '</select>';
  	}

		
	echo (sec_header2("HELIO HEC GUI"));
//	print_r($_GET);
	echo "<BR><BR>\n";
// 	foreach ( $_GET as $k=>$v) {
// 		echo "$k => $v<br>";
// 	}

//	print "<br><H2>Selected catalogs:</H2><br>";
	//---------------------------------


	$dbconn = pg_connect("dbname=hec");
	$attriblist=array(); // cav 
	if (dbconn) {
		$sql_string="SELECT * FROM sec_attribute;";
		$result = pg_exec($dbconn,$sql_string);
		if ($result) {
			$i=0;
			while ($r = pg_fetch_row($result)) {
				$temparr = array($r[0] => $r[1]);
				$attriblist = array_merge($attriblist,$temparr); // cav
			}
			
		}
		$attriblist="";
		$allcat="";
/*
		echo "<H2>Select one or more attributes:</H2><BR>\n";
		echo '<form method=get action="sec_newgui3.php">';	
		$sql_string="SELECT * FROM sec_catalogue;";
		$result = pg_exec($dbconn,$sql_string);
		$i = 0;
		if ($result) {
			while ($r = pg_fetch_row($result)) {
				if ($_GET[$r[0]]==1) {
					print "<BR><H4>$r[2] ($r[1]):</H4><BR>\n";
					$allcat.=$r[1].",";
					$sql_string2="SELECT * FROM sec_attribute,sec_cat_attr where cat_id=".$r[0]." AND sec_attribute.attr_id=sec_cat_attr.attr_id;";
					$result2 = pg_exec($dbconn,$sql_string2);
					while ($r2 = pg_fetch_row($result2)) {
//						$temparr = array($r[1] => $r[2]);
//						$attriblist = array_merge($attriblist,$temparr);
						$i++;
						echo "<input type=hidden name=\"P$i\" value=\"1\">";
						echo "<p><input type=\"checkbox\" name=\"C$i\" value=\"$r2[1]\">$r2[2]&nbsp;($r2[1])&nbsp;\n";
						if ($r2[3]=='t') { t_op($i); echo "&nbsp;"; sec_datetimesec($i,TRUE); }
						  else { t_op($i); echo "&nbsp;<input type=text name=".$r2[1].">"; }
//						if ($r[3]=='i') { t_op(); echo "<input type=text name=".$r[1].">"; }
						echo "&nbsp;\n";
						andor_op($i);
						echo "<BR>\n\n";
					}

				}
			}
		}
		echo "<input type=hidden name=\"allcat\" value=\"$allcat\">";
		echo '<p><input type="submit" value="Next">';
		echo '&nbsp;<input type="reset" value="Clear">';
		echo "</form>\n";
*/
//echo "<hr>\n";
//echo "<H2>2nd style<H2><br>\n";
		
		echo "<H2>Select one or more attributes from:</H2><BR>\n";
		echo '<form method=get action="sec_newgui3.php">';
		$sql_string="SELECT * FROM sec_catalogue;";
		$result = pg_exec($dbconn,$sql_string);
		
		$allcat="";
		$sqlcat="";
		if ($result) {
			while ($r = pg_fetch_row($result)) {
//				if ($_GET[$r[0]]==1) {
				if ($_GET["x"]==$r[0]) {
//					print "<H3>$r[0]: $r[2] ($r[1]):</H3><BR>\n";
					print "<H3>$r[2] ($r[1]):</H3><BR>\n";
					$catname[$r[0]]=$r[1];
					$allcat.=$r[1].",";
					$sqlcat.=" OR cat_id=".$r[0];
				}
			}
		}
		$sqlcat="(".substr($sqlcat,3).")";
		
		$sql_string="SELECT * FROM sec_cat_attr where $sqlcat;";
		$result = pg_exec($dbconn,$sql_string);
		if ($result) {
			$i=0;
			while ($r = pg_fetch_row($result)) {
				$cat_id[$i]=$r[0];
				$attr_id[$i]=$r[1];
				$i++;
			}
		}
		$max=$i-1;

		$i=0;
	}
    $param=array();
					$sql_string2="SELECT DISTINCT sec_attribute.* FROM sec_attribute,sec_cat_attr where $sqlcat AND sec_attribute.attr_id=sec_cat_attr.attr_id;";
//					print "=$sql_string2=\n";
					$result2 = pg_exec($dbconn,$sql_string2);
                    $rows=pg_num_rows($result2);
					echo "<TABLE border=\"1\" cellpadding=\"0\" cellspacing=\"0\" class=\"border_table\" >";
					echo "<TR class=\"cool\">";
//					echo "<TD></TD><TD><b>parameter</TD><TD><b>operator</TD><TD><b>value</TD><TD><b>chaining</TD>";
					echo "<th>&nbsp;</th><th>parameter (field name)</th><th>operator</th><th>value</th><th>chaining</th>";
					echo "</TR>";
					while ($r2 = pg_fetch_row($result2)) {
//						$temparr = array($r[1] => $r[2]);
//						$attriblist = array_merge($attriblist,$temparr);
						$i++;
						if ($i % 2 ==0) echo "<TR class=\"w\">";
							else echo "<TR class=\"b\">";
						echo "<TD>";
						for ($j=0; $j<=$max; $j++) {
//							if ($attr_id[$j]==$r2[0]) { $d=$cat_id[$j]; echo "$d,"; }
						}
//						$d=$r2[0];
//						echo "$d";
						echo "</TD>";
						echo "<TD>";
						echo "<input type=hidden name=\"P$i\" value=\"1\">";
						echo "<p><input type=\"checkbox\" name=\"C$i\" value=\"$r2[1]\">$r2[2]&nbsp;($r2[1])&nbsp;\n";
						array_push($param,$r2[1]);//order by
						echo "</TD>";
						echo "<TD>";
						if ($r2[3]=='t') { t_op($i); echo "&nbsp;"; echo "</TD><TD>"; sec_datetimesec2($i,TRUE); }
//						  else { t_op($i); echo "&nbsp;<input type=text name=".$r2[1].">"; }
						if (($r2[3]=='i') or ($r2[3]=='f')) { t_op($i); echo "&nbsp;</TD><TD><input type=text name=".$r2[1].">"; }
						if ($r2[3]=='b') {
							b_op($i,$r2[1]);
//							echo "<input type=text name=".$r2[1].">";
						}
						if ($r2[3]=='s') { s_op($i); echo "&nbsp;</TD><TD><input type=text name=".$r2[1].">"; }
						echo "&nbsp;\n";
						echo "</TD>";
						echo "<TD>";
						if ($i<$rows) andor_op($i);
                          else echo "<input type=hidden name=\"andor_op$i\" value=\" \">";
						echo "</TD>";
						echo "</TR>\n";
						//echo "<BR>\n\n";
					}
        $i++;
		if ($i % 2 ==0) echo "<TR class=\"w\">";
			else echo "<TR class=\"b\">";
        echo "<TD></TD>";
        echo "<TD>";
        echo "<input type=hidden name=\"P$i\" value=\"1\">";
        echo "<p><input type=\"checkbox\" name=\"C$i\" value=\"ORDER BY\">ORDER BY&nbsp;\n";
        echo "<input type=hidden name=\"dummya$i\" value=\"\">";
//        echo "<input type=hidden name=\"dummyb$i\" value=\"\">";
        echo "<TD>";           
        orderby_op($param);
        echo "</TD><TD></TD><TD></TD>";
        echo "</TR>\n";
		echo "</TABLE>";
		echo "<input type=hidden name=\"allcat\" value=\"$allcat\">";

		echo "<BR><TABLE>";
		echo "<TD><a href=\"javascript:history.go(-1)\"><img src=\"newgui/prev.gif\" border=0></a>&nbsp;</TD>";
//		echo '<input type="submit" value="Next">';
//		echo '&nbsp;<input type="reset" value="Clear">';
		echo '<TD><input type="submit" value="Next" class="button"></TD>';
		echo '<TD><input type="reset" value="Clear" class="button"></TD>';
		echo "</TABLE>";
		
		echo "</form>\n";
	
	echo (sec_footer());
//echo "<hr>$rows\n";
?>
