<?php
	# =============================================
        # HELIO 2010 HEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
	# ---------------------------------------------
	# SEC range table generator
	# hec_range.php
        # last 28-jun-2011
	# =============================================
        require ("hec_global.php");

	$arrmonth = array ('Jan','Feb','Mar','Apr','May','Jun',
		'Jul','Aug','Sep','Oct','Nov','Dec');
					
	$dbconn = pg_connect("dbname=hec");
	if (!$dbconn) {
		echo "Error: unable to connect database";
	}
	$f1 = fopen("$hecdir/sec_range.txt",'w');
	$f2 = fopen("$hecdir/temp/catalogues.postgres.converted",'w');

	fwrite($f1,"<TABLE border=1 ><TR>\n");
	
	fwrite($f1,"<TD><B>\n");
	fwrite($f1,"CATALOGUE");
	fwrite($f1,"</TD></B>\n");
	
	fwrite($f1,"<TD><B>\n");
	fwrite($f1,"TABLE");
	fwrite($f1,"</TD></B>\n");
	
	fwrite($f1,"<TD><B>\n");
	fwrite($f1,"TYPE");
	fwrite($f1,"</TD></B>\n");
	
	fwrite($f1,"<TD><B>\n");
	fwrite($f1,"FROM");
	fwrite($f1,"</TD></B>\n");
	
	fwrite($f1,"<TD><B>\n");
	fwrite($f1,"TO");
	fwrite($f1,"</TD></B>\n");
	
	fwrite($f1,"<TD><B>\n");
	fwrite($f1,"STATUS");
	fwrite($f1,"</TD></B>\n");
	
	fwrite($f1,"<TD><B>\n");
	fwrite($f1,"RECORDS");
	fwrite($f1,"</TD></B>\n");
	
	fwrite($f1,"</TR>\n");

	//read groups
	$sql_string="SELECT hec_groups_id,hec_group_name FROM hec_groups;";
	$result = pg_exec($dbconn,$sql_string);
	if ($result) {
		while ($r = pg_fetch_array($result)) {
			$groups[$r['hec_groups_id']]=$r['hec_group_name'];
		}
	}

	$i=0;
	for($g=1; $g<=5; $g++) {	
			
		fwrite($f1,"<tr><td colspan=7><i>".$groups[$g]."</i></td><tr>");

		// read database for table list
		$sql_string="SELECT name,description,type,status,url,bg_color,longdescription,timefrom,timeto FROM sec_catalogue WHERE hec_groups_id = $g ORDER BY cat_id;";
		$result = pg_exec($dbconn,$sql_string);
		if (!$result) {
			echo "error\n";
		}
		$message = "";
		while ($r = pg_fetch_array($result)) {
			$cat = $r['name'];
/*
			if ((	$cat=="noaa_proton_event") 
					OR ($cat=="lasco_cme_list") 
					OR ($cat=="lasco_cme_cat") 
					OR ($cat=="dsd_list")
					OR ($cat=="srs_list")
					OR ($cat=="eit_list")
					OR ($cat=="ssc_list")
					OR ($cat=="forbush_list")
					OR ($cat=="hi_event")
					OR ($cat=="soho_pm_ips")
					OR ($cat=="wind_imf_ips")
					OR ($cat=="gevloc_flares")
			) 	#insert here catalogues without time_end
				$sql_string2="SELECT COUNT(*),MIN(time_start),MAX(time_start) FROM $cat;";
			else
				$sql_string2="SELECT COUNT(*),MIN(time_start),MAX(time_start),MAX(time_end) FROM $cat;";
*/
      $sql_string2="SELECT COUNT(*),MIN(time_start),MAX(time_start) FROM $cat;";				
    	//echo "$sql_string2\n";
			$result2 = pg_exec($dbconn,$sql_string2);
			if (!$result2) {
				echo "$cat error\n";
			}
			$r2 = pg_fetch_row($result2);
			$count = $r2[0];
	  		//generate warning message
				if ($count<1) $message .= "$r[1] is empty !?\r\n";
			$min = $r2[1];
			$max = $r2[2];
			$max2 = $r2[3];
			if ($max2>$max) $max=$max2;
		        
      $miniso = $min;
      if ($miniso=='') $miniso = '\N';
      $maxiso = $max;	
      if ($maxiso=='') $maxiso = '\N';

			$min = substr($min,0,10);
			$datesplit = split("-",$min);
			$y = $datesplit[0];
			$mo = $arrmonth[$datesplit[1]-1];
			$d = $datesplit[2];
			$min = sprintf("%04d-%s-%02d",$y,$mo,$d);

			$max = substr($max,0,10);
			$datesplit = split("-",$max);
			$y = $datesplit[0];
			$mo = $arrmonth[$datesplit[1]-1];
			$d = $datesplit[2];
			$max = sprintf("%04d-%s-%02d",$y,$mo,$d);
			
			fwrite($f1,"<TR bgcolor=".$r['bg_color'].">\n");
			fwrite($f1,"<TD>\n");
			$st=$r[5];
			fwrite($f1,"<a href=\"".$r['url']."\" target=\"_blank\">".$r['description']."</a>");
			fwrite($f1,"</TD>\n");
			
			fwrite($f1,"<TD>\n");
			fwrite($f1,$r['name']);
			fwrite($f1,"</TD>\n");
			
			fwrite($f1,"<TD>\n");
			fwrite($f1,$r['type']);
			fwrite($f1,"</TD>\n");
			
			fwrite($f1,"<TD>\n");
			fwrite($f1,"$min");
			fwrite($f1,"</TD>\n");
			
			fwrite($f1,"<TD>\n");
			fwrite($f1,"$max");
			fwrite($f1,"</TD>\n");
			
			fwrite($f1,"<TD>\n");
			fwrite($f1,$r['status']);
			fwrite($f1,"</TD>\n");
			
			fwrite($f1,"<TD>\n");
			fwrite($f1,$count);
			fwrite($f1,"</TD>\n");
			
			fwrite($f1,"</TR>\n");

      //HQI catalogues
//      fwrite($f2,$r['name']."\t".$r['description']."\t".$r['type']."\t".$r['status']."\t$miniso\t$maxiso\n");
      
      fwrite($f2,$r['name']."\t".$r['description']."\t".$r['type']."\t".$r['status']."\t".$r['timefrom']."\t".$maxiso."\n");
		}//while
	}//for $g
	fwrite($f1,"</TABLE>\n");
	fclose($f2);

	#get date of last update
	$f2 = fopen("sec.log",'r');
        $buffer2="...";
      if ($f2) {
	while (!feof ($f2)) {
		$buffer = fgets($f2);
		if ($buffer<>"") $buffer2=$buffer;
	}
	fclose($f2);
      } else $buffer2 = date("Ymd_His");
	fwrite($f1,"Last HEC database update: $buffer2 (".date("Ymd").")<BR>\n");
        echo "$buffer2\n";
	fclose($f1);
	pg_close($dbconn);

