<?php
  # =============================================
  # HELIO 2009,2011 - by A.Santin
  # INAF - Trieste Astronomical Observatory
  # ---------------------------------------------
  # HEC - UI main page
  # hec_gui.php
  # last 11-feb-2011, 18-feb-2011
  # header field changed by Alex from 'Catalogue' to 'Catalogue Description', 02-Apr-2011  
  # added cell padding and augmented spacing
  # =============================================
  require ("hec_global.php");
 
  echo('<center>');

  echo (hec_header("HELIO HEC - GUI"));

  echo '<script type="text/javascript" src="hec_gui.js"></script>';
  echo '<script type="text/javascript" src="scrollable.js"></script>';
  echo('<form name="catform" action="hec_gui_fetch.php" target="_blank" method="get" onsubmit="return myOnsubmit()">'."\n");

  echo '<tr valign="middle"><td colspan=3 align="center" cellpadding="0"><b>Recent Changes</b>&nbsp;<a href="http://helio-cdaws.blogspot.com/search/label/Heliophysics Event Catalogue" target="hio_blog"><img src="blogspot-favicon_sm.jpg" title="CDAW Blog topic on the HEC" border=0></a>
</td></tr>';
  echo '<table cellpadding="2%">';
  echo '<tr><td><b id="timetitleid">Search time interval</b>';
//  echo '<tr><img src="info_icon.gif" onmouseover="InfoMouseOver(\'timetitleid\',\'Specify event time span for searching catalogues\')" onmouseout="InfoMouseOut(\'timetitleid\',\'Search time interval\')" </tr>';
  echo '&nbsp;<img src="info_icon.gif" title="Specify event time span for searching catalogues" border=0 style="font-style:italic;">';
  echo '</td>';

  echo '<td valign="middle" colspan="2">';
  sec_date("from",TRUE);
  echo '&nbsp;&nbsp;&nbsp;To&nbsp;';
  sec_date("to",FALSE);
  echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="hec.png" border=0 target="_blank"><img src="hec.png" align="middle" title="Catalogue time coverage" width=60 height=60></a>';
  echo '</td></tr>';
  //echo '<tr><td colspan=3>&nbsp;</td></tr>';

//  echo '<P>';
  echo '<tr><td><b id="eventtitleid">Event characterisation</b>';
  //echo '<td><img src="info_icon.gif" onmouseover="InfoMouseOver(\'eventtitleid\',\'Specify event characteristics to restrict search\')" onmouseout="InfoMouseOut(\'eventtitleid\',\'Event characterisation\')"></td>';
  echo '&nbsp;<img src="info_icon.gif" title="Specify event characteristics to restrict search" border=0 style="font-style:italic;">&nbsp;&nbsp;';
  echo '</td>';
    echo '<td><i>Event type:</i></td>';
    echo '<td><input id="cmechk" type="checkbox" class="button" onclick="return showlist()">CME &nbsp;';
    echo '<input id="flarechk" type="checkbox" class="button" onclick="return showlist()">Flare &nbsp;';
    echo '<input id="swindchk" type="checkbox" class="button" onclick="return showlist()">Solar Wind &nbsp;';
    echo '<input id="partchk" type="checkbox" class="button" onclick="return showlist()">Particle &nbsp;';
    echo '</td></tr>';

    echo '<tr><td></td><td><i>Location:</i></td>';
    echo '<td><input id="solarchk" type="checkbox" class="button" onclick="return showlist()">Solar &nbsp;';
    echo '<input id="ipschk" type="checkbox" class="button" onclick="return showlist()">IPS &nbsp;';
    echo '<input id="geochk" type="checkbox" class="button" onclick="return showlist()">Geo &nbsp;';
    echo '<input id="planetchk" type="checkbox" class="button" onclick="return showlist()">Planet &nbsp;';
    echo '</td></tr>'."\n";

    echo '<tr><td></td><td><i>Obs. type:</i></td>';
