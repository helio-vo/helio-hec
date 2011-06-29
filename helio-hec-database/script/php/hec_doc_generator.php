<?php
	# =============================================
	# EGSO 2003 SEC server - by Max Jurcev
	# INAF - Trieste Astronomical Observatory
	# first 13/feb/2004
    # last  12/may/2005 A.Santin 
	# ---------------------------------------------
	# hec_doc_generator.php
	# =============================================
		
	require ("hec_global.php");
	
	$type  = array ("i"=>"integer","f"=>"float","t"=>"datetime","b"=>"boolean","s"=>"string");
	
	$dbconn = pg_connect("dbname=hec");
	if (!$dbconn) {
		echo "Error: unable to connect database";
		die;
	}
	$f1 = fopen("$hecdir/sec_doc.htm",'w');
	$s = sec_header("HEC description");
	$s .= "Description of the HEC database architecture.<br>";
	$s .= "Cross queries among different catalogues are possible by using common fields.";
    
	// ---------------------------------------
	$s .= "<h2>Tables</h2>";
	$s .= "<table border=1>\n";
	$s .= "<tr class=\"head\"><th>Table name</th><th>List Name</th><th>Description/URL</th></tr>";

  //read groups
  $sql_string="SELECT hec_groups_id,hec_group_name FROM hec_groups;";
  $result = pg_exec($dbconn,$sql_string);
  if ($result) {
    while ($r = pg_fetch_array($result)) {
      $groups[$r['hec_groups_id']]=$r['hec_group_name'];
    }
  }
               
	// read database for table list
  for ($g=1; $g<=5; $g++) {
    $s .= "<tr> <td colspan=3><i>".$groups[$g]."</i></td><tr>";
    $sql_string="SELECT name,description,url,bg_color,longdescription FROM sec_catalogue WHERE hec_groups_id=$g;";
    $result = pg_exec($dbconn,$sql_string);
    if ($result) {
      while ($r = pg_fetch_array($result)) {
        $s .= "<tr bgcolor=".$r['bg_color']."><td>".$r['name']."</td><td>".$r['description']."</td><td>".$r['longdescription']." [<a href='".$r['url']."' target='_new'>URL</a>]</td></tr>";
      }
 	  }
  }//for $g
/*
	$sql_string="select * from sec_catalogue;";
	$result = pg_exec($dbconn,$sql_string);
	if ($result) {
		while ($r = pg_fetch_row($result)) {
print_r($r);
			$s .= "<tr><td>".$r[1]."</td><td>".$r[2]."</td><td>".$r[5]."</td></tr>";
		}
	}
*/
	$s .="</table>\n";
    
	// ---------------------------------------
	$s .= "<hr><h2>Attributes</h2>";
  $i=21;
  for ($j=1; $j<=5; $j++) { 
    $s .= "<table border=1>\n";
    // read database for table list
    $sql_string="select * from sec_catalogue WHERE hec_groups_id=$j;";
    $result = pg_exec($dbconn,$sql_string);
      //if ($j==1)
      //  for ($ii=$i; $ii>0; $ii--)
      //     $r = pg_fetch_row($result);
    $cat = array();
    if ($result) {
      $s .= "<tr class=\"head\">";
          $ii=$i;
      while ($r = pg_fetch_row($result) AND $ii-->0) {
              //$rr=str_replace("_","_<BR>",$r[1]);
              $rr=$r[1];
        $s .= "<th>".$rr."</th>";	
        $temparr = array($r[1]=>$r[0]);
        $cat = array_merge($cat,$temparr);
      }
      $s .= "</tr>";
    }
    //.............................
    $result = pg_exec($dbconn,$sql_string);
      //if ($j==1)
      //  for ($ii=$i; $ii>0; $ii--)
      //     $r = pg_fetch_row($result);
    if ($result) {
      $s .= "<tr>";
          $ii=$i;
      while ($r = pg_fetch_row($result) AND $ii-->0) {
        $s .= "<td valign=\"top\"><i>".$r[2]."</i></td>";	
      }
      $s .= "</tr>";
    }
    //.............................
    $s .= "<tr>";
    foreach ($cat as $c=>$k) {
      $s .= "<td valign=\"top\">";		
  //		$sql_string="select * from sec_attribute order by name;";
      $sql_string="select * from sec_attribute;";
      $result = pg_exec($dbconn,$sql_string);
      if ($result) {
        while ($r = pg_fetch_row($result)) {
          $sql_string2="select * from sec_cat_attr where cat_id=$k AND attr_id=".$r[0].";";
          $result2 = pg_exec($dbconn,$sql_string2);
          if (pg_num_rows($result2)>0) 
            $s .= $r[1]."<br>";
        }
      }
      $s .= "</td>";
    }
    $s .= "</tr>";
    $s .= "</table><BR>\n";
  }//for $j
    
	// ---------------------------------------
	$s .= "<hr><h2>Attribute details</h2>";
	$s .= "<table border=1>\n";
	// read database for table list
	$s .= "<tr class=\"head\">";
	$s .= "<th>Field name</th>";	
	$s .= "<th>Type</th>";
	$s .= "<th>Description</th>";
	$sql_string="select * from sec_catalogue;";
	$result = pg_exec($dbconn,$sql_string);
	$cat = array();
	if ($result) {
		while ($r = pg_fetch_row($result)) {
            $rr=str_replace("_","_<BR>",$r[1]);
			$s .= "<th>".$rr."</th>";	
			$temparr = array($r[1]=>$r[0]);
			$cat = array_merge($cat,$temparr);
		}
	}
    print_r($cat);
	$s .= "</tr>";
	$sql_string="select * from sec_attribute order by name;";
	$result = pg_exec($dbconn,$sql_string);
	if ($result) {
		while ($r = pg_fetch_row($result)) {
			$s .= "<tr><td>".$r[1]."</td>";
			$s .= "<td>".$type[$r[3]]."</td>";
			$s .= "<td>".$r[2]."</td>";
			foreach ($cat as $c=>$k) {
				$s .= "<td align=\"center\">";
				$sql_string2="select * from sec_cat_attr where cat_id=$k AND attr_id=".$r[0].";";
				$result2 = pg_exec($dbconn,$sql_string2);
				$ch = (pg_num_rows($result2)>0) ? "X" : "&nbsp;";
				$s .= $ch;
				$s .= "</td>";
			}
			$s .= "</tr>";
		}
	}
    		
	$s .= "</table>\n";
	// ---------------------------------------
	pg_close($dbconn);
	$s .= sec_footer();
	fwrite($f1,$s);
	fclose($f1);	
?>
