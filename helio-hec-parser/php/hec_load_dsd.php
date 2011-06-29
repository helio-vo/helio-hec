<?php
	# =============================================
	# HELIO 2010 HEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
	# hec_load_dsd.php
	# last 28-jun-2011
	# ---------------------------------------------
	# read DSD catalogue
	# web site: http://www.sec.noaa.gov/ftpdir/indices/old_indices
	# =============================================
	define("DEBUG","0");
	
	require ("hec_global.php");
	$remotedir = "http://www.sec.noaa.gov/ftpdir/indices/old_indices";
//	$tempdir = "/var/www/hec/temp";
	
	$curyear=date("Y");
	if (DEBUG==0) {
		$i=0;
		// get files from HTTP
		for ($y=1994;$y<=$curyear-1;$y++) {
			$f = sprintf("%04d_DSD.txt",$y);
			$list[$i++]=$f;
			exec ("wget $remotedir/$f");
			copy ($f,"$tempdir/$f");
			unlink ($f);
		}
		//get quarters from current year
		for ($q=1;$q<=4;$q++) {
			$f = sprintf("%04dQ%d_DSD.txt",$curyear,$q);
			exec ("wget $remotedir/$f",$rr,$res);
			if ($res==0) {
				$list[$i++]=$f;
				copy ($f,"$tempdir/$f");
				unlink ($f);
			}
		}
	} else {
		$list[0]="1994_DSD.txt";
		$list[1]="1995_DSD.txt";
		$list[2]="1996_DSD.txt";
		$list[3]="1997_DSD.txt";
		$list[4]="1998_DSD.txt";
		$list[5]="1999_DSD.txt";
		$list[6]="2000_DSD.txt";
		$list[7]="2001_DSD.txt";
		$list[8]="2002_DSD.txt";
		$list[9]="2003_DSD.txt";
		$list[10]="2004Q1_DSD.txt";
	}
	
	// parse files and create postgres-ready file	
	$months = array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12);
	$f1 = fopen("$tempdir/DSD.postgres.converted",'w');
	foreach ($list as $file) {
		$f2 = fopen("$tempdir/$file",'r');

		while ( ($f2) AND (!feof ($f2))) {
//		while (!feof ($f2)) {
			$buffer = fgets($f2);
			if (is_numeric(substr($buffer,0,2))) {
				if (DEBUG==1) {
					$st=substr($buffer,0,11);
					echo "$file: $st \n";
				}
                                $buffer = str_replace("\t"," ",$buffer);
//				$pcs = explode(" ",trim($buffer));
				$pcs = preg_split("/( +)/",trim($buffer));
				$max=count($pcs);
				# Missing data is shown as -1, except X-ray Background Flux is shown as *.
				for ($i=0;$i<=$max;$i++)
					if ($pcs[$i]=="*" OR $pcs[$i]=="-1") $pcs[$i]="\N";# replace asterisc and -1 with null
				if ($max==8) {#1994 missing new regions
					$pcs[8]=$pcs[7];
					$pcs[7]=$pcs[6];
					$pcs[6]="\N";
					$max++;
				}
				while ($max<16) $pcs[$max++]="\N";#fill with nulls
//				print_r($pcs);exit;
				if (is_numeric($pcs[1])) {
					// get new date
					$time_start = sprintf("%04d/%02d/%02d 20:00:00",$pcs[0],$pcs[1],$pcs[2]);
				} else {
					// get old date
					$mo = $months[$pcs[1]];
					$time_start = sprintf("19%02d/%02d/%02d 20:00:00",$pcs[2],$mo,$pcs[0]);
				}
					$out = sprintf("%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n",
						$time_start,
						$pcs[3],$pcs[4],$pcs[5],$pcs[6],$pcs[7],$pcs[8],$pcs[9],$pcs[10],$pcs[11],
						$pcs[12],$pcs[13],$pcs[14],$pcs[15]
					);
if ($pcs[15]=="\N") for ($i=0;$i<=15;$i++) print_r($i.">".$pcs[$i]."<\n");
					fwrite($f1,$out);			
			}#if is numeric
		}#while	
		fclose($f2);	
	}#foreach
	
	fclose($f1);	
?>