//    echo '<input id="insituchk" type="checkbox" class="button" onclick="return showlist()">in&nbsp;situ &nbsp;';
//    echo '<input id="remotechk" type="checkbox" class="button" onclick="return showlist()">remote &nbsp;';
    echo '<td><input id="insituchk" type="radio" name="radioremote" onclick="return showlist()">In&nbsp;situ &nbsp;';
    echo '<input id="remotechk" type="radio" name="radioremote" onclick="return showlist()">Remote &nbsp;';
    echo '<input id="allremotechk" type="radio" name="radioremote" onclick="return showlist()">All &nbsp;';
    echo '</td></tr>'."\n";

    echo '<tr><td></td>';
    echo '<td><input id="clearchk" type="button" class="button" style="background-color:#FFE065; color:black;" value="Reset" onclick="return clearall()"></td>';
    echo '<td><input id="showallchk" type="checkbox" class="button" onclick="return showall()">Show all catalogues &nbsp;&nbsp;&nbsp;';
//    echo '<input id="showmorechk" type="checkbox" class="button" onclick="return showmore()">Show more catalogues &nbsp;';
    echo '<input id="showmorechk" type="hidden">';    
    echo '<i>Catalogue title search</i>:<INPUT id="idtitlesearch" TYPE="TEXT" NAME="titlesearch2" size="8" onKeyUp="return titlesearch()">';
    echo '</td></tr></table>';
    echo '<hr>'."\n";
/*
    echo '<table>';
    echo '<tr><b>Metadata description search</b></tr><br>';
    echo '<tr><td>';
//    echo 'Catalogue title search:<INPUT id="idtitlesearch" TYPE="TEXT" NAME="titlesearch2" onKeyUp="return titlesearch()">';
    echo '</td>'."\n";
    echo '<td>Google search (+enter):&nbsp;<INPUT id="idgooglesearch" type="text" value="" name="q" onKeyDown="return googlesearch(event);">';
    echo '</tr></table>';
    echo '<P><hr><BR>';
*/
    $dbconn = pg_connect("dbname=hec");
    if (!$dbconn) {
      echo "Error: unable to connect database";
    }
/*
  //read groups
  $sql_string="SELECT hec_groups_id,hec_group_name FROM hec_groups;";
  $result = pg_exec($dbconn,$sql_string);
  if ($result) {
    while ($r = pg_fetch_array($result)) {
      $groups[$r['hec_groups_id']]=$r['hec_group_name'];
    }
  }
*/
    // read database for table list
    $sql_string="SELECT cat_id,name,description,type,status,url,hec_groups_id,bg_color,longdescription,timefrom,timeto,
       flare,cme,swind,part,otyp,solar,ips,geo,planet
     FROM sec_catalogue ORDER BY sort;";// WHERE hec_groups_id = $g ORDER BY cat_id;";
    $result = pg_exec($dbconn,$sql_string);
    if (!$result) {
      echo "error\n";
    }

    //init the javascript arrays for categories
    $catarr_st = '  var catarr=[';
    $solararr_st = '  var solararr=[';
    $flarearr_st = '  var flarearr=[';
    $cmearr_st = '  var cmearr=[';
    $swindarr_st = '  var swindarr=[';
    $partarr_st = '  var partarr=[';
    $remotearr_st = '  var remotearr=[';
    $insituarr_st = '  var insituarr=[';
    $ipsarr_st = '  var ipsarr=[';
    $geoarr_st = '  var geoarr=[';
    $planetarr_st = '  var planetarr=[\'0\',';
    $deletedarr_st = '  var deletedarr=[';
    $descarr_st = '  var descarr = new Array(); ';
    
    echo '<tr><td><b>Catalogues matching selection</b></tr>';
    echo '&nbsp;<img src="info_icon.gif" title="Select catalogues to get events from and fetch data" border=0 style="font-style:italic;">';
    echo '</td>';
    echo '&nbsp;&nbsp;&nbsp;<tr><td colspan="8" align="center"><INPUT TYPE="button" style="background-color:lightgreen; color:black;" value="Submit search" onclick="document.forms[\'catform\'].submit();"></td></tr>';
    echo '<br><br>';

    //--------------------------------------------------------------------------
    echo '<div id="tableContainer" class="tableContainer">';
    echo '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="scrollTable">';
    echo '<thead class="fixedHeader">';
    echo ('<tr><th class="Select">Select</th><th class="Catalogue">Catalogue Description</th><th class="Type">Type</th><th class="Status">Status</th><th class="Source">Source</th><th class="From">From</th><th class="To">To</th><th class="Info">Info</th></tr>'."\n");
    echo '</thead>';
    echo '<tbody class="scrollContent">'; 
    //--------------------------------------------------------------------------

