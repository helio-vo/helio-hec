<?php
	# =============================================
	# EGSO 2003,2004 - by Max Jurcev, A.Santin
	# INAF - Trieste Astronomical Observatory
	# ---------------------------------------------
	# SEC - Server
	# sec_server.php
	# last 19-oct-2004
	# =============================================
	
	require_once('nusoap.php'); 
	import_request_variables("pg");
    
    $secdir = "/var/www/html/hec";
    $tempdir = "$secdir/temp";

	$error_reporting_old = error_reporting (0);//disabling error reporting allow error capture

	$s = new soap_server;
	$s->register('sql');
	$s->register('describeCatalogue');
	$s->register('describeTable');
	$s->register('countRows');

	function sql($sql_string) {
		$date = date("Ymd His");
		$cr = "";
		//$cr = "\n";
		// associative array with VOTable data types:
		$fieldtype = array(
			"integer"=>"int",			
			"int4"=>"int",
			"float"=>"float",			
			"float8"=>"float",
			"varchar"=>"char",
			"timestamp"=>"char",
			"serial"=>"int",
			"bool"=>"boolean",
			"boolean"=>"boolean"
		);
		
		$r = '<?xml version="1.0"?>';
		$sql_string = str_replace("\\","",$sql_string);
		$dbconn = pg_connect("dbname=hec");
		if (!dbconn) {			
			$r .= "<ERROR>unable to connect database</ERROR>";
		} else {
			$result = pg_query($dbconn,$sql_string);
			if (!$result) {
				$err = pg_last_error($dbconn);
				$r .= '<!DOCTYPE VOTABLE SYSTEM "http://us-vo.org/xml/VOTable.dtd">';
				$r .= '<VOTABLE version="1.0">';
				$r .= '<DESCRIPTION>';
				$r .= "Created: $date";
				$r .= "</DESCRIPTION>$cr";
				$r .= "<DEFINITIONS></DEFINITIONS>$cr";
				$r .= '<RESOURCE type="error">';
				$r .= '<DESCRIPTION>';
				$r .= " EGSO SEC Server $err";
				$r .= "</DESCRIPTION>$cr";
				$r .= "<INFO name=\"error_message\" value=\"$err\"/>";
				$r .= '</RESOURCE>';
				$r .= '</VOTABLE>';
			} else {
				$n = pg_num_fields($result);
				$rows = pg_num_rows($result);
				$r .= '<!DOCTYPE VOTABLE SYSTEM "http://us-vo.org/xml/VOTable.dtd">';
				$r .= '<VOTABLE version="1.0">';
				$r .= "<DEFINITIONS></DEFINITIONS>$cr";
				$r .= '<RESOURCE>';
				$r .= '<DESCRIPTION>';
				$r .= "EGSO SEC Server";
				//$r .= "<ROWS>$rows</ROWS>$cr";
				$sql_string_modified = str_replace("<","&lt;",$sql_string);
				$sql_string_modified = str_replace(">","&gt;",$sql_string_modified);
				//$r .= "<SQL_STRING>$sql_string_modified</SQL_STRING>";
				$d = date("Y-m-d H:i:s");
				//$r .= "<CREATION_DATE>$d</CREATION_DATE>$cr";
				$r .= "</DESCRIPTION>$cr";
				$r .= '<TABLE>';
				for ($j=0; $j<$n; $j++) {
					$a = pg_field_name($result,$j);
					$tt = explode(" ",pg_field_type($result,$j));
					$t = $fieldtype[$tt[0]];
					if (is_null($t))
						$t='char';
					if ($t=='char')
						$as = ' arraysize="3400"';
					else 
						$as = '';			
					$r .= "<FIELD name=\"$a\" datatype=\"$t\"$as/>";			
					$r .= $cr;
				}
				$r .= '<DATA>';
				$r .= "<TABLEDATA>";
				for ($i=0; $i<$rows; $i++) {
					$r .= '<TR>';
					$row = pg_fetch_row($result,$i);
					for ($j=0; $j<$n; $j++) {
						$r .= ("<TD>$row[$j]</TD>");
					}
					$r .= "</TR>";
					$r .= $cr;
				}
				$r .= '</TABLEDATA>';
				$r .= '</DATA>';
				$r .= '</TABLE>';
				$r .= '</RESOURCE>';
				$r .= '</VOTABLE>';
			}
		}
		
		// write 'testserver.xml' for debugging
		$res = fopen("$tempdir/testserver.xml",'w');
		//fwrite($res,sprintf("numfields=%d numrows=%d\n",$n,$rows));
		fwrite($res,$r);
		fclose($res);
	
		return ($r);		
	}//sql
	
	function describeCatalogue() {
		$sql_string = "select * from sec_catalogue;";
		$date = date("Ymd His");
		$fieldtype = array(
			"integer"=>"int",			
			"int4"=>"int",
			"float"=>"float",			
			"float8"=>"float",
			"varchar"=>"char",
			"timestamp"=>"char",
			"serial"=>"int",
			"bool"=>"boolean",
			"boolean"=>"boolean"
		);
		$r = '<?xml version="1.0"?>';
		$sql_string = str_replace("\\","",$sql_string);
		$dbconn = pg_connect("dbname=hec");
		if (!dbconn) {			
			$r .= "<ERROR>unable to connect database</ERROR>";
		} else {
			$result = pg_query($dbconn,$sql_string);
			if (!$result) {
				$err = pg_last_error($dbconn);
				$r .= '<!DOCTYPE VOTABLE SYSTEM "http://us-vo.org/xml/VOTable.dtd">';
				$r .= '<VOTABLE version="1.0">';
				$r .= '<DESCRIPTION>';
				$r .= "Created: $date";
				$r .= "</DESCRIPTION>$cr";
				$r .= "<DEFINITIONS></DEFINITIONS>$cr";
				$r .= '<RESOURCE type="error">';
				$r .= '<DESCRIPTION>';
				$r .= " EGSO SEC Server $err";
				$r .= "</DESCRIPTION>$cr";
				$r .= "<INFO name=\"error_message\" value=\"$err\"/>";
				$r .= '</RESOURCE>';
				$r .= '</VOTABLE>';
			} else {
				$n = pg_num_fields($result);
				$rows = pg_num_rows($result);
				$r .= '<!DOCTYPE VOTABLE SYSTEM "http://us-vo.org/xml/VOTable.dtd">';
				$r .= '<VOTABLE version="1.0">';
				$r .= "<DEFINITIONS></DEFINITIONS>$cr";
				$r .= '<RESOURCE>';
				$r .= '<DESCRIPTION>';
				$r .= "EGSO SEC Server";
				//$r .= "<ROWS>$rows</ROWS>$cr";
				$sql_string_modified = str_replace("<","&lt;",$sql_string);
				$sql_string_modified = str_replace(">","&gt;",$sql_string_modified);
				//$r .= "<SQL_STRING>$sql_string_modified</SQL_STRING>";
				$r .= "</DESCRIPTION>$cr";
				$r .= '<TABLE>';
				for ($j=0; $j<$n; $j++) {
					$a = pg_field_name($result,$j);
					$tt = explode(" ",pg_field_type($result,$j));
					$t = $fieldtype[$tt[0]];
					if (is_null($t))
						$t='char';
					if ($t=='char')
						$as = ' arraysize="3400"';
					else 
						$as = '';			
					$r .= "<FIELD name=\"$a\" datatype=\"$t\"$as/>";			
					$r .= $cr;
				}
				$r .= '<DATA>';
				$r .= "<TABLEDATA>";
				for ($i=0; $i<$rows; $i++) {
					$r .= '<TR>';
					$row = pg_fetch_row($result,$i);
					for ($j=0; $j<$n; $j++) {
						$r .= ("<TD>$row[$j]</TD>");
					}
					$r .= "</TR>";
					$r .= $cr;
				}
				$r .= '</TABLEDATA>';
				$r .= '</DATA>';
				$r .= '</TABLE>';
				$r .= '</RESOURCE>';
				$r .= '</VOTABLE>';
			}
		}
		
		// write 'testserver.xml' for debugging
		$res = fopen("$tempdir/testserver.xml",'w');
		//fwrite($res,sprintf("numfields=%d numrows=%d\n",$n,$rows));
		fwrite($res,$r);
		fclose($res);
	
		return ($r);		
	}//describeCatalogue

	function describeTable($table) {
		$sql_string = "SELECT sec_attribute.name,sec_attribute.description FROM sec_cat_attr LEFT JOIN sec_attribute ON (sec_cat_attr.attr_id=sec_attribute.attr_id) LEFT JOIN sec_catalogue ON (sec_cat_attr.cat_id=sec_catalogue.cat_id) WHERE sec_catalogue.name='$table';";
		$date = date("Ymd His");
		$fieldtype = array(
			"integer"=>"int",			
			"int4"=>"int",
			"float"=>"float",			
			"float8"=>"float",
			"varchar"=>"char",
			"timestamp"=>"char",
			"serial"=>"int",
			"bool"=>"boolean",
			"boolean"=>"boolean"
		);
		$r = '<?xml version="1.0"?>';
		$sql_string = str_replace("\\","",$sql_string);
		$dbconn = pg_connect("dbname=hec");
		if (!dbconn) {			
			$r .= "<ERROR>unable to connect database</ERROR>";
		} else {
			$result = pg_query($dbconn,$sql_string);
			if (!$result) {
				$err = pg_last_error($dbconn);
				$r .= '<!DOCTYPE VOTABLE SYSTEM "http://us-vo.org/xml/VOTable.dtd">';
				$r .= '<VOTABLE version="1.0">';
				$r .= '<DESCRIPTION>';
				$r .= "Created: $date";
				$r .= "</DESCRIPTION>$cr";
				$r .= "<DEFINITIONS></DEFINITIONS>$cr";
				$r .= '<RESOURCE type="error">';
				$r .= '<DESCRIPTION>';
				$r .= " EGSO SEC Server $err";
				$r .= "</DESCRIPTION>$cr";
				$r .= "<INFO name=\"error_message\" value=\"$err\"/>";
				$r .= '</RESOURCE>';
				$r .= '</VOTABLE>';
			} else {
				$n = pg_num_fields($result);
				$rows = pg_num_rows($result);
				$r .= '<!DOCTYPE VOTABLE SYSTEM "http://us-vo.org/xml/VOTable.dtd">';
				$r .= '<VOTABLE version="1.0">';
				$r .= "<DEFINITIONS></DEFINITIONS>$cr";
				$r .= '<RESOURCE>';
				$r .= '<DESCRIPTION>';
				$r .= "EGSO SEC Server";
				//$r .= "<ROWS>$rows</ROWS>$cr";
				$sql_string_modified = str_replace("<","&lt;",$sql_string);
				$sql_string_modified = str_replace(">","&gt;",$sql_string_modified);
				//$r .= "<SQL_STRING>$sql_string_modified</SQL_STRING>";
				$r .= "</DESCRIPTION>$cr";
				$r .= '<TABLE>';
				for ($j=0; $j<$n; $j++) {
					$a = pg_field_name($result,$j);
					$tt = explode(" ",pg_field_type($result,$j));
					$t = $fieldtype[$tt[0]];
					if (is_null($t))
						$t='char';
					if ($t=='char')
						$as = ' arraysize="3400"';
					else 
						$as = '';			
					$r .= "<FIELD name=\"$a\" datatype=\"$t\"$as/>";			
					$r .= $cr;
				}
				$r .= '<DATA>';
				$r .= "<TABLEDATA>";
				for ($i=0; $i<$rows; $i++) {
					$r .= '<TR>';
					$row = pg_fetch_row($result,$i);
					for ($j=0; $j<$n; $j++) {
						$r .= ("<TD>$row[$j]</TD>");
					}
					$r .= "</TR>";
					$r .= $cr;
				}
				$r .= '</TABLEDATA>';
				$r .= '</DATA>';
				$r .= '</TABLE>';
				$r .= '</RESOURCE>';
				$r .= '</VOTABLE>';
			}
		}
		
		// write 'testserver.xml' for debugging
		$res = fopen("$tempdir/testserver.xml",'w');
		//fwrite($res,sprintf("numfields=%d numrows=%d\n",$n,$rows));
		fwrite($res,$r);
		fclose($res);
	
		return ($r);		
	}//describeTable


	function countRows($table) {
		$sql_string = "select count(*) from $table;";
		$date = date("Ymd His");
		$fieldtype = array(
			"integer"=>"int",			
			"int4"=>"int",
			"float"=>"float",			
			"float8"=>"float",
			"varchar"=>"char",
			"timestamp"=>"char",
			"serial"=>"int",
			"bool"=>"boolean",
			"boolean"=>"boolean"
		);
		$r = '<?xml version="1.0"?>';
		$sql_string = str_replace("\\","",$sql_string);
		$dbconn = pg_connect("dbname=hec");
		if (!dbconn) {			
			$r .= "<ERROR>unable to connect database</ERROR>";
		} else {
			$result = pg_query($dbconn,$sql_string);
			if (!$result) {
				$err = pg_last_error($dbconn);
				$r .= '<!DOCTYPE VOTABLE SYSTEM "http://us-vo.org/xml/VOTable.dtd">';
				$r .= '<VOTABLE version="1.0">';
				$r .= '<DESCRIPTION>';
				$r .= "Created: $date";
				$r .= "</DESCRIPTION>$cr";
				$r .= "<DEFINITIONS></DEFINITIONS>$cr";
				$r .= '<RESOURCE type="error">';
				$r .= '<DESCRIPTION>';
				$r .= " EGSO SEC Server $err";
				$r .= "</DESCRIPTION>$cr";
				$r .= "<INFO name=\"error_message\" value=\"$err\"/>";
				$r .= '</RESOURCE>';
				$r .= '</VOTABLE>';
			} else {
				$n = pg_num_fields($result);
				$rows = pg_num_rows($result);
				$r .= '<!DOCTYPE VOTABLE SYSTEM "http://us-vo.org/xml/VOTable.dtd">';
				$r .= '<VOTABLE version="1.0">';
				$r .= "<DEFINITIONS></DEFINITIONS>$cr";
				$r .= '<RESOURCE>';
				$r .= '<DESCRIPTION>';
				$r .= "EGSO SEC Server";
				//$r .= "<ROWS>$rows</ROWS>$cr";
				$sql_string_modified = str_replace("<","&lt;",$sql_string);
				$sql_string_modified = str_replace(">","&gt;",$sql_string_modified);
				//$r .= "<SQL_STRING>$sql_string_modified</SQL_STRING>";
				$r .= "</DESCRIPTION>$cr";
				$r .= '<TABLE>';
				for ($j=0; $j<$n; $j++) {
					$a = pg_field_name($result,$j);
					$tt = explode(" ",pg_field_type($result,$j));
					$t = $fieldtype[$tt[0]];
					if (is_null($t))
						$t='char';
					if ($t=='char')
						$as = ' arraysize="3400"';
					else 
						$as = '';			
					$r .= "<FIELD name=\"$a\" datatype=\"$t\"$as/>";			
					$r .= $cr;
				}
				$r .= '<DATA>';
				$r .= "<TABLEDATA>";
				for ($i=0; $i<$rows; $i++) {
					$r .= '<TR>';
					$row = pg_fetch_row($result,$i);
					for ($j=0; $j<$n; $j++) {
						$r .= ("<TD>$row[$j]</TD>");
					}
					$r .= "</TR>";
					$r .= $cr;
				}
				$r .= '</TABLEDATA>';
				$r .= '</DATA>';
				$r .= '</TABLE>';
				$r .= '</RESOURCE>';
				$r .= '</VOTABLE>';
			}
		}
		
		// write 'testserver.xml' for debugging
		$res = fopen("$tempdir/testserver.xml",'w');
		//fwrite($res,sprintf("numfields=%d numrows=%d\n",$n,$rows));
		fwrite($res,$r);
		fclose($res);
	
		return ($r);		
	}//countRows

	
	$s->service($HTTP_RAW_POST_DATA);

	error_reporting ($error_reporting_old);
?>
