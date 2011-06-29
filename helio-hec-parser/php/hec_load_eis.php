<?php
	# =============================================
	# HELIO 2010 HEC server - by Andrej Santin
	# ---------------------------------------------
	# read EIS
	# web site: 
	# hec_load_eis.php
	# 1st 17-sep-2010, last 17-sep-2010
	# =============================================

	//require ("hec_global.php");
        $tempdir = "/var/www/hec";

	// parse files and create postgres-ready file	
	$f1 = fopen("$tempdir/EIS.postgres.converted",'w');
	$f2 = fopen("$tempdir/eis_list_2006_fin.txt",'r');

  $buffer = fgets($f2);//skip first line

  while (!feof ($f2)) {
    $buffer = fgets($f2);
    $items = explode(";", $buffer);
    for ($i=0; $i<4;$i++) $items[$i]=trim($items[$i]);
//    print_r($items);
//    print ">".$items[1]."<";
    //$rem = substr($items[3],1,100);
    //if (strpos($rem," ")==0) $rem='\N';
    //$rem = str_replace("\n","",$rem);
    if ($buffer !="") fwrite($f1,$items[1]."\t".$items[2]."\t".$items[0]."\t".$items[3]."\n");//
  }//while
  fclose($f2);
  fclose($f1);
?>
