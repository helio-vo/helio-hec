 <?php
	# =============================================
	# EGSO 2003,2004 - by Max Jurcev
	# INAF - Trieste Astronomical Observatory
	# =============================================
	# VOTable parser: produce an HTML table
	# last 12-feb-2004
	# sec_votabe.php
	# =============================================
	function votable_parse($result) {
		$result = str_replace("\n","",$result);
		$xml = domxml_open_mem ($result);
		if (!$xml) {
			return '';
		}
		$s = '';
		$s .= '<table  border=1 width="100%">';
		$s .= '<tr class="head">';
		// root: votable
		$n= $xml->document_element();
		// definitions
		$n = $n->first_child();
		// resource
		$n = $n->next_sibling();
		// description
		$n = $n->first_child();
		// table
		$n = $n->next_sibling();
		// fields
		$n = $n->first_child();
		while ($n->node_name() == 'FIELD') {
			$s .= '<td>';
			$s .= $n->get_attribute("name");
			$s .= '</td>';
			$n = $n->next_sibling();		
		}
		$s .= '</tr>';
		// data		
		$count = 0;
		// tabledata
		$n = $n->first_child();
		// tr
		$r = $n->first_child();
		while ($r) {
			if ($r->node_name() == 'TR') {
				$s .= '<tr>';	
				$count++;
				$f = $r->first_child();
				while ($f) {
					// td
					if ($f->node_name() == 'TD') {
						$v = $f->first_child();
						if ($v) {
//						echo '<td>',$v->get_content(),'</td><br>';
							$v2=$v->node_value();
							if ($v2=="t") $v2="TRUE";
							if ($v2=="f") $v2="FALSE";
                                                        if (strpos($v2,"jpg")>0) { $v2="<a href='$v2' target='_blank'>$v2</a>";} 
							$s .= "<td>$v2</td>";
						} else
							$s .= '<td>&nbsp;</td>';
					}
					$f = $f->next_sibling();	
				}
				$s .= '</tr>';	
			}
			$r = $r->next_sibling();
		}	
		$s .= '</table>';
		$s .= "returned rows = $count";
		return $s;
	}






	//---------------------------------------------
	function votable_parse_text($result) {
		$xml = domxml_open_mem ($result);
		if (!$xml) {
			return "XML parsing error";
		}
		$s = "";
		// root: votable
		$n= $xml->document_element();
		// definitions
		$n = $n->first_child();
		// resource
		$n = $n->next_sibling();
		// description
		$n = $n->first_child();
		// table
		$n = $n->next_sibling();
		// fields
		$n = $n->first_child();
		while ($n->node_name() == 'FIELD') {
			$s .= $n->get_attribute("name");
			$s .= "\t";
			$n = $n->next_sibling();		
		}
		$s .= "\r\n";
		// data		
		// tabledata
		$n = $n->first_child();
		// tr
		$r = $n->first_child();
		while ($r) {
			if ($r->node_name() == 'TR') {
				$f = $r->first_child();
				while ($f) {
					// td
					if ($f->node_name() == 'TD') {
						$v = $f->first_child();
						if ($v) {
							$v2=$v->node_value();
							if ($v2=="t") $v2="TRUE";
							if ($v2=="f") $v2="FALSE";
							$s .= $v2."\t";
						} else
							$s .= "\t";
					}
					$f = $f->next_sibling();	
				}
				$s .= "\r\n";	
			}
			$r = $r->next_sibling();
		}	
		return $s;
	}
	
  ?>
