<?php
	# =============================================
        # HELIO 2010 HEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
	# hec_load_bms.php
        # last 28-jun-2011
	# ---------------------------------------------
	# read BAS magnetic storms list
	# web site: http://www.antarctica.ac.uk/SatelliteRisks
	# =============================================
	define("DEBUG","0");
	
	require ("hec_global.php");
//	$tempdir = "/var/www/hec/temp";
	
	if (DEBUG==0) {
	}
	$list[0]="stormtimes.txt";
	
	// parse files and create postgres-ready file	
	$f1 = fopen("$tempdir/BMS.postgres.converted",'w');
	foreach ($list as $file) {
		$f2 = fopen("$tempdir/$file",'r');
		while ( ($f2) AND (!feof ($f2))) {
			$buffer = fgets($f2);
			if (is_numeric(substr($buffer,0,2))) {//skip comment at begin
				$pcs = preg_split("/( +)/",trim($buffer));
//YYYY  MM  DD  HH    nT dd hh   YYYY  MM  DD  HH   YYYY  MM  DD  HH
//1992   1   8  19   -51  0 16   1992   1   8   9   1992   1   9   1
				$max=count($pcs);
				$time_peak = sprintf("%04d/%02d/%02d %02d:00:00",$pcs[0],$pcs[1],$pcs[2],$pcs[3]);
				$time_start = sprintf("%04d/%02d/%02d %02d:00:00",$pcs[7],$pcs[8],$pcs[9],$pcs[10]);
				$time_end = sprintf("%04d/%02d/%02d %02d:00:00",$pcs[11],$pcs[12],$pcs[13],$pcs[14]);
				$dst=$pcs[4];
				$dd=$pcs[5]*24+$pcs[6];
				$out = sprintf("%s\t%s\t%s\t%s\t%s\n",$time_start,$time_peak,$time_end,$dst,$dd);
				fwrite($f1,$out);
			}#if is numeric
		}#while	
		fclose($f2);	
	}#foreach
	
	fclose($f1);	
?>