//    echo('<table border=1>');
//    echo('<tr><th>Select</th> <th>Catalogue Description</th> <th>Type</th> <th>Status</th> <th>Source</th> <th>From</th> <th>To</th> <th>Info</th></tr>'."\n");
//    echo('<form ACTION="hec_gui_fetch.php" target="_blank" METHOD=get onSubmit="return copyDate()">'."\n");
//    $groupscolor = array('ccdddd','ccbbbb','ccaaaa','cccc99','ivory');
    $groupscolor = array('D3D1D1','D3D1D1','D3D1D1','D3D1D1','D3D1D1');
    $now = date('Ymd');

    //--------------------------------------------------------------------------

    while ($r = pg_fetch_array($result)) {
        echo('<tr id="cat'.$r['cat_id'].'" style="display:none" bgcolor="'.$groupscolor[$r['hec_groups_id']].'">');
        echo('<td class="Select"><input id="chkcat'.$r['cat_id'].'" name="'.$r['name'].'" value="istable" type="checkbox"></td>');
        echo('<td class="Catalogue">'.$r['description'].'</td>');
        echo('<td class="Type">'.$r['type'].'</td>');
        echo('<td class="Status">'.$r['status'].'</td>');
        echo('<td class="Source"><a href="'.$r['url'].'" target="_blank">URL</a></td>');
        echo('<td class="From">'.$r['timefrom'].'</td>');
        $timeto = $r['timeto'];
        //if (strpos(' '.$timeto,'2999')==1) $timeto = '&nbsp;';
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

        echo('<td class="To">'.$timeto.'</td>');
        //echo('<td><input  type="button" class="button" value="Info" onclick="return desc(\''.$r['longdescription'].'\')"></td>');
//        echo('<td bgcolor="white"><a href="http://140.105.77.30/hec/stfc/HEC_ListsAll.html#'.$r['name'].'" target="_blank" >Info</a></td>');
        echo("<td bgcolor=\"white\"><INPUT type=\"button\" value=\"Info\" onClick=\"{mywindow = window.open('stfc/HEC_ListsAll.html#".$r['name']."','mywindow','width=800,height=400,scrollbars=1');mywindow.moveTo(100, 100);}\"></td>");

        echo('</tr>'."\n");
        //populate the javascript arrays
        if ($r['status']!='deleted') $catarr_st .= "'".$r['cat_id']."',";//add index to catarr
        if (strtolower($r['solar'])=='y') $solararr_st .= "'".$r['cat_id']."',";//add index to solararr
        if (strtolower($r['flare'])=='y') $flarearr_st .= "'".$r['cat_id']."',";//add index to flarearr
        if (strtolower($r['cme'])=='y') $cmearr_st .= "'".$r['cat_id']."',";//add index to cmearr
        if (strtolower($r['swind'])=='y') $swindarr_st .= "'".$r['cat_id']."',";//add index to swindarr
        if (strtolower($r['part'])=='y') $partarr_st .= "'".$r['cat_id']."',";//add index to partarr
        if (strtolower($r['otyp'])=='r') $remotearr_st .= "'".$r['cat_id']."',";//add index to remotearr
        if (strtolower($r['otyp'])=='i') $insituarr_st .= "'".$r['cat_id']."',";//add index to insituarr
        if (strtolower($r['ips'])=='y') $ipsarr_st .= "'".$r['cat_id']."',";//add index to ipsarr
        if (strtolower($r['geo'])=='y') $geoarr_st .= "'".$r['cat_id']."',";//add index to geoarr
        if (strtolower($r['planet'])=='y') $planetarr_st .= "'".$r['cat_id']."',";//add index to planetarr
        if (strtolower($r['status'])=='deleted') $deletedarr_st .= "'".$r['cat_id']."',";//add index to deletedarr
        $descarr_st .= " descarr[".$r['cat_id']."]='".$r['description']."';\n";//add index to descarr

    }//while
