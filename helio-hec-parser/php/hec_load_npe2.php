<?php
	# =============================================
	# EGSO 2003 SEC server - by Max Jurcev
	# INAF - Trieste Astronomical Observatory
	# ---------------------------------------------
	// read NOAA proton event catalogue
	// web site: http://umbra.nascom.nasa.gov/SEP/seps.html
	# last 25-oct-2006 by Andrej Santin
	# =============================================
	// Doesn't parse latitude and longitude: too much irregular
	
	function parse_date($year,$datetime)
	{
		$months = array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,
			"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12,"June"=>6);
    $datetime = str_replace("~","",$datetime);
		$z = preg_split('/\/|( +)/',$datetime);
		//print_r($z); print "\n";
//		$t = sprintf("%02d/%02d/%04d %02d:%02d:00",$z[1],$months[$z[0]],$year,substr($z[2],0,2),substr($z[2],3,2));
		$t = sprintf("%04d/%02d/%02d %02d:%02d:00",$year,$months[$z[0]],$z[1],substr($z[2],0,2),substr($z[2],2,2));
		if (($datetime=="") or (strlen($datetime)==strlen(trim($datetime,"0123456789")))) $t = "\N";
		return $t;
	}
	
	
	require ("hec_global.php");
//	$tempdir = "/var/www/html/sec/temp";
	
	// get files from HTTP
	exec ("wget http://www.swpc.noaa.gov/ftpdir/indices/SPE.txt");
	copy ("SPE.txt",$tempdir."/SPE.txt");
	unlink ("SPE.txt");
	
	// parse files and create postgres-ready file	
	$f1 = fopen("$tempdir/NPE2.postgres.converted",'w');
	$f2 = fopen("$tempdir/SPE.txt",'r');
	$previous = "";
	
	//skip header
	for ($i=0;$i<13;$i++) $buffer = fgets($f2);
  $year = 1976;
  $stop=false;
	while (!feof ($f2)) {
    		$buffer = trim(fgets($f2));
        while (strlen($buffer)==0) $buffer = fgets($f2);
    		if (substr($buffer,0,5)=="=====") break;
    		echo (">>".$buffer."<<\n");
    		if (is_numeric(substr($buffer,16,4))) {
    		  $year = substr($buffer,16,4);
    		  while (!is_numeric(substr($buffer,4,2))) $buffer = trim(fgets($f2));
        }
//Jan 10/2045      Jan 11/0530	  91	E/08 1754 
        if (substr($buffer,40,2)=="  ") {
          $buffer = substr_replace($buffer, "\N", 39, 0);
          echo (">->".$buffer."<-<\n");
          $stop=true;
        } else {
          $new = substr($buffer,40,13);
          $new = str_replace(" ",",",$new);
          $buffer = substr_replace($buffer,$new,40,13);
        }
        
    		$buffer .= "  ";
    		$len = strlen($buffer)+1;
    		while ($len != strlen($buffer)) {
    		  $len = strlen($buffer);
    		  $buffer = str_replace("  "," ",$buffer);
        }
				$v = explode(" ", $buffer);
				echo ($year);
				print_r($v);
				//if (stop) exit(0);
				if ($year==2002) exit(0);
				
				// convert lat/long
				//echo "$v[8]\n";
				$lat=trim(substr($v[8],0,3));
				$lon=trim(substr($v[8],3,3));
				if (is_numeric(substr($lat,1,2)) AND is_numeric(substr($lon,1,2))) {
					if (substr($lat,0,1)=="S")
						$lat="-".substr($lat,1,2);
					else
						$lat=substr($lat,1,2);
					if (substr($lon,0,1)=="E")
						$lon="-".substr($lon,1,2);
					else
						$lon=substr($lon,1,2);
				} else {
					$lat = "\N";
					$lon = "\N";
				}
//				print "lat=$lat lon=$lon\n";

				$c = preg_split('/\//',$v[7]);
				$c[1] = strtolower($c[1]);#optical_class
				if ($c[0]=="") $c[0]="\N";
				if ($c[1]=="") $c[1]="\N";
				                        
				$v[9] = preg_replace('/[^0-9]/','',$v[9]);
				if ($v[9]=="") $v[9]="0";
        if (strlen($v[1])>12) $v[1] = substr($v[1],0,11);

				if ($v[5]=="") $v[5]="\N";
				if (($v[10]=="") or (!is_numeric($v[10]))) $v[10]="\N";

//time_start,time_peak,nar,latitude,longitude,proton_flux,assoc_cme,assoc_flare_pk,xray_class,optical_class
				$out =  parse_date($year,$v[0]." ".$v[1])."\t";
				$out .= parse_date($year,$v[2]." ".$v[3])."\t";
				$out .= $v[10]."\t";//nar
				$out .= $lat."\t".$lon."\t";
				$out .= $v[4]."\t";//proton_flux
				$out .= $v[5]."\t";//assoc_cme
				$out .= parse_date($year,$v[6]." ".$v[7])."\t";
				$out .= $c[0]."\t".$c[1]."\n";
				print($out."\n");
				fwrite($f1,$out);			
				$previous = "";			
	}//while	
	fclose($f2);		
	fclose($f1);	
	
?>
