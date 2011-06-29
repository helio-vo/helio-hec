<?php
	# =============================================
	# HELIO 2010 HEC server - by Andrej Santin
        # INAF - Trieste Astronomical Observatory
	# hec_load_goes.php
	# last 28-jun-2011
	# ---------------------------------------------
	# read the GOES xray flare data
	# ftp://anonymous@ftp.ngdc.noaa.gov/STP/SOLAR_DATA/SOLAR_FLARES/XRAY_FLARES
	# =============================================
	define("DEBUG","0");
	set_time_limit(60);//may take a long time...
	
	require ("hec_global.php");
//	$tempdir = "/var/www/hec/temp";

	
	// get files from HTTP
	if (DEBUG==0) {
		exec ("/bin/rm temp/goes/xray*");
		exec ("/bin/rm temp/goes/XRAY*");
		exec ("wget -r -nd -P temp/goes -N -A 'XRAY[1-9]*','xray[1-9]*' ftp://anonymous@ftp.ngdc.noaa.gov/STP/SOLAR_DATA/SOLAR_FLARES/FLARES_XRAY/\*");
		exec ("cat temp/goes/xray* temp/goes/XRAY* > all_xray.txt");
		copy ("all_xray.txt",$tempdir."/all_xray.txt");
		unlink ("all_xray.txt");
	}
	
	// parse files and create postgres-ready file	
	$f1 = fopen("$tempdir/GOES.postgres.converted",'w');
	$f2 = fopen("$tempdir/all_xray.txt",'r');
	$ntime_err=0;
    
	while (!feof ($f2)) {
		$i=1; //buf array index
    	$buffer = fgets($f2);
//        if (substr($buffer,0,5)!="31777") $buffer=substr($buffer,1,255);//echo "?????????\n";
		// get date
		$y = substr($buffer,5,2);
		if ($y<70) $y=$y+2000; else $y=$y+1900;
		$mo = substr($buffer,7,2);
		$d = substr($buffer,9,2);
		$jd = GregorianToJD ($mo,$d,$y);
		//next day
		$gregorian = JDToGregorian ($jd+1);// mm/dd/yyyy
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
		$gregorian = JDToGregorian ($jd+2);// mm/dd/yyyy
		$dd=split("/",$gregorian);
		$dateinc3=sprintf("%04d/%02d/%02d",$dd[2],$dd[0],$dd[1]);// next day date
		if (checkdate($mo,$d,$y)) {
            $date = sprintf("%04d/%02d/%02d",$y,$mo,$d);
        } else {
            $date = "";
        }

        //start time
        if (strlen(trim(substr($buffer,13,4)))==4) {
			$m_start=substr($buffer,13,2)*60+substr($buffer,15,2);//start time in minutes
			if (substr($buffer,13,2)<24) {
                $buf[$i++]=$date." ".substr($buffer,13,2).":".substr($buffer,15,2).":00";
				$et=substr($buffer,13,2)+substr($buffer,15,2)/60; 
			} else {  
                $buf[$i++]=$dateinc." ".(substr($buffer,13,2)-24).":".substr($buffer,15,2).":00";
				$et=(substr($buffer,13,2)-24)+substr($buffer,15,2)/60; 
//			  echo $buffer."\nS".$buf[$i-1]."\n";
			}
        } else {
            $buf[$i++]="";
			$et=-1;
        }
        //end time
        if (strlen(trim(substr($buffer,18,4)))==4) {
			$m_end=substr($buffer,18,2)*60+substr($buffer,20,2);//end time in minutes
			if (substr($buffer,18,2)>=24) // dateinc,h=h-24
              $buf[$i++]=$dateinc." ".(substr($buffer,18,2)-24).":".substr($buffer,20,2).":00";
			else {
                if ($m_start>$m_end) // dateinc,h=h
                    $buf[$i++]=$dateinc." ".(substr($buffer,18,2)).":".substr($buffer,20,2).":00";
			    else {                
                    $buf[$i++]=$date." ".substr($buffer,18,2).":".substr($buffer,20,2).":00";
//			        echo $buffer."\nE".$buf[$i-1]."\n";
			    }
            }
        } else {
            $buf[$i++]="";
        }
        //peak time
        if (strlen(trim(substr($buffer,23,4)))==4) {
			$m_peak=substr($buffer,23,2)*60+substr($buffer,25,2);//peak time in minutes
			if (substr($buffer,23,2)>=24) // dateinc,h=h-24
              $buf[$i++]=$dateinc." ".(substr($buffer,23,2)-24).":".substr($buffer,25,2).":00";
			else {
                if ($m_start>$m_peak) // dateinc,h=h
                    $buf[$i++]=$dateinc." ".(substr($buffer,23,2)).":".substr($buffer,25,2).":00";
                else {
                    $buf[$i++]=$date." ".substr($buffer,23,2).":".substr($buffer,25,2).":00";
//		            echo $buffer."\nP".$buf[$i-1]."\n";
                }
			}
        } else {
            $buf[$i++]="";
        }

        if ($m_start>$m_peak) { $m_peak+=1440; echo "peakinc:$buffer\n"; $f=1;} else $f=0;
        if ($m_start>$m_end) $m_end+=1440;
		if (($buf[1]!="") and ($buf[2]!="") and ($buf[3]!="")
             and ($m_start<=$m_peak) and ($m_peak<=$m_end)) {
//			echo ("$buf[1]=$m_start $buf[3]=$m_peak $buf[2]=$m_end\n");
//            if ((($m_start+1440)>($m_peak+1440)) or (($m_peak+1440)>($m_end+1440))) echo ("inv: $buffer\n$buf[1]=$m_start $buf[3]=$m_peak $buf[2]=$m_end\n");
			//ntime_start *  selection start time (t0) as a full flare rise time before the declared start time 
			$t0=$m_peak-2*($m_peak-$m_start);
			$t0_date=$date;
			if ($t0<0) {
				$t0+=1440;
				$t0_date=$datedec;
//				echo "-\n-\n-\n-\n-\n-\n-\n-\n";
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
//				echo "+\n+\n+\n+\n+\n+\n+\n+\n";
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
//			if ($t1h>23) echo "23>\n";
			$t1m=sprintf("%02d",$t1 % 60);
			$ntime_end=$t1_date." ".$t1h.":".$t1m.":00";
			if ($f) echo ("t0=$t0 $ntime_start\nt1=$t1 $ntime_end\n\n");
		} else {
			echo ("err:$buffer\n");
            $ntime_err++;
			$ntime_start="";
			$ntime_end="";
		}

        //swap end and peak times
        $tmp=$buf[2];
        $buf[2]=$buf[3];
        $buf[3]=$tmp;

        // convert lat
        $st=trim(substr($buffer,28,3));
        if (substr($st,0,1)=="S")
            $buf[$i++]="-".substr($st,1,2);
        else
            $buf[$i++]=substr($st,1,2);
        //if (!is_numeric($buf[4])) $buf[4]="";
        // convert long
        $st=trim(substr($buffer,31,3));
        if (substr($st,0,1)=="E")
            $buf[$i++]="-".substr($st,1,2);
        else
            $buf[$i++]=substr($st,1,2);
		if (($buf[$i-1]!="") and ($et>-1))
			$longcarr=long_carr($y,$mo,$d,$et,$buf[$i-1]);
		else
			$longcarr="";

			
        // optical class
        $buf[$i++] = strtolower(trim(substr($buffer,34,2)));
        // xray class
//        $buf[$i++] = trim(substr($buffer,59,4));
		$st=sprintf("%.1f",trim(substr($buffer,60,3))/10);
        $buf[$i++] = substr($buffer,59,1).$st;
        // nar
		$nar = trim(substr($buffer,80,5));// % 10000;
        if (is_numeric($nar)) $buf[$i++] = $nar; else $buf[$i++] = "";//38684
		
		$buf[$i++]=$ntime_start;
		$buf[$i++]=$ntime_end;
		$buf[$i++]=$longcarr;

        if ($buf[1]=="") $out="\N"; else $out=$buf[1];
        for ($k=2;$k<=11;$k++) {
            if ($buf[$k]=="") $buf[$k]="\N";
            $out.="\t".$buf[$k];
        }
        $out.="\n";
        if ($buf[1]!="") fwrite($f1,$out);
	}
	fclose($f2);
	fclose($f1);	
    echo "ntime_err=$ntime_err\n";
?>