echo '</tbody>';
echo '</table>';
echo '</div>';

//    echo '<br><tr><td colspan="8" align="center"><INPUT TYPE="button" style="background-color:lightgreen; color:black;" value="Submit search" onclick="document.forms[\'catform\'].submit();"></td></tr>';

    echo('</form>');
/*
    echo '&nbsp;Metadata search (+enter)';
    echo '&nbsp;<img src="info_icon.gif" title="Get metadata description from catalogue specifications" border=0 style="font-style:italic;">';
    echo ':&nbsp;<INPUT id="idmetadatasearch" TYPE="TEXT" NAME="metadatasearch2" size="8"  onKeyUp="return metadatasearch(event)">';
*/
    echo '<INPUT id="idmetadatasearch" TYPE="hidden" NAME="metadatasearch2">';

    $catarr_st = substr($catarr_st,0,-1)."];\n";//delete last comma and close string
    $solararr_st = substr($solararr_st,0,-1)."];\n";//delete last comma and close string
    $flarearr_st = substr($flarearr_st,0,-1)."];\n";//delete last comma and close string
    $cmearr_st = substr($cmearr_st,0,-1)."];\n";//delete last comma and close string
    $swindarr_st = substr($swindarr_st,0,-1)."];\n";//delete last comma and close string
    $partarr_st = substr($partarr_st,0,-1)."];\n";//delete last comma and close string
    $remotearr_st = substr($remotearr_st,0,-1)."];\n";//delete last comma and close string
    $insituarr_st = substr($insituarr_st,0,-1)."];\n";//delete last comma and close string
    $ipsarr_st = substr($ipsarr_st,0,-1)."];\n";//delete last comma and close string
    $geoarr_st = substr($geoarr_st,0,-1)."];\n";//delete last comma and close string
    $planetarr_st = substr($planetarr_st,0,-1)."];\n";//delete last comma and close string
    $deletedarr_st = substr($deletedarr_st,0,-1)."];\n";//delete last comma and close string
    echo('<script type="text/javascript">'."\n");
    echo($catarr_st."\n");
    echo($solararr_st."\n");
    echo($flarearr_st."\n");
    echo($cmearr_st."\n");
    echo($swindarr_st."\n");
    echo($partarr_st."\n");
    echo($remotearr_st."\n");
    echo($insituarr_st."\n");
    echo($ipsarr_st."\n");
    echo($geoarr_st."\n");
    echo($planetarr_st."\n");
    echo($deletedarr_st."\n");
    echo($descarr_st."\n");
    echo('</script>');

    echo('<hr>'."\n");
    echo('<a href="http://140.105.77.30/hec/hec_gui_free.php" target="_blank">Free SQL search</a>'."\n");
/*
 passthru('wget "http://festung1.oats.inaf.it:8081/stilts/task/sqlclient?db=jdbc:postgresql://140.105.77.30/hec&user=apache&sql=select%20*%20from%20goes_xray_flare%20limit%2010&ofmt=html-element" -O -',$result);
echo $result;

//java -jar /var/www/stilts/stilts.jar tcopy ofmt=html-element /home/andrej/goes_xray_flare.xml output.txt
passthru('java -jar /var/www/stilts/stilts.jar tcopy ofmt=html-element /home/andrej/goes_xray_flare.xml',$result);
echo $result;

  echo '<br><a href="hec_rev.txt" target="_blank">HEC Revision history</a>';
  echo '<br><br>This page has been visited ';
  echo (sec_counter());
  echo ' times since Jan, 1st 2009';
*/
  echo (sec_footer());

/*
// open the file in a binary mode
$name = './img/ok.png';
$fp = fopen($name, 'rb');

// send the right headers
header("Content-Type: image/png");
header("Content-Length: " . filesize($name));

// dump the picture and stop the script
fpassthru($fp);

*/

?>
