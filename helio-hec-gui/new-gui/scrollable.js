
/* http://www.alistapart.com/articles/zebratables/ */
function removeClassName (elem, className) {
	elem.className = elem.className.replace(className, "").trim();
}

function addCSSClass (elem, className) {
	removeClassName (elem, className);
	elem.className = (elem.className + " " + className).trim();
}

String.prototype.trim = function() {
	return this.replace( /^\s+|\s+$/, "" );
}

function stripedTable() {
	if (document.getElementById && document.getElementsByTagName) {  
		var allTables = document.getElementsByTagName('table');
		if (!allTables) { return; }

		for (var i = 0; i < allTables.length; i++) {
			if (allTables[i].className.match(/[\w\s ]*scrollTable[\w\s ]*/)) {
				var trs = allTables[i].getElementsByTagName("tr");
/*				for (var j = 0; j < trs.length; j++) 
				 if (trs[j].style.display=="table-row") {
					removeClassName(trs[j], 'alternateRow');
					addCSSClass(trs[j], 'normalRow');
			  	}
				for (var k = 0; k < trs.length; k += 2)
				  if (trs[k].style.display=="table-row")  {
					removeClassName(trs[k], 'normalRow');
					addCSSClass(trs[k], 'alternateRow');
		
				}
*/ 
        var f=false;
        for (var j = 0; j < trs.length; j++) 
				 if (trs[j].style.display=="table-row") {
				  if (f) { 
					  removeClassName(trs[j], 'alternateRow');
					  addCSSClass(trs[j], 'normalRow');
					  f=false;
          } else {
	  				removeClassName(trs[j], 'normalRow');
  					addCSSClass(trs[j], 'alternateRow');
            f=true;
          }
			   }//if table-row       
			}
		}
	}
}

//window.onload = function() { stripedTable(); } 
