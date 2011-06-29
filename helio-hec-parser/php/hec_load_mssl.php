<?php
        # =============================================
        # HELIO 2009 HEC server - by Andrej Santin
        # INAF - Trieste Astronomical Observatory
        # ---------------------------------------------
        # hec_load_mssl.php
        # 1st 03-nov-10, last 06-nov-10
        # =============================================
       // require ("hec_global.php");
        $tempdir = "/var/www/hec/temp";


        // get files from HTTP
        exec ("wget -N --directory-prefix=$tempdir http://www.mssl.ucl.ac.uk/~rdb/egso-sec/latest_gev.txt");
        exec ("cat $tempdir/latest_gev.txt.last $tempdir/latest_gev.txt | sort | uniq > $tempdir/latest_gev.txt.last");
//        exec ("/bin/rm $tempdir/latest_gev.txt");

        exec ("wget -N --directory-prefix=$tempdir http://www.mssl.ucl.ac.uk/~rdb/soars/gevloc_flares.txt");
        exec ("cat $tempdir/gevloc_flares.txt.last $tempdir/gevloc_flares.txt | sort | uniq > $tempdir/gevloc_flares.txt.last");


        // parse files and create postgres-ready file
        $f1 = fopen("$tempdir/LATEST_GEV_FLARE.postgres.converted",'w');
        $f2 = fopen("$tempdir/latest_gev.txt.last",'r');
//2010/09/24 01:20:00; 2010/09/24 01:25:00; 2010/09/24 01:31:00; 11109; ; ; B2.9; ;
        while (!feof ($f2)) {
          $buffer = fgets($f2);
          $items = explode(";", $buffer);
          //print_r($items);
          for ($i=0; $i<count($items); $i++) {
            $items[$i] = trim($items[$i]);
            if ($items[$i]=="") $items[$i] = "\N";
          }//for
          if (count($items)==9) fwrite($f1,$items[0]."\t".$items[1]."\t".$items[2]."\t".$items[3]."\t".$items[4]."\t".$items[5]."\t".$items[6]."\t".$items[7]."\n");
          //echo(count($items)."\n");
        }//while
        fclose($f2);
        fclose($f1);

        $f1 = fopen("$tempdir/GEVLOC_FLARES.postgres.converted",'w');
        $f2 = fopen("$tempdir/gevloc_flares.txt.last",'r');
//2010/10/28 10:12:00  B2.0  N18W34
        while (!feof ($f2)) {
          $buffer = fgets($f2);
          if (substr($buffer,0,1)!=";") {
            $items = explode("  ", $buffer);
            //print_r($items);
            for ($i=0; $i<count($items); $i++) {
              $items[$i] = trim($items[$i]);
              if ($items[$i]=="") $items[$i] = "\N";
            }//for
            if (count($items)==3) fwrite($f1,$items[0]."\t".$items[1]."\t".$items[2]."\n");
          }//if
        }//while
        fclose($f2);
        fclose($f1);

?>
