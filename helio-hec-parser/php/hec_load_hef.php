<?php
	# =============================================
        # HELIO 2010 HEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
	# hec_load_hef.php
        # last 28-jun-2011
	# ---------------------------------------------
	# read rhessi catalogue
	# web site: http://hesperia.gsfc.nasa.gov/ssw/hessi/dbase
	# =============================================
	
	//+++++++++++++++++++++++++++++++
	// TODO!!!
	// manage rollover on midnight
	// manage kev min-max
	// convert asec to heliographic degrees?
	// verify flags: are useful?	
	//+++++++++++++++++++++++++++++++

	require ("hec_global.php");
	$remotedir = "http://hesperia.gsfc.nasa.gov/ssw/hessi/dbase";
//	$tempdir = "/var/www/hec/temp/hef";
	$tempdirhef = "$tempdir/hef";
    
	// get files from HTTP
	$curyear=date("Y");
	for ($y=2002;$y<=$curyear;$y++) {
		for ($m=1;$m<=12;$m++) {
			$f = sprintf("hessi_flare_list_%04d%02d.txt",$y,$m);
			exec ("wget $remotedir/$f");
			copy ($f,"$tempdirhef/$f");
			unlink ($f);
		}
	}
	
	// get file list
	$s = shell_exec("ls $tempdirhef/hessi_flare_list_*.txt");
	$list = split("\n",$s);
	
	// parse files and create postgres-ready file	
	$months = array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12);
	$f1 = fopen("$tempdir/HEF.postgres.converted",'w');
	foreach ($list as $file) {
		$f2 = fopen("$file",'r');
		if ($f2) {
			while (!feof ($f2)) {
				$buffer = fgets($f2);
				if (is_numeric(substr($buffer,0,4))) { 
					$word_array = split(" ",trim($buffer));
					$no_space ="";
					foreach ($word_array as $elem) {
						if (strlen($elem) <> 0)
							$no_space .=$elem." ";
					} 
					$pcs = explode(" ",trim($no_space));
					// get date
					$datesplit = split("-",$pcs[1]);
					$d = $datesplit[0];
					$mo = $months[$datesplit[1]];
					$y = $datesplit[2];
					if (checkdate($mo,$d,$y)) {
						$date = sprintf("%04d/%02d/%02d",$y,$mo,$d);		
					}
					// get time_start
					$h = substr($pcs[2],0,2);
					$mi = substr($pcs[2],3,2);
					$s = substr($pcs[2],6,2);
					if (is_numeric($h) && is_numeric($mi) && is_numeric($s)) {
						$time_start = $date.sprintf(" %02d:%02d:%02d",$h,$mi,$s);
					} else {
						$time_start = '\N';
					}
					// get time_peak
					$h = substr($pcs[3],0,2);
					$mi = substr($pcs[3],3,2);
					$s = substr($pcs[3],6,2);
					if (is_numeric($h) && is_numeric($mi) && is_numeric($s)) {
						$time_peak = $date.sprintf(" %02d:%02d:%02d",$h,$mi,$s);
					} else {
						$time_peak = '\N';
					}
					// get time_end
					$h = substr($pcs[4],0,2);
					$mi = substr($pcs[4],3,2);
					$s = substr($pcs[4],6,2);
					if (is_numeric($h) && is_numeric($mi) && is_numeric($s)) {
						$time_end = $date.sprintf(" %02d:%02d:%02d",$h,$mi,$s);
					} else {
						$time_end = '\N';
					}
//					$kev = substr($pcs[8],0,2);
					$kev = substr($pcs[8],0,strpos($pcs[8],"-"));
                                        if (!is_numeric($pcs[11])) $pcs[11]=0;
                                        if (!is_numeric($pcs[12])) $pcs[12]=0;


					$out = sprintf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n",
						$pcs[0],$time_start,$time_peak,$time_end,
						$pcs[5],$pcs[6],$pcs[7],$kev,$pcs[9],$pcs[10],$pcs[11],$pcs[12]
					);
					fwrite($f1,$out);			
				}		
			}	
			fclose($f2);	
		}	
	}
	fclose($f1);	
?>
