<?php
	# =============================================
	# INAF - Trieste Astronomical Observatory
        # HELIO 2010 HEC server - by Andrej Santin
	# ---------------------------------------------
	// read NOAA proton event catalogue
	// web site: http://umbra.nascom.nasa.gov/SEP/seps.html
        # hec_load_npe.php
        # last 28-jun-2011
	# =============================================
	// Doesn't parse latitude and longitude: too much irregular
	
	function parse_date($year,$datetime)
	{
		$months = array("Jan"=>1,"Feb"=>2,"Mar"=>3,"Apr"=>4,"May"=>5,"Jun"=>6,
			"Jul"=>7,"Aug"=>8,"Sep"=>9,"Oct"=>10,"Nov"=>11,"Dec"=>12,"June"=>6);
		$z = preg_split('/\/|( +)/',$datetime);
//		$t = sprintf("%02d/%02d/%04d %02d:%02d:00",$z[1],$months[$z[0]],$year,substr($z[2],0,2),substr($z[2],3,2));
		$t = sprintf("%04d/%02d/%02d %02d:%02d:00",$year,$months[$z[0]],$z[1],substr($z[2],0,2),substr($z[2],3,2));
		if (($datetime=="") or (strlen($datetime)==strlen(trim($datetime,"0123456789")))) $t = "\N";
		return $t;
	}
	
	
	require ("hec_global.php");
//	$tempdir = "/var/www/hec/temp";
	
	// get files from HTTP
	exec ("wget http://umbra.nascom.nasa.gov/SEP/seps.html");
	copy ("seps.html",$tempdir."/seps.html");
	unlink ("seps.html");
	
	// parse files and create postgres-ready file	
	$f1 = fopen("$tempdir/NPE.postgres.converted",'w');
	$f2 = fopen("$tempdir/seps.html",'r');
	$previous = "";
	while (!feof ($f2)) {
    		$buffer = fgets($f2);
		if (1 == preg_match('/^( *)<td>/',$buffer)) {
			// found the continuation of a line of data
			$previous = $previous . $buffer;
		} else {
			// if there's one, store the previous data line
			if ($previous<>"") {
				// remove <tr>, </tr> and linefeed		
				$previous = preg_replace('/(<tr>)|(<\/tr>)|(\n)/','',$previous);
				$v = preg_split('/( *)<td[^>]*>( *)/',$previous);
				// echo "YEAR:$year,$v[1],$v[2],$v[3],$v[4],$v[5],$v[6],$v[7],$v[8],$v[9]\n";
				
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
				$v[7]=str_replace("<em>none</em>","",$v[7]);
				$c = preg_split('/\//',$v[7]);
				$c[1] = strtolower($c[1]);#optical_class
				if ($c[0]=="") $c[0]="\N";
				if ($c[1]=="") $c[1]="\N";                        
				$v[9] = preg_replace('/[^0-9]/','',$v[9]);
				if ($v[9]=="") $v[9]="0";
                                if (strlen($v[1])>12) $v[1] = substr($v[1],0,11);
                                print_r($v);
				$out =  parse_date($year,$v[1])."\t".parse_date($year,$v[2])."\t".$v[9]."\t";
				$out .= $lat."\t".$lon."\t".str_replace(",","",$v[3])."\t".$v[5]."\t";
				$out .= parse_date($year,$v[6])."\t".$c[0]."\t".$c[1]."\n";
				fwrite($f1,$out);			
                                //if ($year=='2005') {print_r($v); echo $out;}
				$previous = "";			
			}					
			if (1 == preg_match('/^<tr><td><td><td><td><strong>[0-9]/',$buffer)) {
				// found a line with year stamp
				preg_match('/[0-9]+/',$buffer,$match);
				$year = $match[0]; 
			} elseif (1 == preg_match('/^<tr><td>[A-Z]/',$buffer)) {
				// found a new line of data
				$previous = $buffer;
			}	
		}		
	}	
	fclose($f2);		
	fclose($f1);	
	
?>
