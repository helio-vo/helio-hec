<?php
	# =============================================
	# HELIO 2010 HEC server - by Andrej Santin
	# ---------------------------------------------
	# read apstar
	# web site:
	# hec_load_apstar.php
	# 1st 26-may-2010, last 26-may-2010
	# =============================================

	//require ("sec_global.php");
	$tempdir = "/var/www/html/radiosun/hec/temp";
$tempdir = "run_hec";

	// get files from HTTP
	//exec ("wget http://www.");
	//copy ("current.txt",$tempdir."/current.txt");
	//unlink ("current.txt");

	// parse files and create postgres-ready file	
	$f1 = fopen("$tempdir/APSTAR.postgres.converted",'w');
	$f2 = fopen("$tempdir/apstar_csv.txt",'r');

  $buffer = fgets($f2);//skip first line
  print $buffer;

	while (!feof ($f2)) {
    $buffer = fgets($f2);
    $items = explode(";", $buffer);
    print_r($items);
    if ($buffer !="") fwrite($f1,$items[0]."\t".$items[1]."\t".$items[2]."\t".$items[3]."\t".$items[4]."\t".$items[5]."\t".$items[6]);//."\n"
	}//while
	fclose($f2);
	fclose($f1);
?>
