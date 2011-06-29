<?php
	# =============================================
        # HELIO 2010 HEC server - by Andrej Santin
	# INAF - Trieste Astronomical Observatory
        # hec_load_srs.php
        # last 28-jun-2011
	# ---------------------------------------------
	# read SRS catalogue
	# web site: ftp://solar.sec.noaa.gov/pub/forecasts/SRS
	# =============================================
	define("DEBUG","0");//after 06-jan-2007 ftp is dead!!!
   	                   //after 05-jun-2009 again ok
//alternative 05-jun-2009:
//wget http://www.swpc.noaa.gov/ftpdir/warehouse/2008/2008_SRS.tar.gz
//wget ftp://ftp.swpc.noaa.gov/pub/warehouse/2009/SRS/*

	require ("hec_global.php");
	$remotedir = "http://www.sec.noaa.gov/ftpdir/indices/old_indices";
	$tempdirsrs = "$tempdir/srs.archive";
	
    $months = array("JAN"=>1,"FEB"=>2,"MAR"=>3,"APR"=>4,"MAY"=>5,"JUN"=>6,"JUL"=>7,"AUG"=>8,"SEP"=>9,"OCT"=>10,"NOV"=>11,"DEC"=>12);
    $curyear=date("Y");//"2009";
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
	//ftp_chdir($conn,'pub/forecasts/SRS');
	ftp_chdir($conn,'pub/warehouse/'.$curyear.'/SRS');
	$list = ftp_nlist($conn, "*.txt");
	foreach ($list as $file) {
		ftp_get($conn,"$tempdirsrs/$file",$file,FTP_ASCII);
		echo "Got file $file\n";
	}
	ftp_close($conn);
} #debug==0

	// parse files and create postgres-ready file
	$f1 = fopen("$tempdir/SRS2.postgres.converted",'w');
	$i=0;

      for ($iii=0;$iii<3;$iii++) {
        // from srs.srchive
	// get file list
        switch ($iii) {
        case 0:
          $s = shell_exec("ls $tempdirsrs/srs.1*");
	//$list1 = split("\n",$s);
          break;
        case 1:
	  $s = shell_exec("ls $tempdirsrs/srs.2*");
	//$list2 = split("\n",$s);
          break;
        case 2:
	  $s = shell_exec("ls $tempdirsrs/2*.txt");
	//$list3 = split("\n",$s);
          break;
        }
        $list = split("\n",$s);
//	$list=array_merge($list1,$list2,$list3);
	
	foreach ($list as $file) {
	 if (strlen($file)>4) {
//		echo "$file\n";
		$f2 = fopen("$file",'r');
		$i++;#files counter
		do {
			$buffer = fgets($f2);
			if (substr($buffer,0,3)=="SRS") {#find day,month,year 7,8,9
				$ybuf=split(" ",$buffer);
			}
//			if ((strpos($buffer,"RECEIVED")>0) OR (strpos($buffer,"received")>0)) {#find date
//				$dbuf=split(" ",$buffer);
//			}
		} while ((substr($buffer,0,2)<>"I.") AND !feof($f2));#search until .I
		$buffer = fgets($f2);//skip comment
		$y=$ybuf[9];#year from file
//		if ( ($dbuf[8]=="31") AND ($months[strtoupper(substr($dbuf[9],0,3))]==12) ) $y=$y-1;
//		$date=sprintf("%04d/%02d/%02d",$y,$months[strtoupper(substr($dbuf[9],0,3))],$dbuf[8]);
		$date=sprintf("%04d/%02d/%02d",$ybuf[9],$months[strtoupper(substr($ybuf[8],0,3))],$ybuf[7]);
		$buffer = fgets($f2);
		$j=0;


		//REGIONS WITH SUNSPOTS
		while (is_numeric(substr($buffer,0,4)) AND strlen($buffer)>6){
			$out="";
//			echo "$date-$buffer\n";
			$out.=$date." 00:00:00";
//			$out.="\t\N";#end_time null
			$buf[1]=trim(substr($buffer,0,4));#nar
			if (!is_numeric($buf[1])) $buf[1]="";
			if (($y>2001) and ($buf[1]<9732) and ($buf[1]!="")) $buf[1]=$buf[1]+10000;//nar rollover
			if (substr($buffer,5,1)==" ") $sh=1; else $sh=0;
			// convert lat
			$st=trim(substr($buffer,5+$sh,3));
			if (substr($st,0,1)=="S")
				$buf[2]="-".substr($st,1,2);
			else
				$buf[2]=substr($st,1,2);
			if (!is_numeric($buf[2])) $buf[2]="";
			// convert long
			$st=trim(substr($buffer,8+$sh,3));
			if (substr($st,0,1)=="E")
				$buf[3]="-".substr($st,1,2);
			else
				$buf[3]=substr($st,1,2);
			if (!is_numeric($buf[3])) $buf[3]="";
			$buf[4]=trim(substr($buffer,14,3));#Lo
			$buf[5]=trim(substr($buffer,19,4));#Area
			$st=trim(substr($buffer,24,3));#Z
			if (strlen($st)==3) {
				$buf[6]=substr($buffer,24,1);#zurich
				$buf[7]=substr($buffer,25,1);#c-value
				$buf[8]=substr($buffer,26,1);#p-value
			} else {
				$buf[6]="";
				$buf[7]="";
				$buf[8]="";
			}
			$buf[9]=trim(substr($buffer,29,2));#LL
			$buf[10]=trim(substr($buffer,33,3));#NN
			$buf[11]=trim(substr($buffer,37,20));#MAG TYPE
			$buf[12]="REGIONS WITH SUNSPOTS";
			for ($k=1;$k<=12;$k++) {
				if ($buf[$k]=="") $buf[$k]="\N";
				$out.="\t".$buf[$k];
			}
			$out.="\n";
			if (strlen($out)>50) fwrite($f1,$out);
			$buffer = fgets($f2);
			$j++;
		}#while (is_numeric(substr($buffer,0,4)) AND strlen($buffer)>6)


		//H-ALPHA PLAGES WITHOUT SPOTS
		while ((substr($buffer,0,3)<>"IA.") AND (!feof($f2))) {#search until IA. (or 1A.?)
			$buffer = fgets($f2);
		}
		$buffer = fgets($f2);//skip comment
		$buffer = fgets($f2);
//		echo "$file***$buffer\n";
		while (is_numeric(substr($buffer,0,4)) AND strlen($buffer)>6){
			$out="";
			$out.=$date." 00:00:00";
//			$out.="\t\N";#end_time null
			$buf[1]=trim(substr($buffer,0,4));#nar
			if (!is_numeric($buf[1])) $buf[1]="";
			if (($y>2001) and ($buf[1]<9732) and ($buf[1]!="")) $buf[1]=$buf[1]+10000;//nar rollover
			if (substr($buffer,5,1)==" ") $sh=1; else $sh=0;
			// convert lat
			$st=trim(substr($buffer,5+$sh,3));
			if (substr($st,0,1)=="S")
				$buf[2]="-".substr($st,1,2);
			else
				$buf[2]=substr($st,1,2);
			if (!is_numeric($buf[2])) $buf[2]="";
			// convert long
			$st=trim(substr($buffer,8+$sh,3));
			if (substr($st,0,1)=="E")
				$buf[3]="-".substr($st,1,2);
			else
				$buf[3]=substr($st,1,2);
			if (!is_numeric($buf[3])) $buf[3]="";
			$buf[4]=trim(substr($buffer,15,3));#Lo
			for ($k=5;$k<=11;$k++) $buf[$k]="";
			$buf[12]="H-ALPHA PLAGES WITHOUT SPOTS";
			for ($k=1;$k<=12;$k++) {
				if ($buf[$k]=="") $buf[$k]="\N";
				$out.="\t".$buf[$k];
			}
			$out.="\n";
			if (strlen($out)>50) fwrite($f1,$out);
			$buffer = fgets($f2);
			$j++;
		}#while (is_numeric(substr($buffer,0,4)) AND strlen($buffer)>6)


		//REGIONS DUE TO RETURN
		while ((substr($buffer,0,3)<>"II.") AND !feof($f2)) {#search until .II
			$buffer = fgets($f2);
		}
		$buffer = fgets($f2);//skip comment
		$buffer = fgets($f2);
//		echo "$file---$buffer\n";
		while (is_numeric(substr($buffer,0,4)) AND strlen($buffer)>6){
			$out="";
			$out.=$date." 00:00:00";
//			$out.="\t\N";#end_time null
			$buf[1]=trim(substr($buffer,0,4));#nar
			if (!is_numeric($buf[1])) $buf[1]="";
			if (($y>2001) and ($buf[1]<9732) and ($buf[1]!="")) $buf[1]=$buf[1]+10000;//nar rollover
			if (substr($buffer,5,1)==" ") $sh=1; else $sh=0;
			// convert lat
			$st=trim(substr($buffer,5+$sh,3));
			if (substr($st,0,1)=="S")
				$buf[2]="-".substr($st,1,2);
			else
				$buf[2]=substr($st,1,2);
			if (!is_numeric($buf[2])) $buf[2]="";
			$buf[3]="";#lon
			//$buf[4]=trim(substr($buffer,12,3));#Lo
			$buf[4]="";#Lo //removed on Bob ask
			for ($k=5;$k<=11;$k++) $buf[$k]="";
			$buf[12]="REGIONS DUE TO RETURN";
			for ($k=1;$k<=12;$k++) {
				if ($buf[$k]=="") $buf[$k]="\N";
				$out.="\t".$buf[$k];
			}
			$out.="\n";
			if (strlen($out)>50) fwrite($f1,$out);
			$buffer = fgets($f2);
			$j++;
		}#while (is_numeric(substr($buffer,0,4)) AND strlen($buffer)>6)

		fclose($f2);	
	 }#if strlen($file)
	}#foreach
       }//for iii       
	
	fclose($f1);
	echo "readed files=$i\n";
?>
