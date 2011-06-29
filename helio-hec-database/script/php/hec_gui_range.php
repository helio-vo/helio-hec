<?php
  # =============================================
  # HELIO 2009,2011 - by A.Santin
  # INAF - Trieste Astronomical Observatory
  # ---------------------------------------------
  # HEC - UI main page
  # hec_gui.php
  # last 11-feb-2011, 18-feb-2011
  # =============================================
  require ("hec_global.php");
 
    $dbconn = pg_connect("dbname=hec");
    if (!$dbconn) {
      echo "Error: unable to connect database";
    }
    
  	$f2 = fopen("$hecdir/temp/catalogues.postgres.converted",'w');

    // read database for table list
    $sql_string="SELECT cat_id,name,description,type,status,url,hec_groups_id,bg_color,longdescription,timefrom,timeto,
       flare,cme,swind,part,otyp,solar,ips,geo,planet
     FROM sec_catalogue ORDER BY hec_groups_id;";// WHERE hec_groups_id = $g ORDER BY cat_id;";
    $result = pg_exec($dbconn,$sql_string);
    if (!$result) {
      echo "error\n";
    }

    $now = date('Ymd');
    while ($r = pg_fetch_array($result)) {
        $timeto = $r['timeto'];
        //search for last data in the catalogue (and filter any future date)
        if (strpos(' '.$timeto,'2999')==1) {
          $sql_string2="SELECT max(time_start) as timemax FROM ".$r['name']." WHERE time_start<='$now'";//select max(end) where end<=now
          $result2 = pg_exec($dbconn,$sql_string2);
          if ($result2) {
            $r2 = pg_fetch_array($result2);
            $timeto = substr($r2['timemax'],0,10);
          } else {
            $timeto = '&nbsp;';//if any error leave empty
          }//if
        }//if 2999
        echo('<td>'.$timeto.'</td>'."\n");
        if ($r['status']!='deleted') fwrite($f2,$r['name']."\t".$r['description']."\t".$r['type']."\t".$r['status']."\t".$r['timefrom']."\t".$timeto."\n");
 
    }//while
  
  	fclose($f2);


?>
