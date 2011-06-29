 <?php
	# =============================================
	# EGSO 2003 - by Max Jurcev
	# ---------------------------------------------
	# PHP global functions
	# last 09-feb-2005
	# =============================================
    $secdir = "/var/www/hec";
    $tempdir = "$secdir/temp";
    $urldir = "http://localhost/hec";
	
	# =============================================
	# SEC header for web pages
	# =============================================
	function hec_header($title) {
		$s = "<html><head><title>$title</title>";
		$s .= '<link rel=stylesheet HREF="sec.css" TYPE="text/css">';
		$s .= '<link rel=stylesheet HREF="scrollable.css" TYPE="text/css">';
		$s .= '<meta content="">';
		$s .= '<link rel="SHORTCUT ICON" href="logo_inv2.gif">';
		$s .= '</head><body>';
		$s .= '<table><tr>';
		$s .= '<td><a href="http://www.helio-vo.eu"><img src="helio_logo.gif" border=0></a></td>';
		$s .= '<td><font size=+3 color=red><b>Heliophysics Event Catalogue</b></td>';
    $s .= '<td><a href="http://ec.europa.eu/research/fp7/index_en.cfm?pg=capacities"><img src="FP7-cap-RGB12.gif" border=0  width="95%"></a></td>';
		$s .= '</tr></table><hr>'."\n";
		return $s;
	}
	
	# =============================================
	# SEC header for web pages (newgui)
	# =============================================
	function sec_header2($title) {
		$s = "<html><head><title>$title</title>";
		$s .= '<link rel=stylesheet HREF="newgui/stili.css" TYPE="text/css">';
		$s .= '<meta content=""><style></style></head>';
		$s .= '<link rel="SHORTCUT ICON" href="logo_inv2.gif">';
		$s .= '<body>';
		$s .= '<h1><a href="http://www.helio-vo.eu"><img src="helio_logo.gif" border=0></a> Heliophysics Event Catalogue</h1>';
		$s .= '<hr>';
		return $s;
	}
	
	# =============================================
	# SEC footer for web pages
	# =============================================
	function sec_footer() {
		$s = '<hr>';
		$s .= '<div align=right><table><tr><td><small>Designed and maintained by ';
		$s .= '';
		$s .= '<a href="http://www.oats.inaf.it">INAF-TRIESTE ASTRONOMICAL OBSERVATORY</a></td>';
		//$s .= '<br><a href="http://www.inaf.it/" target="_blank"><img src="l_inaf.gif" alt="INAF" width="59" height="59" border="0"></a>';
		$s .= '<td><a href="http://www.inaf.it/" target="_blank"><img src="Inaf-circ-colore-N_10.gif" border="1" width="50%"></a></td>';
		//$s .= '&nbsp;<a href="http://www.ts.astro.it/" target="_blank"><img src="l_oat.gif" alt="Trieste Astronomical Observatory" border="0"></a>';
		$s .= '</tr></table></body></html>';
		return $s;
	}
	
	# =============================================
	# SEC error web pages
	# =============================================
	function sec_error($msg) {
		echo '<h2>ERROR</h2>';
		echo $msg;
		echo (sec_footer());
	}
	
	# =============================================
	# SEC 
	# =============================================
	function sec_datetime($varname,$isBegin) {
		# Show a date time control
		# If $varname='xxx' then put results into variables
		# $y_xxx, $mo_xxx, $d_xxx, $h_xxx, $mi_xxx
		# Preselect date with current date
		# If IsBegin==TRUE then preselect time with 00:00:00
		# else with 23:59:59

		$now = getdate();
		$arrmonth = array ('January','February','March','April','May','June',
			'July','August','September','October','November','December');
		echo "&nbsp;&nbsp;";

		echo '<select name="y_',"$varname",'" autocomplete="off">';
		for ($i=1995; $i<=$now['year']; $i++) {
			if ($i==$now['year'])
				echo '<option value="',$i,'" selected>',$i;
			else
				echo '<option value="',$i,'">',$i;
		}
		echo '</select>';

		echo '<select name="mo_',"$varname",'">';
		for ($i=0; $i<12; $i++) {
			if (($i+1)==$now['mon'])
				echo '<option value="',$i+1,'" selected>',$arrmonth[$i];
			else
				echo '<option value="',$i+1,'">',$arrmonth[$i];
		}
		echo '</select>';

		echo '<select name="d_',"$varname",'">';
		for ($i=1; $i<=31; $i++) {
			if ($i==$now['mday'])
				echo '<option value="',$i,'" selected>',$i;
			else
				echo '<option value="',$i,'">',$i;
		}
		echo '</select>';

		echo "&nbsp;&nbsp;";

		if ($isBegin) {
			$hDef = 0;
			$miDef = 0;
		} else {
			$hDef = 23;
			$miDef = 59;
		}

		echo '<select name="h_',"$varname",'">';
		for ($i=0; $i<=23; $i++) {
			if ($i==$hDef)
				echo '<option value="',$i,'" selected>',sprintf('%02d',$i);
			else
				echo '<option value="',$i,'">',sprintf('%02d',$i);
		}
		echo '</select>';

		echo '<select name="mi_',"$varname",'">';
		for ($i=0; $i<=59; $i++) {
			if ($i==$miDef)
				echo '<option value="',$i,'" selected>',sprintf('%02d',$i);
			else
				echo '<option value="',$i,'">',sprintf('%02d',$i);
		}
		echo '</select>';
  	}
	# =============================================
	# SEC 
	# =============================================
	function sec_datetimesec($varname,$isBegin) {
		# Show a date time control
		# If $varname='xxx' then put results into variables
		# $y_xxx, $mo_xxx, $d_xxx, $h_xxx, $mi_xxx, $s_xxx
		# Preselect date with current date
		# If IsBegin==TRUE then preselect time with 00:00:00
		# else with 23:59:59

		$now = getdate();
		$arrmonth = array ('January','February','March','April','May','June',
			'July','August','September','October','November','December');
		echo "&nbsp;&nbsp;";

		echo '<select name="y_',"$varname",'">';
		if ($isBegin) {
			if ($now['mon']==1) {
				$yy=-1;
				$mm=11;
			} else {
				$yy=0;
				$mm=-1;
			}
		} else {
			$yy=0;
			$mm=0;
		}
		for ($i=1975; $i<=$now['year']; $i++) {
			if ($i==$now['year']+$yy)
				echo '<option value="',$i,'" selected>',$i;
			else
				echo '<option value="',$i,'">',$i;
		}
		echo '</select>';

		echo '<select name="mo_',"$varname",'">';
		for ($i=0; $i<12; $i++) {
			if (($i+1)==$now['mon']+$mm)
				echo '<option value="',$i+1,'" selected>',$arrmonth[$i];
			else
				echo '<option value="',$i+1,'">',$arrmonth[$i];
		}
		echo '</select>';

		echo '<select name="d_',"$varname",'">';
		for ($i=1; $i<=31; $i++) {
			if ($i==$now['mday'])
				echo '<option value="',$i,'" selected>',$i;
			else
				echo '<option value="',$i,'">',$i;
		}
		echo '</select>';

		echo "&nbsp;&nbsp;";

		if ($isBegin) {
			$hDef = 0;
			$miDef = 0;
			$sDef = 0;
		} else {
			$hDef = 23;
			$miDef = 59;
			$sDef = 59;
		}

		echo '<select name="h_',"$varname",'">';
		for ($i=0; $i<=23; $i++) {
			if ($i==$hDef) 
				echo '<option value="',$i,'" selected>';
			else
				echo '<option value="',$i,'">';
			echo sprintf('%02d',$i);
		}
		echo '</select>';

		echo '<select name="mi_',"$varname",'">';
		for ($i=0; $i<=59; $i++) {
			if ($i==$miDef)
				echo '<option value="',$i,'" selected>';
			else
				echo '<option value="',$i,'">';
			echo sprintf('%02d',$i);
		}
		echo '</select>';
		
		echo '<select name="s_',"$varname",'">';
		for ($i=0; $i<=59; $i++) {
			if ($i==$sDef)
				echo '<option value="',$i,'" selected>';
			else
				echo '<option value="',$i,'">';
			echo sprintf('%02d',$i);
		}
		echo '</select>';
  	}
	# =============================================
	# date control
	# =============================================
	function sec_date($varname,$isBegin) {
		# Show a date control
		# If $varname='xxx' then put results into variables
		# $y_xxx, $mo_xxx, $d_xxx
		# Preselect date with current date

		$now = getdate();
		$arrmonth = array ('January','February','March','April','May','June',
			'July','August','September','October','November','December');
		echo "&nbsp;&nbsp;";

		echo '<select name="y_',"$varname",'" autocomplete="off">';
		if ($isBegin) {
			if ($now['mon']==1) {
				$yy=-1;
				$mm=11;
			} else {
				$yy=0;
				$mm=-1;
			}
		} else {
			$yy=0;
			$mm=0;
		}
		for ($i=1975; $i<=$now['year']; $i++) {
			if ($i==$now['year']+$yy)
				echo '<option value="',$i,'" selected>',$i;
			else
				echo '<option value="',$i,'">',$i;
		}
		echo '</select>';

		echo '<select name="mo_',"$varname",'" autocomplete="off">';
		for ($i=0; $i<12; $i++) {
			if (($i+1)==$now['mon']+$mm)
				echo '<option value="',$i+1,'" selected>',$arrmonth[$i];
			else
				echo '<option value="',$i+1,'">',$arrmonth[$i];
		}
		echo '</select>';

		echo '<select name="d_',"$varname",'" autocomplete="off">';
		for ($i=1; $i<=31; $i++) {
			if ($i==$now['mday'])
				echo '<option value="',$i,'" selected>',$i;
			else
				echo '<option value="',$i,'">',$i;
		}
		echo '</select>';
  	}
	# =============================================
	# show db query in a table
	# =============================================
  	function sec_showresult($result) {
		$n = pg_numfields($result);
		echo '<table border=1 width="100%">';
		echo '<tr>';
		for ($j=0; $j<$n; $j++) {
			$a = pg_fieldname($result,$j);
			echo ("<td>$a</td>");
		}
  		echo '</tr>';

		$rows = pg_numrows($result);
		for ($i=0; $i<$rows; $i++) {
			echo '<tr>';
			$row = pg_fetch_row($result,$i);
			for ($j=0; $j<$n; $j++) {
				echo ("<td>$row[$j]</td>");
			}
			echo '</tr>';
		}
		echo '</table>';
		if ($rows==0) {
			echo '<p><b>No matches found</b>';
		} else {
			echo "<p>$rows items found";
		}
	}
	# =============================================
	# counter for web page
	# =============================================
	function sec_counter() {
		$fc = "counter.txt";
		if (file_exists($fc)) {
			$pf = fopen($fc,"r+");
			flock($pf,2);
			$c = fgets($pf,4);
			$c++;
			rewind($pf);
			fputs($pf,$c,4);
			flock($pf,3);
			fclose($pf);
		} else {
			$pf = fopen($fc,"w");
			$c = "1";
			flock($pf,2);
			fputs($pf,$c,4);
			flock($pf,3);
			fclose($pf);
		}
		return $c;
	}
	# =============================================
	# transform signed int into N/S, E/W coordinate
	# if isLat=TRUE then assume data to be a latitude
	# =============================================
  	function sec_latlon($val,$isLat) {
		$v = intval($val);
		if ($isLat) {
			if ($v<0)
				$r = sprintf("S %d",-1*$v);
			else
				$r = sprintf("N %d",$v);
		} else {
			if ($v<0)
				$r = sprintf("W %d",-1*$v);
			else
				$r = sprintf("E %d",$v);
		}
		return $r;
	}

