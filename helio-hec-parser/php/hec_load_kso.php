<?php
	# =============================================
        # HELIO 2010 HEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
        # hec_load_kso.php
        # last 28-jun-2011
	# ---------------------------------------------
	# read Kanzelhoehe flare list
	# web site: ftp.kso.ac.at
	# =============================================
	define("DEBUG","0");
	require ("hec_global.php");
//	$tempdir = "/var/www/hec/temp/hef";
	

if (DEBUG==0) {	
	// get files from FTP site
	$conn = ftp_connect("ftp.kso.ac.at");
	if (!$conn) {
		echo 'FTP connect error';
		exit;
	}
	if (ftp_login($conn, 'download','9521treffen')) {
    		echo "Connected\n";
	} else {
    		echo "Couldn't connect\n";
	}
	ftp_chdir($conn,'KSO_flares');
	$list = ftp_nlist($conn, "flares*");
	foreach ($list as $file) {
		ftp_get($conn,"$tempdir/$file",$file,FTP_ASCII);
		echo "Got file $file\n";
	}
	ftp_close($conn);
} else {#debug==0

	$list[0]="flares.txt";
	$list[1]="flares.2005.txt";
}

    // parse files and create postgres-ready file	
    $months = array("JAN"=>1,"FEB"=>2,"MAR"=>3,"APR"=>4,"MAY"=>5,"JUN"=>6,"JUL"=>7,"AUG"=>8,"SEP"=>9,"OCT"=>10,"NOV"=>11,"DEC"=>12);
    $f1 = fopen("$tempdir/KSO.postgres.converted",'w');
    foreach ($list as $file) {
        $f2 = fopen("$tempdir/$file",'r');
        $buffer = fgets($f2);//skip comment
        while (!feof ($f2)) {
            $buffer = fgets($f2);
            $buf=split("\t",$buffer);
            $date=$buf[1];
            $y = substr($date,0,4);
            $mo = substr($date,5,2);
            $d = substr($date,8,2);
            $time_start=$date." ".$buf[2];
            $time_start_m=$buf[3];
            $et=substr($buf[2],0,2)+substr($buf[2],3,2)/60;
            $time_peak=$date." ".$buf[4];
            $time_peak_m=$buf[5];
            $time_end=$date." ".$buf[6];
            $time_end_m=$buf[7];
            $lat=$buf[8];
            if (substr($lat,0,1)=="S")
                $lat="-".substr($lat,1,2);
            else
                $lat=substr($lat,1,2);
            $lon=$buf[9];
            if (substr($lon,0,1)=="E")
                $lon="-".substr($lon,1,2);
            else
                $lon=substr($lon,1,2);
            if (($lon!="") and ($et>-1))
                $longcarr=long_carr($y,$mo,$d,$et,$lon);
            else
                $longcarr="";
            //optical class
            $oc=strtolower(substr($buf[10],0,2));

//            $out = sprintf("%s\t%s\t%s\t%s\t%s\t%s",$time_start,$time_start_m,$time_peak,$time_peak_m,$time_end,$time_end_m,$lat,$lon,$longcarr,$oc);
            $buf2=array($time_start,$time_start_m,$time_peak,$time_peak_m,$time_end,$time_end_m,$lat,$lon,$longcarr,$oc);
            if (($buf2[0]=="") or ($buf2[0]==" ")) $out="\N"; else $out=$buf2[0];
            for ($k=1;$k<=9;$k++) {
                if (($buf2[$k]=="") or ($buf2[$k]==" ")) $buf2[$k]="\N";
                $out.="\t".$buf2[$k];
            }
            $out.="\n";
            fwrite($f1,$out);
        }//while        
    }//foreach
    
?>
