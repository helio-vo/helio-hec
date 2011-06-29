<?php
	# =============================================
        # HELIO 2010 HEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
	# hec_load_yfc.php
        # last 28-jun-2011
	# ---------------------------------------------
	# read Yohkoh HXT Flare Catalogue
	# web site: http://isass1.solar.isas.jaxa.jp/sxt_co/hxt_flare_list.txt
	# =============================================
	
	define("DEBUG","1");
	require ("hec_global.php");
//	$tempdir = "/var/www/hec/temp";
	
if (DEBUG==0) {	
	// get files from HTTP
	exec ("wget http://isass1.solar.isas.jaxa.jp/sxt_co/hxt_flare_list.txt");
	copy ("hxt_flare_list.txt",$tempdir."/hxt_flare_list.txt");
	unlink ("hxt_flare_list.txt");
}

	// parse files and create postgres-ready file	
	$f1 = fopen("$tempdir/YFC.postgres.converted",'w');
	$f2 = fopen("$tempdir/hxt_flare_list.txt",'r');
	while (!feof ($f2)) {
		$buffer = fgets($f2);
		//EVENT   DATE     START     MAX     END    L   M1   M2    H GOES H alpha  NOAA
		//00010 91/10/01 142430  142432  142436     3    -    -    - C3.5 SF S11W05 6850 ??                     
//		$buf = split(" ",trim($buffer));
//		$buf = preg_split("/( +)/",trim($buffer));
		$i=0;
		$buf[$i++] = substr($buffer,0,5); #event
		$buf[$i++] = substr($buffer,6,8); #date
		$buf[$i++] = substr($buffer,15,6);#time
		$buf[$i++] = substr($buffer,23,6);#time
		$buf[$i++] = substr($buffer,31,6);#time
		$buf[$i++] = substr($buffer,38,5);#lo
		$buf[$i++] = substr($buffer,44,4);#m1
		$buf[$i++] = substr($buffer,49,4);#m2
		$buf[$i++] = substr($buffer,54,4);#hi
		$buf[$i++] = substr($buffer,59,4);#xray
		$buf[$i++] = strtolower(substr($buffer,64,2));#opt
		$buf[$i++] = substr($buffer,67,3);#lat
		$buf[$i++] = substr($buffer,70,3);#long
		$buf[$i++] = substr($buffer,74,4);#nar
		$buf[$i++] = substr($buffer,79,3);#rem?
		for ($i=0; $i<=14; $i++) $buf[$i]=trim($buf[$i]);
		
		$out="";
		
		// get date
		if (strlen($buf[1])<8) $buf[1]="0".$buf[1];
		$y = substr($buf[1],0,2);
		if ($y>90) $y=$y+1900; else $y=$y+2000;
		$mo = substr($buf[1],3,2);
		$d = substr($buf[1],6,2);
		
		// get times
		for ($i=2; $i<=4; $i++) {
			$h = substr($buf[$i],0,2);
			$mi = substr($buf[$i],2,2);
			$s = substr($buf[$i],4,2);
			if ($s=="-") $s=0;
			if (checkdate($mo,$d,$y)) {
				$time = sprintf("%04d/%02d/%02d",$y,$mo,$d);
				if (is_numeric($h) && is_numeric($mi) && is_numeric($s)) {
					$time = $time.sprintf(" %02d:%02d:%02d",$h,$mi,$s);
				} else {
					$time = '\N';
				}
			} else {
				$time = '\N';
			}
			$out.="\t".$time;
		}

		if ($buf[11]=="RB") {
			$buf[11]="";
			$buf[12]="";
			$buf[13]="";
			$buf[14]="RB";
		}
		if (is_numeric($buf[11])) { #lant/long missing but nar present
			$buf[13]=trim($buf[11].$buf[12]);
			$buf[11]="";
			$buf[12]="";
		}
		// convert lat/long
		if (substr($buf[11],0,1)=="S")
			$buf[11]="-".substr($buf[11],1,2);
		else
			$buf[11]=substr($buf[11],1,2);
		if (substr($buf[12],0,1)=="E")
			$buf[12]="-".substr($buf[12],1,2);
		else
			$buf[12]=substr($buf[12],1,2);
		$lat=$buf[11];
		$long=$buf[12];
		
		// get rest
		for ($i=5; $i<=14; $i++) {
			$buf[$i]=str_replace(">","",$buf[$i]);
			if ($buf[$i]<>"-" AND $buf[$i]<>"") {
				$buf[$i]="\t".$buf[$i];
			} else {
				$buf[$i]="\t\N";
			}
		}
		
		#scramble fields
		$out.=$buf[13];#nar
		$out.=$buf[11];#lat
		$out.=$buf[12];#long
		$out.=$buf[9];#xray_class
		$out.=$buf[10];#optical_class
		$out.=$buf[5];#hxt_lo
		$out.=$buf[6];#hxt_m1
		$out.=$buf[7];#hxt_m2
		$out.=$buf[8];#hxt_hi
		$out.=$buf[14];#rem
		$out.="\t".$buf[0];#yoh_event

		$out.="\n";
		$out=substr($out,1,strlen($out));#cut first tab
		if ($buffer<>"") fwrite($f1,$out);
	}	
	fclose($f2);
	fclose($f1);
?>
