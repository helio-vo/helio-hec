<?php
	# =============================================
	# INAF - Trieste Astronomical Observatory
        # HELIO 2010 HEC server - by Andrej Santin
	# ---------------------------------------------
	// read lasco preliminary cme lists
	// web site: http://lasco-www.nrl.navy.mil/cmelist.html
        # hec_load_lcl.php
        # last 28-jun-2011
	# =============================================
	define("DEBUG","0");
	require ("hec_global.php");
	
//	$tempdir = "/var/www/hec/temp";
    $maxpending=512-21;//DB VARCHAR() size - "date-time/angle"

if (DEBUG==0) {	
	// get files from FTP site
	$conn = ftp_connect("lasco6.nascom.nasa.gov");
	if (!$conn) {
		echo 'FTP connect error';
		exit;
	}
	if (ftp_login($conn, 'anonymous','alpha@beta.com')) {
    		echo "Connected\n";
	} else {
    		echo "Couldn't connect\n";
	}
	ftp_chdir($conn,'pub/lasco/status');
	$list = ftp_nlist($conn, "LASCO_CME_List_*");
	foreach ($list as $file) {
		ftp_get($conn,"$tempdir/$file.1",$file,FTP_BINARY);
		exec("tr '\r' '\n' <$tempdir/$file.1 >$tempdir/$file");
		unlink("$tempdir/$file.1");
		echo "Got file $file\n";
	}
	ftp_close($conn);
} else {#debug==0

	$list[0]="LASCO_CME_List_1996";
	$list[1]="LASCO_CME_List_1997";
	$list[2]="LASCO_CME_List_1998";
	$list[3]="LASCO_CME_List_1999";
	$list[4]="LASCO_CME_List_2000";
	$list[5]="LASCO_CME_List_2001";
	$list[6]="LASCO_CME_List_2002";
	$list[7]="LASCO_CME_List_2003";
	$list[8]="LASCO_CME_List_2004";
	$list[9]="LASCO_CME_List_2005";
}

	// parse files and create postgres-ready file	
	$months = array("JAN"=>1,"FEB"=>2,"MAR"=>3,"APR"=>4,"MAY"=>5,"JUN"=>6,"JUL"=>7,"AUG"=>8,"SEP"=>9,"OCT"=>10,"NOV"=>11,"DEC"=>12);
	$f1 = fopen("$tempdir/LCL.postgres.converted",'w');
	$newdate="";
	foreach ($list as $file) {
		$n = 0;
		$pending = "";
		$f2 = fopen("$tempdir/$file",'r');
		while (!feof ($f2)) {
			$buffer = fgets($f2);
//			if (substr($buffer,0,12)=="01-Sep-2004:") break 1;//problems after 1st september new format!!!
			if ( (substr($buffer,2,1)=="-") and (substr($buffer,11,1)==":") ) {//new date system detected
				$newdate=substr($buffer,0,11);
				$buffer="";//avoid blank records
			}
			if (is_numeric(substr($buffer,0,2))) {//if line start with two digits
					// it's a new record
					if ($pending<>"") {
						// write old record
            			if (strlen($pending)>$maxpending) {
                            $l=strlen($pending);
            				echo "Pending cutted. $l\n";
							$pending = substr($pending,0,$maxpending).">>>";
						}
						fwrite($f1,$pending."\n");
//						echo "pending: $pending\n";
						$pending="";
						$n++;
					}
					// decode date & time
					if ($newdate!="") $buffer=$newdate."\t".$buffer;
//					$pcs = explode("\t", $buffer);
                    $pcs = preg_split("/[\s\t]+/",trim($buffer));
                    for ($j=4;$j<count($pcs);$j++) { $pcs[3].=" ".$pcs[$j]; }//concatenate again description
					$pcs[0] = trim($pcs[0]);
					$d = substr($pcs[0],0,2);
					$mo = $months[strtoupper(substr($pcs[0],3,3))];
					$y = substr($pcs[0],7,4);
					if ($mo=="") {#problems in 1996
						$mo = $months[substr($pcs[0],6,3)];
						$y = substr($pcs[0],10,4);
					}
					if ($y<1900) $y+=1900;#problems in 1996
					if (!is_numeric(substr($pcs[1],0,1))) $pcs[1]=substr($pcs[1],1,5);
					$h = substr($pcs[1],0,2);
					$mi = substr($pcs[1],3,2);
					if (checkdate($mo,$d,$y)) {
						$time = sprintf("%04d/%02d/%02d",$y,$mo,$d);
						if (is_numeric($h) && is_numeric($mi))
							$time = $time.sprintf(" %02d:%02d",$h,$mi);
					} else 
						$time = '\N';
					if ($pcs[2]=="") {#if double tabs
						for ($j=2;$j<5;$j++) { $pcs[$j]=$pcs[$j+1]; }
					}
					// decode polar angle
					$j=strpos($pcs[2],"+");
					if ($j>0) $pcs[2]=substr($pcs[2],0,$j);
//                    echo "$pcs[2]\n";
					switch($pcs[2]) {
						case 'N':
						case 'NP':
						case 'NPole':
							$pa = 0;
							break;
						case 'NNE':
						case 'N/NE':
							$pa = 22;
							break;
						case 'NE':
						case 'NE+':
							$pa = 45;
							break;
						case 'ENE':
						case 'E/NE':
							$pa = 67;
							break;
						case 'E':
						case 'E+':
							$pa = 90;
							break;
						case 'ESE':
						case 'E/SE':
							$pa = 112;
							break;
						case 'SE':
						case 'SE+':
							$pa = 135;
							break;
						case 'SSE':
						case 'S/SE':
							$pa = 157;
							break;
						case 'S':
						case 'SP':
						case 'S+':
							$pa = 180;
							break;
						case 'SSW':
						case 'S/SW':
							$pa = 202;
							break;
						case 'SW':
						case 'SW+':
							$pa = 225;
							break;
						case 'WSW':
						case 'W/SW':
							$pa = 247;
							break;
						case 'W':
						case 'West':
						case 'W+':
						case 'W?':
							$pa = 270;
							break;
						case 'WNW':
						case 'W/NW':
							$pa = 292;
							break;
						case 'NW':
						case 'NW+':
							$pa = 315;
							break;
						case 'NNW':
						case 'N/NW':
						case 'N-NW':
							$pa = 337;
							break;
						case 'halo':
						case 'Halo':
						case 'HALO':
						case '360':
							$pa = -1;
							break;
						default:
							$pa = '\N';
                            $pcs[3]="[".$pcs[2]."] ".$pcs[3];//can't decode
                            echo "$pcs[3]\n";
					}#switch
					// add comment
					$pending = sprintf("%s\t%s\t%s",$time,$pa,trim($pcs[3]));
			} else {//else if line start with two digits
				if ( (substr($buffer,0,1)<>"*") AND (substr($buffer,0,1)<>"-") AND (substr($buffer,0,1)<>"=") AND ($pending<>"") ) {#add pending lines until new record
					$buffer=str_replace("\t"," ",trim($buffer));#clear tabs
					$pending = $pending ." ". $buffer;
				} 
			}
		}#while feof($f2)
		if (substr($buffer,0,12)=="01-Sep-2004:") {
			echo "new format!\n";
			$pending="";
		}

		fclose($f2);
		if ($pending<>"") {	
			// eof: write anyway old record
			if (strlen($pending)>$maxpending) {
                $l=strlen($pending);
                echo "Pending cutted. $l\n";
				$pending = substr($pending,0,$maxpending).">>>";
			}
			fwrite($f1,$pending."\n");
			$n++;
			$pending="";
		}
		echo ("$file: $n lines\n");
	}#foreach
	fclose($f1);	
?>
