<?php
	# =============================================
	# INAF - Trieste Astronomical Observatory
        # HELIO 2010 HEC server - by Andrej Santin
        # hec_load_sgs.php
        # last 28-jun-2011
	# ---------------------------------------------
	# read SGAS catalogue
	# web site: ftp://solar.sec.noaa.gov/pub/forecasts/SGAS
	# =============================================
	define("DEBUG","0");//after 06-jan-2007 ftp is dead!!!
//alternative 05-jun-2009:
//wget http://www.swpc.noaa.gov/ftpdir/warehouse/2008/2008_SGAS.tar.gz
//wget ftp://ftp.swpc.noaa.gov/pub/warehouse/2009/SGAS/*
	
	require ("hec_global.php");
	$remotedir = "http://www.sec.noaa.gov/ftpdir/indices/old_indices";
	$tempdirsgas = "$tempdir/sgas.archive";
        $curyear = date("Y");

if (DEBUG==0) {	
	// get files from FTP site
	//$conn = ftp_connect("solar.sec.noaa.gov");
	$conn = ftp_connect("ftp.swpc.noaa.gov");
	if (!$conn) {
		echo 'FTP connect error';
		exit;
	}
	if (ftp_login($conn, 'anonymous','alpha@beta.com')) {
    		echo "Connected\n";
	} else {
    		echo "Couldn't connect\n";
	}
	ftp_chdir($conn,'pub/warehouse/'.$curyear.'/SGAS');
	$list = ftp_nlist($conn, "*.txt");
	foreach ($list as $file) {
		ftp_get($conn,"$tempdirsgas/$file",$file,FTP_ASCII);
		echo "Got file $file\n";
	}
	ftp_close($conn);
} #debug==0

      $i=0;
      $f1 = fopen("$tempdir/SGS2.postgres.converted",'w');

      for ($iii=0;$iii<3;$iii++) {
	// get file list
        switch ($iii) {
        case 0:
          $s = shell_exec("ls $tempdirsgas/sgas.1*");
          break;
        case 1:
          $s = shell_exec("ls $tempdirsgas/sgas.2*");
          break;
        case 2:
          $s = shell_exec("ls $tempdirsgas/20*.txt");
          break;
        }
	$list = split("\n",$s);
//	$list=array_merge($list1,$list2,$list3);

//	$list[0]="/var/www/html/sec/temp/sgas.archive/sgas.20000627";

	$ntime_err=0;
	// parse files and create postgres-ready file	
	$months = array("JAN"=>1,"FEB"=>2,"MAR"=>3,"APR"=>4,"MAY"=>5,"JUN"=>6,"JUL"=>7,"AUG"=>8,"SEP"=>9,"OCT"=>10,"NOV"=>11,"DEC"=>12);
	foreach ($list as $file) {
	if (strlen($file)>4) {
//		echo "$file\n";
		$f2 = fopen("$file",'r');
		$i++;
		do {
			if (substr($buffer,0,4)=="SGAS") {#find year
				$ybuf=split(" ",$buffer);
			}
			$buffer = fgets($f2);
			if ((strpos($buffer,"RECEIVED")>0) OR (strpos($buffer,"received")>0)) {#find date
				$dbuf=split(" ",$buffer);
			}
		} while ((substr($buffer,0,2)<>"A.") AND !feof($f2));#search until .A
		$buffer = fgets($f2);//skip comment
		$date=substr($file,strlen($file)-8,8);
//		$date=substr($date,0,4)."/".$months[strtoupper(substr($dbuf[11],0,3))]."/".$dbuf[10];
		$y=$ybuf[9];#year from file
		if (($dbuf[10]=="31") AND ($months[strtoupper(substr($dbuf[11],0,3))]==12) ) {
//			echo "$y $dbuf[10] $dbuf[11] $file\n";
			$y=$y-1;
		}
		$date=sprintf("%04d/%02d/%02d",$y,$months[strtoupper(substr($dbuf[11],0,3))],$dbuf[10]);
        $mo=$months[strtoupper(substr($ybuf[8],0,3))];
//        $d=$ybuf[7];
		$d=$dbuf[10];
		$y=substr($y,0,4);
		$jd = GregorianToJD($mo,$d,$y);
		//next day
		$gregorian = JDToGregorian ($jd+1);// mm/dd/yyyy
//		echo "$y,$mo,$d - $gregorian\n";
		$dd=split("/",$gregorian);
		$dateinc=sprintf("%04d/%02d/%02d",$dd[2],$dd[0],$dd[1]);// next day date
		//day before
		$gregorian = JDToGregorian ($jd-1);// mm/dd/yyyy
		$dd=split("/",$gregorian);
		$datedec=sprintf("%04d/%02d/%02d",$dd[2],$dd[0],$dd[1]);// previous day date
		//next two day
		$gregorian = JDToGregorian ($jd+2);// mm/dd/yyyy
		$dd=split("/",$gregorian);
		$dateinc2=sprintf("%04d/%02d/%02d",$dd[2],$dd[0],$dd[1]);// next day date
		//next three day
		$gregorian = JDToGregorian ($jd+3);// mm/dd/yyyy
		$dd=split("/",$gregorian);
		$dateinc3=sprintf("%04d/%02d/%02d",$dd[2],$dd[0],$dd[1]);// next day date
//		echo "$datedec, |$date|, $dateinc, $dateinc2, $dateinc3\n";
		
		$buffer = fgets($f2);
		$j=0;
		while ((substr($buffer,0,1)==" ") AND strlen($buffer)>6){
			$out="";
			//echo " $date-$buffer\n";
			#time_start
			if (strlen(trim(substr($buffer,1,4)))==4) {
				$out.=$date." ".substr($buffer,1,2).":".substr($buffer,3,2).":00\t";
				$et=substr($buffer,1,2)+substr($buffer,3,2)/60;
				$m_start=substr($buffer,1,2)*60+substr($buffer,3,2);
			} else {
				$out.="\N\t";
				$m_start="";
			}
			#time_peak
			if (strlen(trim(substr($buffer,6,4)))==4) {
				$out.=$date." ".substr($buffer,6,2).":".substr($buffer,8,2).":00\t";
				$m_peak=substr($buffer,6,2)*60+substr($buffer,8,2);
			} else {
				$out.="\N\t";
				$m_peak="";
			}
			#time_end
			if (strlen(trim(substr($buffer,11,4)))==4) {
				$out.=$date." ".substr($buffer,11,2).":".substr($buffer,13,2).":00";
				$m_end=substr($buffer,11,2)*60+substr($buffer,13,2);
			} else {
				$out.="\N\t";
				$m_end="";
			}
			if (substr($out,0,9)=="\N\t\N\t\N\t") echo "$file:\n|$buffer|\n";
			$buf[1]=trim(substr($buffer,16,5));#nar
			if (!is_numeric($buf[1])) $buf[1]="";
			if (($y>2001) and ($buf[1]<9751) and ($buf[1]!="")) $buf[1]=$buf[1]+10000;//nar rollover
			// convert lat
			$st=trim(substr($buffer,22,3));
			if (substr($st,0,1)=="S")
				$buf[2]="-".substr($st,1,2);
			else
				$buf[2]=substr($st,1,2);
			if (!is_numeric($buf[2])) $buf[2]="";
			// convert long
			$st=trim(substr($buffer,25,3));
			if (substr($st,0,1)=="E")
				$buf[3]="-".substr($st,1,2);
			else
				$buf[3]=substr($st,1,2);
			if (!is_numeric($buf[3])) $buf[3]="";
			$buf[4]=trim(substr($buffer,29,4));#xray
			$buf[5]=strtolower(trim(substr($buffer,35,2)));#opt
			$buf[6]=trim(substr($buffer,38,6));#245MHz
			$buf[7]=trim(substr($buffer,45,4));#10cm
			$st=trim(substr($buffer,52,5));#sweep
			$buf[8]="";
			$buf[9]="";
			if ($st=="II") {
				$buf[8]="1";
			} 
			if ($st=="IV") {
				$buf[9]="1";
			} 
			if ($st=="II/IV") {
				$buf[8]="1";
				$buf[9]="1";
			} 
			$buf[10]="";#swf

			if (($m_start!="") and ($m_peak!="") and ($m_end!="")
				and ($m_start<=$m_peak) and ($m_peak<=$m_end)) {
				echo ("$out start=$m_start peak=$m_peak end=$m_end\n");
				//ntime_start *  selection start time (t0) as a full flare rise time before the declared start time 
				if (($m_start>$m_peak) or ($m_peak>$m_end)) echo ("inv: $buffer\n$buf[1]=$m_start $buf[3]=$m_peak $buf[2]=$m_end\n");
				$t0=$m_peak-2*($m_peak-$m_start);
				$t0_date=$date;
				if ($t0<0) {
					$t0+=1440;
					$t0_date=$datedec;
					echo "-\n-\n-\n-\n-\n-\n-\n-\n";
				}
				$t0h=sprintf("%02d",floor($t0/60));
				$t0m=sprintf("%02d",$t0 % 60);
				$ntime_start=$t0_date." ".$t0h.":".$t0m.":00";
				//ntime_end *  selection end time (t1) as 8 times the time to half intensity after the flare maximum (=> ~1/50th intensity)
				$t1=$m_peak+8*($m_end-$m_peak);
				$t1_date=$date;
				if ($t1>1439) {
					$t1=$t1-1440;
					$t1_date=$dateinc;
					echo "+\n+\n+\n+\n+\n+\n+\n+\n";
				}
				if ($t1>1439) {
					$t1=$t1-1440;
					$t1_date=$dateinc2;
	//				echo "+\n+\n+\n+\n+\n+\n+\n+\n";
				}
				if ($t1>1439) {
					$t1=$t1-1440;
					$t1_date=$dateinc3;
	//				echo "+\n+\n+\n+\n+\n+\n+\n+\n";
				}
				$t1h=sprintf("%02d",floor($t1/60));
				if ($t1h>23) echo "23>\n";
				$t1m=sprintf("%02d",$t1 % 60);
				$ntime_end=$t1_date." ".$t1h.":".$t1m.":00";
				echo ("t0=$t0 $ntime_start\nt1=$t1 $ntime_end\n\n");
			} else {
				$ntime_err++;
				$ntime_start="";
				$ntime_end="";
			}//if (($m_start!="")...

			$buf[11]=$ntime_start;
			$buf[12]=$ntime_end;
			
			#compute Carrington longitude
			if ($buf[3]!="")
				$buf[13]=long_carr($y,$mo,$d,$et,$buf[3]);
			else $buf[13]="";
			
			for ($k=1;$k<=13;$k++) {
				if ($buf[$k]=="") $buf[$k]="\N";
				$out.="\t".$buf[$k];
			}
			$out.="\n";
			if ((is_numeric(substr($buffer,1,4))) AND //time at begin og line
				(strlen($out)>50) AND //enougth data
				(substr($out,0,9)!="\N\t\N\t\N\t") ) {//not all three times NULL
				fwrite($f1,$out);
			}
			$buffer = fgets($f2);
			$j++;
		}
		fclose($f2);	
	}#if strlen
	}#foreach
       }//for iii	
	fclose($f1);
	echo "i=$i\n";
?>
