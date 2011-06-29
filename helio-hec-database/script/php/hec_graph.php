<?php
# =============================================
# HELIO 2010 HEC server - by Andrej Santin
# INAF - Trieste Astronomical Observatory
# hec_graph.php
# last 28-jun-2011
# ---------------------------------------------
# generate SEC list entry population as PNG image
# =============================================
define("DEBUG","0");
require ("hec_global.php");

echo "hec_graph running...\n";

$dd = array (0,31,28,31,30,31,30,31,31,30,31,30,31);
$curyear=date("Y");

$dbconn = pg_connect("dbname=hec");
if (!$dbconn) {
    echo "Error: unable to connect database<br>\n";
}
// read database for table list
$sql_string="select * from sec_catalogue order by hec_groups_id,cat_id;";
$result = pg_exec($dbconn,$sql_string);
if ($result) {
    $maxcats=0;
    while ($r = pg_fetch_row($result)) {
        $cats[$maxcats] = $r[1];
        $maxcats++;
    }
} else {
    echo "result error<br>\n\n";
}

$hs=135;//hor.shift
$vs=105;//vert.shift
$va=35;//vert.step
//specify diagram parameters(these are global)
$diagramWidth=($curyear-1975+1)*24+$hs;
$diagramHeight=($maxcats-1)*$va+$vs;
//create image
$image=imageCreate($diagramWidth,$diagramHeight);
//allocate all required colors
$white=imageColorAllocate($image,255,255,255);
$colorBackgr=imageColorAllocate($image,192,192,192);
$black=imageColorAllocate($image,0,0,0);
$red=imageColorAllocate($image,255,0,0);
$green=imageColorAllocate($image,0,200,370);

//clear the image with the background color
imageFilledRectangle($image,0,0,$diagramWidth-1,$diagramHeight-1,$colorBackgr);
//draw rectangle a round diagram (mark sits boundaries)
imageRectangle($image,0,0,$diagramWidth-1,$diagramHeight-1,$black);
//print descriptive text into the diagram
$today=date("Y-m-d");
imageString($image,3,$diagramWidth/3,5,"HEC list entry population",$black);
imageString($image,3,$diagramWidth-200,5,"last update $today",$black);
imageLine($image,0,$vs-$va*2,$diagramWidth-1,$vs-$va*2,$black);

$j=-1;
imageLine($image,0,$j*$va+$vs,$diagramWidth-1,$j*$va+$vs,$black);
$j=0;
for ($j=0; $j<$maxcats; $j++) {
  imageLine($image,0,$j*$va+$vs,$diagramWidth-1,$j*$va+$vs,$black);
}

$max=0; $max2=0;

$i=$hs;
imageLine($image,$i,$vs-$va*2,$i,$diagramHeight-1,$black);
//$curyear=1979;
for ($y=1975;$y<=$curyear;$y++) {
  imageStringup($image,3,$i+2,$vs-40,"$y",$black);
  for ($mo=1;$mo<=12;$mo++) {
    echo "$y-$mo\n";
    $j=0;
    foreach ($cats as $cat) {
      if (DEBUG==0) {
        $sql_string2="select count(*) from $cat where time_start>='$y-$mo-1' AND time_start<='$y-$mo-$dd[$mo]';";
        $result2 = pg_exec($dbconn,$sql_string2);
        if (!$result2) {
          echo "result2 error\n";
        }
        $r2 = pg_fetch_row($result2);
        $count = $r2[0];
      } else {
        $count = rand(0,939);
      }
//        echo "count=$count\n";
        if ($count>$max) $max=$count;
        //$count=$count/1000*50;//lin
        $count=log10($count+1.0)*($va/log10(2000));
        if ($count>$max2) $max2=$count;
        if ($count>0) imageLine($image,$i,$j*$va+$vs-$count,$i,$j*$va+$vs,$green);
        $j++;
     }//while
     $i++;$i++;
  }//for $mo
  imageLine($image,$i,$vs-$va*2,$i,$diagramHeight-1,$black);
}//for $y

$j=0;
foreach ($cats as $cat) {   
  imageFilledRectangle($image,1,$j*$va+$vs-($va-1),$hs-1,$j*$va+$vs-($va-11),$colorBackgr);
  imageString($image,3,2,$j*$va+$vs-$va,"$cats[$j]",$black);
  $j++;
}

pg_close($dbconn);
echo "max records/month= $max max2 pixels= $max2<br>\n";



//create an interlaced image for better loadingin the browser
imageInterlace($image,1);

//mark background coloras being transparent
imageColorTransparent($image,$colorBackgr);

imagePNG($image,"$hecdir/hec.png");
//exec ("chmod 777 $hecdir/temp.png");
echo "<br><IMG SRC=\"hec.png\">\n";

?>