//test hostname
$SERVER_NAME=exec('hostname');
echo "$SERVER_NAME\n";
//$message="test test test\n";//uncomment for test

/*
if (($SERVER_NAME=="imhotep.oat.ts.astro.it") and ($message!="")) {
  
  // subject 
  $subject = "SEC automatic warning";

  // message 
  $now=date('Y-m-d H:i:s');
  $message .= "automaticaly generated from $SERVER_NAME at ".$now."\r\n\r\n";

  // To send HTML mail, you can set the Content-type header.
//  $headers  = "MIME-Version: 1.0\r\n";
//  $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

  $smtp_server = "avalon.oat.ts.astro.it";
  $port = 25;
  $mydomain = "oat.ts.astro.it";
  $sender = "<sec@imhotep.oat.ts.astro.it>";
#  $recipient1 = "<messerotti@ts.astro.it>";
  $recipient1 = "<messerotti@oats.inaf.it>";
#  $recipient2 = "<coretti@ts.astro.it>";
  $recipient2 = "<coretti@oats.inaf.it>";
  $subject = "SEC automatic warning";
  $content = $message;
  echo ($content);
  
  // Initiate connection with the SMTP server
  $handle = fsockopen($smtp_server,$port);
  fputs($handle, "EHLO $mydomain\r\n");
  $s=fgets($handle);
//  echo($s);
  
  // Send out the e-mail
  fputs($handle, "MAIL FROM:$sender\r\n");
  fputs($handle, "RCPT TO:$recipient1\r\n");
  fputs($handle, "RCPT TO:$recipient2\r\n");
  fputs($handle, "DATA\r\n");
  fputs($handle, "To: $recipient1,$recipient2\r\n");
  fputs($handle, "Subject: $subject\n\n");
  fputs($handle, "$content\r\n");
  fputs($handle, ".\r\n");
  $s=fgets($handle);
//  echo($s);
  
  // Close connection to SMTP server
  fputs($handle, "QUIT\r\n");
  $s=fgets($handle);
//  echo($s);
  fclose($handle);
}//if "imhotep"
*/

?>
