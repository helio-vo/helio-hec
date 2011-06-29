<?php
	# =============================================
        # HELIO 2010 HEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
        # hec_load_lcc.php
        # last 28-jun-2011
	# =============================================
	// read lasco cme catalogue
	// web site: http://cdaw.gsfc.nasa.gov/CME_list/UNIVERSAL/text_ver/
	define("DEBUG","0");
	require ("hec_global.php");
//	$tempdir = "/var/www/hec/temp";
	
if (DEBUG==0) {	
	// get files from HTTP
	exec ("wget http://cdaw.gsfc.nasa.gov/CME_list/UNIVERSAL/text_ver/univ_all.txt");
	copy ("univ_all.txt",$tempdir."/univ_all.txt");
	unlink ("univ_all.txt");
}
	
	// parse files and create postgres-ready file	
	$f1 = fopen("$tempdir/LCC.postgres.converted",'w');
	$f2 = fopen("$tempdir/univ_all.txt",'r');
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
			// get datetime
			$y = substr($pcs[0],0,4);
			$mo = substr($pcs[0],5,2);
			$d = substr($pcs[0],8,2);
			$h = substr($pcs[1],0,2);
			$mi = substr($pcs[1],3,2);
			$s = substr($pcs[1],6,2);
			if (checkdate($mo,$d,$y)) {
				$time = sprintf("%04d/%02d/%02d",$y,$mo,$d);
				if (is_numeric($h) && is_numeric($mi) && is_numeric($s))
					$time = $time.sprintf(" %02d:%02d:%02d",$h,$mi,$s);
			} else {
				$time = '\N';
			}
			if ($pcs[2]=="Halo")
				$pcs[2]=-1;
			for ($i=2;$i<=9;$i++) {
				if (substr($pcs[$i],1,1)=="-") {#empty field "----"
					$pcs[$i] = "\N";
				}
				if (substr($pcs[$i],0,1)==">") {#cut ">" from Width field
					$pcs[$i] = substr($pcs[$i],1,strlen($pcs[$i])-1);
				}
				if ((substr($pcs[$i],strlen($pcs[$i])-1,1)=="*") or (substr($pcs[$i],strlen($pcs[$i])-1,1)=="-")) {#cut "*" or "-" at end of Accel field
					$pcs[$i] = substr($pcs[$i],0,strlen($pcs[$i])-1);
				}
			}
			$out = sprintf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n",
				$time,$time,$pcs[2],$pcs[3],$pcs[4],$pcs[5],$pcs[6],$pcs[7],$pcs[8],$pcs[11]);
			fwrite($f1,$out);			
		}		
	}	
	fclose($f2);		
	fclose($f1);	
?>
