<?php
	# =============================================
	# EGSO 2003,2004 - by Max Jurcev
	# INAF - Trieste Astronomical Observatory
	# ---------------------------------------------
	# SEC - SOAP client for the web user
	# sec_soap.php
	# last update 09-feb-2005
	# =============================================
	if (PHP_VERSION>='5')
 		require_once('domxml-php4-to-php5.php');
		
	require_once('nusoap.php');
	require ("sec_global.php");
	require ("sec_votable.php");
	import_request_variables("pg");

	echo (sec_header("HELIO HEC - Results"));
	$xmldate = date("Ymd_His");
	$xml1 = "hec1_$xmldate.xml";
	$xml2 = "hec2_$xmldate.xml";
	$txt1 = "hec1_$xmldate.txt";
	$txt2 = "hec2_$xmldate.txt";
	$max_xml_size = 100*1024;		// 100 KB is the max XML file size
	$max_xml_error = "The VOTable result file is too large to display on this page, but the results can be downloaded from the XML result file link above. It is possible to reduce number of results by shortening the data selection interval or refining your query";
//	$sql=str_replace("\\","",$sql);

	// prepare sql query string:
	if ($qtype==0) {
		$s1= $sql;
	} else {
		while ((!checkdate($mo_from,$d_from,$y_from)) and ($d_from>0)) { $d_from--; }
		if (!checkdate($mo_from,$d_from,$y_from)) {
			sec_error('Start time is invalid, please verify');
			exit;
		}
		while ((!checkdate($mo_to,$d_to,$y_to)) and ($d_to>0)) { $d_to--; }
		if (!checkdate($mo_to,$d_to,$y_to)){
			sec_error('End time is invalid, please verify');
			exit;
		}
		if (mktime($h_from,$mi_from,$s_from,$mo_from,$d_from,$y_from) >
			mktime($h_to,$mi_to,$s_to,$mo_to,$d_to,$y_to)) {
			sec_error('End date is greater than start date, please verify');
			exit;
		}
		$from = sprintf("%04d-%02d-%02d %02d:%02d:%02d",
			$y_from,$mo_from,$d_from,$h_from,$mi_from,$s_from);
		$to = sprintf("%04d-%02d-%02d %02d:%02d:%02d",
			$y_to,$mo_to,$d_to,$h_to,$mi_to,$s_to);
		$query_time = "time_start>='$from' AND time_start<='$to'";
		if ($nar<>"") 
			$query_nar = " OR nar=$nar";
		$w = $query_time.$query_nar;
		$s1 = "SELECT * FROM $cat1 WHERE $w ORDER BY time_start;";		
		$s2 = "SELECT * FROM $cat2 WHERE $w ORDER BY time_start;";
	}
	
	$error = 0;
	echo "The VOTable (XML) result file is <a href=\"xml/$xml1\"><B>hec1_$xmldate.xml</B></a>";
	echo "<br>The TXT result file is <a href=\"xml/$txt1\"><B>hec1_$xmldate.txt</B></a><p>";
	if ($multi=='1' && $qtype<>0) {
		echo "The VOTable (XML) result file for catalogue #2 is <a href=\"xml/$xml2\"><b>sec2_$xmldate.xml</b></a><br>";
		echo "The TXT result file for catalogue #2 is <a href=\"xml/$txt2\"><b>sec2_$xmldate.txt</b></a><p>";
		echo "SQL query #1: <b>$s1</b><p>";
		echo "SQL query #2: <b>$s2</b><p>";
		
		// query 1
		$parameters = array('sql_string'=>$s1);
		$soapclient = new soapclientnu("$urldir/sec_server.php");
		$result = $soapclient->call('sql',$parameters);	
		if (strlen($result)>$max_xml_size || $result=='') {
			$error = 1;
			$error_string = $max_xml_error;	
		} else {
			$p = votable_parse($result);
			if ($p=='') {
				$error = 1;
				$error_string = "XML parsing error";		
			} else {
				echo $p;
				// save XML file
				$res = fopen("$secdir/xml/$xml1",'w');
				fwrite($res,$result);
				fclose($res);
				// save TXT file
				$vot = votable_parse_text($result);
				$res = fopen("$secdir/xml/$txt1",'w');
				fwrite($res,$vot);
				fclose($res);	
			}
		}
		unset($soapclient);
		
		echo '<br>';
		// query 2
		$parameters = array('sql_string'=>$s2);
		$soapclient = new soapclientnu("$urldir/sec_server.php");
		$result2 = $soapclient->call('sql',$parameters);	
		if (strlen($result2)>$max_xml_size || $result2=='') {
			$error = 1;
			$error_string = $max_xml_error;
		} else {
			$p = votable_parse($result2);
			if ($p=='') {
				$error = 1;
				$error_string = "XML parsing error";		
			} else {
				echo $p;
				// save XML file
				$res = fopen("$secdir/xml/$xml2",'w');
				fwrite($res,$result2);
				fclose($res);
				// save TXT file
				$vot = votable_parse_text($result2);
				$res = fopen("$secdir/xml/$txt2",'w');
				fwrite($res,$vot);
				fclose($res);	
			}
		}
		unset($soapclient);	
	} else {
		echo "SQL query: <b>$s1</b><p>";
				
		// query 1
		$parameters = array('sql_string'=>$s1);
		$soapclient = new soapclientnu("$urldir/sec_server.php");
		$result = $soapclient->call('sql',$parameters);
		$result2=$result;
		$result = str_replace("&","and",$result2);//SoHO Campaign!
		// save XML file
		$res = fopen("$secdir/xml/$xml1",'w');
		fwrite($res,$result);
		fclose($res);
		if (strlen($result)>$max_xml_size) {
			$error = 1;
			$error_string = $max_xml_error;
		} else {
			if (($result=='') OR (strpos($result,"error")>0)) {
				$error = 1;
				//I can't understand why $result is empty when error occurs in sec_server.php !!!
//				$f1 = fopen("$secdir/temp/testserver.xml",'r');
//				$error_string = fgets($f1);
//				fclose($f1);
				$error_string = $result;
			} else {
				$p = votable_parse($result);
				if ($p=='') {
					$error = 1;
					$error_string = "XML parsing error";		
				} else {
					echo $p;
					// save TXT file
					$vot = votable_parse_text($result);
					$res = fopen("$secdir/xml/$txt1",'w');
					fwrite($res,$vot);
					fclose($res);	
				}
			}
		}
		unset($soapclient);		
	}
	if ($error>0) {
		echo "<H2>Error</H2><H3>$error_string</H3><br>";
	}
	
	echo (sec_footer());
?>
