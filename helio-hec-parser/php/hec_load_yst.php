<?php
# =============================================
# HELIO 2010 HEC server - by Andrej Santin
# INAF - Trieste Astronomical Observatory
# hec_load_yst.php
# last 28-jun-2011
# ---------------------------------------------
# read Nitta Yohkoh, SXT, TRACE catalog
# http://www.lmsal.com/nitta/sxt_trace_flares/list.html
# =============================================
        
//require ("hec_global.php");
$tempdir = "/var/www/hec/temp";
$months = array("JAN"=>1,"FEB"=>2,"MAR"=>3,"APR"=>4,"MAY"=>5,"JUN"=>6,"JUL"=>7,"AUG"=>8,"SEP"=>9,"OCT"=>10,"NOV"=>11,"DEC"=>12);
        

// parse files and create postgres-ready file	
$f1 = fopen("$tempdir/YST.postgres.converted",'w');
$f2 = fopen("$tempdir/nitta_list.txt",'r');
        
//skip first three lines    
$buffer = fgets($f2);
$buffer = fgets($f2);
$buffer = fgets($f2);

//in
//link,date,t_st_sx,t_en_sx,GOES,N_img,Xc,Yc,t_st,t_en,t_cl,WLdom,Xc,Yc,n171,n195,n284,n1600,n1216 
while (!feof ($f2)) {
    $buffer = fgets($f2);
   if ($buffer!="") { 
    $buffer_array = explode(";",trim($buffer));
    if (strlen($buffer_array[1])<9) $buffer_array[1]=" ".$buffer_array[1];
    $y="19".substr($buffer_array[1],7,2);
    $m=$months[strtoupper(substr($buffer_array[1],3,3))];
    $date=sprintf("%04d/%02d/%02d",$y,$m,substr($buffer_array[1],0,2));
    if ($buffer_array[4]=="NoGOES") $buffer_array[4]=""; //GOES
    $buffer_array[11]=substr($buffer_array[11],0,strpos($buffer_array[11],"A")-1);//WLdom, delete " A"
    if ($buffer_array[11]=="Wht-Lh") $buffer_array[11]=5500;//"Wht-Lht"
    $buf=array();
    $ii=0;
//    $buf[$ii++]=$buffer_array[0];//link
    $st=strpos($buffer_array[0],"www");
    $en=strpos($buffer_array[0],".gif");
    $buf[$ii++]=substr($buffer_array[0],$st,$en-$st+4);//link
    $buf[$ii++]= $date." ".$buffer_array[2];//t_st_sx
    $buf[$ii++]= $date." ".$buffer_array[3];//t_en_sx
    $buf[$ii++]= $buffer_array[4];//GOES
    $buf[$ii++]= $buffer_array[5];//N_img
    $buf[$ii++]= $buffer_array[6];//Xc
    $buf[$ii++]= $buffer_array[7];//Yc
    $buf[$ii++]= $date." ".$buffer_array[8];//t_st
    $buf[$ii++]= $date." ".$buffer_array[9];//t_en
    $buf[$ii++]= $date." ".$buffer_array[10];//t_cl
    $buf[$ii++]= $buffer_array[11];//WLdom
    $buf[$ii++]= $buffer_array[12];//Xc
    $buf[$ii++]= $buffer_array[13];//Yc
    $buf[$ii++]= $buffer_array[14];//n171
    $buf[$ii++]= $buffer_array[15];//n195
    $buf[$ii++]= $buffer_array[16];//n284
    $buf[$ii++]= $buffer_array[17];//n1600
    $buf[$ii++]= $buffer_array[18];//n1216
    $buf[$ii++]= $buffer_array[19];//nWL

//out
//link,sxt_time_start,sxt_time_end,xray_class,n_img,x_arcsec_sxt,y_arcsec_sxt,time_start,time_end,time_sxt_trace,wl_dom,x_arcsec,y_arcsec,n171,n195,n284,n1600,n1216,nwl
    if ($buf[0]=="") $out="\N"; else $out=$buf[0];
    for ($k=1;$k<=18;$k++) {
      if ($buf[$k]=="") $buf[$k]="\N";
      $out.="\t".$buf[$k];
    }
    $out.="\n";
    fwrite($f1,$out);
   }
}//while

?>
