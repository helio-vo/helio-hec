<?php
	# =============================================
        # HELIO 2010 HEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
	# hec_load_soho.php
        # last 28-jun-2011
	# ---------------------------------------------
	# read the SoHO Campaign
	# http://sohowww.nascom.nasa.gov/data/summary/asplanned/campaign/soho_campaign.dat
	# =============================================
	define("DEBUG","0");
	
	require ("hec_global.php");
//	$tempdir = "/var/www/hec/temp";

	// get files from HTTP
	exec ("wget http://sohowww.nascom.nasa.gov/data/summary/asplanned/campaign/soho_campaign.dat");
	copy ("soho_campaign.dat",$tempdir."/soho_campaign.dat");
	unlink ("soho_campaign.dat");

	// parse files and create postgres-ready file	
	$f1 = fopen("$tempdir/SOHO.postgres.converted",'w');
	$f2 = fopen("$tempdir/soho_campaign.dat",'r');
	$buffer = fgets($f2);//skip 1st line

	$buffer = fgets($f2);//skip 2nd line
		
	for ($k=0;$k<=8;$k++) {
	  $maxlen[$k]=0;
	}
	$maxbuffer=0;
	$ii=0;
	
	while (!feof ($f2)) {
		$buffer = "";
		do {
		  $buffer2 = fgets($f2);
		  $buffer.=$buffer2;
		} while (strlen($buffer2)==1023);
    	
		$buf=split("~",$buffer);
		$buf[7]=$buf[7]." 00:00:00";
		$buf[8]=$buf[8]." 23:59:59";
		
		if (strlen($buffer)>$maxbuffer) { $maxbuffer=strlen($buffer); }
		for ($k=0;$k<=8;$k++) {
		  if (strlen($buf[$k])>$maxlen[$k]) { $maxlen[$k]=strlen($buf[$k]); }
		}

		$n=0;
        if ($buf[0]=="") $out="\N"; else $out=$buf[0];
        for ($k=1;$k<=8;$k++) {
            if ($buf[$k]=="") {
				$buf[$k]="\N";
				$n++;
			}
            $out.="\t".$buf[$k];
        }
        $out.="\n";
        if ($n<6) fwrite($f1,$out);
	}
	fclose($f2);
	fclose($f1);	
	
	for ($k=0;$k<=8;$k++) {
	  print "maxlen[".$k."]=".$maxlen[$k]."\n";
	}
	print "maxbuffer=".$maxbuffer."\n";
	print "ii=".$ii."\n";
    
?>