function long_carr($y,$mo,$d,$et,$helio_long) {
    //; based on sun.pro code (SolarSoft)
    //;       Notes: based on the book Astronomical Formulae
    //;         for Calculators, by Jean Meeus.
    $jd=GregorianToJD( $mo, $d, $y)-0.5+$et/24.0;
    $PI = 3.14159265358979;
    $radeg=180.0/$PI;
    $T1900=2415020.0;
    //;        Julian Centuries from 1900.0               ;
    $t = ($jd - $T1900) / 36525.0;
    $t2 = $t*$t;
    $t3 = $t2*$t;
    //;        Geometric Mean Longitude (deg)             ;
    $mnl = 279.69668 + 36000.76892*$t + (0.0003025*$t2);
    $mnl = fmod($mnl,360.0);
    //;        Mean anomaly (deg)                         ;
    $mna = 358.47583 + 35999.04975*$t - 0.000150*$t2 - 0.0000033*$t3;
    $mna = fmod($mna,360.0);
    //;        Sun's equation of center (deg)             ;
    $mna = $mna/$radeg;
    $c = (1.919460 - 0.004789*$t - 0.000014*$t2)*sin($mna) + (0.020094 - 0.000100*$t)*sin(2*$mna) + 0.000293*sin(3*$mna);
    //;        Sun's true geometric longitude (deg)       ;
    //;        refered to the mean equinox of date.       ;
    //;        Should the higher accuracy terms (not      ;
    //;        included here) be added to true_long?      ;
    //;        (from which app_long is derived).          ;
    $true_long = fmod($mnl + $c,360.0);
    //;        Apparent longitude (deg) from true         ;
    //;        longitude.                                 ;
    $omega=259.18-1934.142*$t;
    //;        Heliographic coordinates                   ;
    $theta=($jd-2398220.0)*360.0/25.38;
    $i=7.25;
    $k=74.3646+1.395833*$t;
    $lambda=$true_long-0.00569;
    $lambda2=$lamda-0.00479*sin($omega/$radeg);
    $diff=($lambda-$k)/$radeg;
    //;        Latitude at center of disk (deg)           ;
    $lat0=asin(sin($diff)*sin($i/$radeg))*$radeg;
    //;        Longitude at center of disk (deg)          ;
    $y=-sin($diff)*cos($i/$radeg);
    $x=-cos($diff);
    //convert x,y->r,eta /DEG
    $eta=atan($y/$x)*$radeg;
    if ($x<0) $eta+=180.0;
    if ($eta<0) $eta+=360.0;
    $long0=fmod($eta-$theta,360.0);
    if ($long0<0) $long0+=360.0;
    $out=round(fmod($helio_long+$long0,360.0),2);
    return ($out);
    }//long_carr
  
  ?>
