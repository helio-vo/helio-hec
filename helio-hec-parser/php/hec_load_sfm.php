<?php
	# =============================================
        # HELIO 2010 HEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
	# ---------------------------------------------
	# 10.7 cm radio Solar Flux Monitor
	# web site: http://www.drao-ofr.hia-iha.nrc-cnrc.gc.ca/icarus/www/current.txt
	# hec_load_sfm.php
        # last 28-jun-2011
	# =============================================
	require ("hec_global.php");
//	$tempdir = "/var/www/hec/temp";

	// get files from HTTP
//	exec ("wget http://www.drao-ofr.hia-iha.nrc-cnrc.gc.ca/icarus/www/current.txt");
//        exec ("wget ftp://ftp.geolab.nrcan.gc.ca/data/solar_flux/daily_flux_values/fluxtable.txt");
//	exec ("wget ftp://ftp.geolab.nrcan.gc.ca/data/solar_flux/daily_flux_values/current.txt");
//1947 to 1996: ftp://ftp.geolab.nrcan.gc.ca/data/solar_flux/daily_flux_values/pre96flx.zip
//1996 to 2007: ftp://ftp.geolab.nrcan.gc.ca/data/solar_flux/daily_flux_values/solradflux_feb96_to_jun062007.txt.zip
	//copy ("current.txt",$tempdir."/current.txt");
	unlink ($tempdir."current.txt");
//        exec("cp ".$tempdir."/nrao75to07.prn current.txt");//first part
	copy ($tempdir."/nrao75to07.prn",$tempdir."/current.txt");//overwrite first part
//        exec("tail -n +5 current.txt >> ".$tempdir."/current.txt");//append without headers
//        exec("tail -n +5 fluxtable.txt >> ".$tempdir."/current.txt");//append without headers
	//unlink ("current.txt");
//	unlink ("fluxtable.txt");

	// parse files and create postgres-ready file	
	$f1 = fopen("$tempdir/SFM.postgres.converted",'w');
	$f2 = fopen("$tempdir/current.txt",'r');
/*
Julian Day   Carrington  <---Date--->   U.T. Flux Density Values in s.f.u.
Number        Rotation   Year  Mo  Dy        Observed  Adjusted  Series D
=========================================================================
02450128.250  01906.052  1996  02  14  1800  000068.6  000066.9  000060.2
02450667.333  001925.818  1997  08  06  2000  000076.8  000079.0  000071.1  2
02450688.458  001926.592  1997  08  27  2300  000082.9  000084.6  000076.1  2  0  Normal
*/
	for ($i=0; $i<3; $i++) { $buffer = fgets($f2); } //skip first three lines
	$s = 0;
	while (!feof ($f2)) {
		$buffer = fgets($f2);
/*
		if (substr($buffer,25,1)=="1") { $s = 0; } else { $s = 1; }#shift in columns after 1997-08-27
		$year = substr($buffer,25+$s,4);
		$month = substr($buffer,31+$s,2);
		$day = substr($buffer,35+$s,2);
		$h = substr($buffer,39+$s,2);
		$observed = substr($buffer,45+$s,8);
		$adjusted = substr($buffer,55+$s,8);
		$seriesd = substr($buffer,65+$s,8);
*/
//		$arr = split(" ",trim($buffer));//better?
                $buffer = str_replace("\t"," ",$buffer);
                $buffer = str_replace(","," ",$buffer);
                $arr = preg_split("/( +)/",trim($buffer));
                $year = $arr[2];
                $month = $arr[3];
                $day = $arr[4];
                if (($year=="1996") and ($month=="4") and ($day=="1")) {
                  $new_format = true;
                }
               if ($new_format) {
                $h = substr($arr[5],0,2);
//print_r($h."\n");
                $observed = $arr[6];
                $adjusted = $arr[7];
                $seriesd = $arr[8];
               } else {
                $h = "20";
//print_r($h."\n");
                $observed = $arr[5];
                $adjusted = $arr[6];
                $seriesd = $arr[7];
               }
		if ($observed=="") { $observed = "\N"; }//if empty insert as NULL
		if ($adjusted=="") { $adjusted = "\N"; }//if empty insert as NULL
		if ($seriesd=="") { $seriesd = "\N"; }//if empty insert as NULL
//		$out = sprintf("%04d-%02d-%02d %02d:00:00\t\N\t%f\t%f\t%f\n",$year,$month,$day,$h,$observed,$adjusted,$seriesd);
                //add dummy interval -30min +30min
		$out = sprintf("%04d-%02d-%02d %02d:30:00\t%04d-%02d-%02d %02d:30:00\t%f\t%f\t%f\n",$year,$month,$day,$h-1,$year,$month,$day,$h,$observed,$adjusted,$seriesd);
		//$out = "$day/$month/$year $h:00:00\t\N\t$observed\t$adjusted\t$seriesd\n";
		if ((strlen($buffer)>5) and (strlen($year)==4) and (is_numeric($year))) { fwrite($f1,$out); }
//		echo "$buffer ".strlen($buffer)."-".substr($buffer,25,1)."-$s\n";
		echo "$year>>>> $month $day $h $observed\n";
	}
	fclose($f2);
	fclose($f1);
?>
