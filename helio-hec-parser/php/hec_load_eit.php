<?php
# =============================================
# HELIO 2010 HEC server - by Andrej Santin
# INAF - Trieste Astronomical Observatory
# hec_load_eit.php
# last 28-jun-2011
# ---------------------------------------------
# read EIT catalog CVS, dateformat dd-MMM-yy, timeformat 23:59:59
# =============================================
        
//require ("hec_global.php");
$tempdir = "/var/www/hec/temp";
$months = array("JAN"=>1,"FEB"=>2,"MAR"=>3,"APR"=>4,"MAY"=>5,"JUN"=>6,"JUL"=>7,"AUG"=>8,"SEP"=>9,"OCT"=>10,"NOV"=>11,"DEC"=>12);
        

// parse files and create postgres-ready file	
$f1 = fopen("$tempdir/EIT.postgres.converted",'w');
$f2 = fopen("$tempdir/wavescatalog_print.csv",'r');
        
//skip first three lines    
//N,Date,Quality Rating, Source Loc.N/S,Source Loc.E/W,Previous Image Time,Image Time,Speed Plane-of-Sky,Speed Projected,Direction of Meas.
$buffer = fgets($f2);
$buffer = fgets($f2);
$buffer = fgets($f2);

$lastdate="";
$ll=0;
while (!feof ($f2)) {
    $buffer = fgets($f2);
    if ((substr($buffer,0,9)!=",,,,,,,,,") and ($buffer!="")) {
        $buffer_array = explode(",",trim($buffer));
        $buf=array();
        $buf[0]="";
        $buf[1]="";
        if ($buffer_array[1]=="") {
            $date=$lastdate;
            $note="";
        } else {
            if (is_numeric(substr($buffer_array[1],0,1))) {
                $y="19".substr($buffer_array[1],7,2);
                $m=$months[strtoupper(substr($buffer_array[1],3,3))];
                $date=sprintf("%04d/%02d/%02d",$y,$m,substr($buffer_array[1],0,2));
                $lastdate=$date;
                $note="";
            } else {
                $date=$lastdate;
                $note=$buffer_array[1];//"note"
            }
        }
        if ($buffer_array[5]!="") $buf[0]=$date." ".$buffer_array[5];//previmg_time
        if ($buffer_array[6]!="") $buf[1]=$date." ".$buffer_array[6];//img_time
        $buf[2]=$buffer_array[2];//quality
        $buf[3]=$buffer_array[3];//latitude
        $buf[4]=trim($buffer_array[4]);//longitude
        if (is_numeric($buffer_array[7])) 
          $buf[5]=$buffer_array[7];//speed_planeofsky 
        else $buf[5]="";//filter "see note"
        if (is_numeric($buffer_array[8]))
          $buf[6]=$buffer_array[8];//speed_proj
        else $buf[6]="";//filter "off limb"
//        $buf[7]=$buffer_array[9];//measure_dir
        switch($buffer_array[9]) {
            case 'N':
                $pa = 0;
                break;
            case 'NE':
                $pa = 45;
                break;
            case 'E':
                $pa = 90;
                break;
            case 'SE':
                $pa = 135;
                break;
            case 'S':
                $pa = 180;
                break;
            case 'SW':
                $pa = 225;
                break;
            case 'W':
                $pa = 270;
                break;
            case 'NW':
                $pa = 315;
                break;
            default:
                $pa = '\N';
        }#switch
        $buf[7]=$pa;//measure_dir
        $buf[8]=$note;//description
        $buf[9]=$buf[1];//time_start as img_time
        
//out
//previmg_time, img_time, quality, latitude, longitude, speed_planeofsky, speed_proj, measure_dir, description, time_start
        if ($buf[0]=="") $out="\N"; else $out=$buf[0];
        for ($k=1;$k<=9;$k++) {
          if ($buf[$k]=="") $buf[$k]="\N";
          $out.="\t".$buf[$k];
        }
        $out.="\n";
        fwrite($f1,$out);
    }//if not ",,,,,,,,," and not ""
}//while

?>
