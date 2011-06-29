
  window.onload = init;

  function init() {
      clearall();
  }//init

  function allnone() {
    for (i=0; i<catarr.length; i++) {
      st = 'cat'+catarr[i];
      document.getElementById(st).style.display="none";
      document.getElementById('chk'+st).checked=false;
    }
  }//allnone


  function alldeselect() {
    for (i=0; i<catarr.length; i++) {
      st = 'cat'+catarr[i];
      document.getElementById('chk'+st).checked=false;
    }
  }//alldeselect

  function deletednone() {
    for (i=0; i<deletedarr.length; i++) {
      st = 'cat'+deletedarr[i];
      document.getElementById(st).style.display="none";
      document.getElementById('chk'+st).checked=false;
    }
  }//deletednone

  function showall() {
    document.getElementById("idtitlesearch").value='';
    var oldshowallchk=document.getElementById("showallchk").checked;//save
    clearall();
    document.getElementById("showallchk").checked=oldshowallchk;//restore
/*
    deletednone();
    document.getElementById("showmorechk").checked=false;
    for (i=0; i<catarr.length; i++) {
      st = 'cat'+catarr[i];
      document.getElementById(st).style.display="none";
    }
*/
    if (document.getElementById("showallchk").checked) {
      for (i=0; i<catarr.length;i++) {
        st = 'cat'+catarr[i];
        document.getElementById(st).style.display="table-row";
      }
    }
    stripedTable();
  }//showall

  function showlistORing() {
    for (i=0; i<catarr.length; i++) {
      st = 'cat'+catarr[i];
      document.getElementById(st).style.display="none";
    }

    if (document.getElementById("showallchk").checked)
      for (i=0; i<catarr.length;i++) {
        st = 'cat'+catarr[i];
        document.getElementById(st).style.display="table-row";
      }

    if (document.getElementById("solarchk").checked)
      for (i=0; i<solararr.length;i++) {
        st = 'cat'+solararr[i];
        document.getElementById(st).style.display="table-row";
      }

    if (document.getElementById("flarechk").checked)
      for (i=0; i<flarearr.length;i++) {
        st = 'cat'+flarearr[i];
        document.getElementById(st).style.display="table-row";
      }

    if (document.getElementById("cmechk").checked)
      for (i=0; i<cmearr.length;i++) {
        st = 'cat'+cmearr[i];
        document.getElementById(st).style.display="table-row";
      }

    if (document.getElementById("swindchk").checked)
      for (i=0; i<swindarr.length;i++) {
        st = 'cat'+swindarr[i];
        document.getElementById(st).style.display="table-row";
      }

    if (document.getElementById("partchk").checked)
      for (i=0; i<partarr.length;i++) {
        st = 'cat'+partarr[i];
        document.getElementById(st).style.display="table-row";
      }

    if (document.getElementById("insituchk").checked)
      for (i=0; i<insituarr.length;i++) {
        st = 'cat'+insituarr[i];
        document.getElementById(st).style.display="table-row";
      }

    if (document.getElementById("remotechk").checked)
      for (i=0; i<remotearr.length;i++) {
        st = 'cat'+remotearr[i];
        document.getElementById(st).style.display="table-row";
      }

    if (document.getElementById("ipschk").checked)
      for (i=0; i<ipsarr.length;i++) {
        st = 'cat'+ipsarr[i];
        document.getElementById(st).style.display="table-row";
      }

    if (document.getElementById("geochk").checked)
      for (i=0; i<geoarr.length;i++) {
        st = 'cat'+geoarr[i];
        document.getElementById(st).style.display="table-row";
      }

    return true;
  }//showlistORing

  var wasremote = false;//needed for mutal exclusion

  function showlist() {//logical AND
    alldeselect();
    document.getElementById("idtitlesearch").value='';
    deletednone();
    for (i=0; i<catarr.length; i++) {
      st = 'cat'+catarr[i];
      document.getElementById(st).style.display="none";
    }

    if ((document.getElementById("insituchk").checked) &&
        wasremote) {
      document.getElementById("remotechk").checked=false;
    }
    if (document.getElementById("remotechk").checked)
      document.getElementById("insituchk").checked=false;

    var flag = false;
    var t = catarr.slice();

    var t2 = new Array();
    if (document.getElementById("solarchk").checked) {
      flag =true;
      for (i=0; i<solararr.length; i++) {
        for (j=0; j<t.length; j++) {
          if (solararr[i]==t[j]) { t2.push(solararr[i]); };
        }//for j
      }//for i
      t = t2.slice();
    }//if solarchk

    //desc(t.join());

    t2 = new Array();
    if (document.getElementById("flarechk").checked) {
      flag =true;
      for (i=0; i<flarearr.length; i++) {
        for (j=0; j<t.length; j++) {
          if (flarearr[i]==t[j]) { t2.push(flarearr[i]); };
        }//for j
      }//for i
      t = t2.slice();
    }//if flarechk

    t2 = new Array();
    if (document.getElementById("cmechk").checked) {
      flag =true;
      for (i=0; i<cmearr.length; i++) {
        for (j=0; j<t.length; j++) {
          if (cmearr[i]==t[j]) { t2.push(cmearr[i]); };
        }//for j
      }//for i
      t = t2.slice();
    }//if cmechk

    t2 = new Array();
    if (document.getElementById("swindchk").checked) {
      flag =true;
      for (i=0; i<swindarr.length; i++) {
        for (j=0; j<t.length; j++) {
          if (swindarr[i]==t[j]) { t2.push(swindarr[i]); };
        }//for j
      }//for i
      t = t2.slice();
    }//if swindchk

    t2 = new Array();
    if (document.getElementById("partchk").checked) {
      flag =true;
      for (i=0; i<partarr.length; i++) {
        for (j=0; j<t.length; j++) {
          if (partarr[i]==t[j]) { t2.push(partarr[i]); };
        }//for j
      }//for i
      t = t2.slice();
    }//if partchk

    t2 = new Array();
    if (document.getElementById("insituchk").checked) {
      flag =true;
      for (i=0; i<insituarr.length; i++) {
        for (j=0; j<t.length; j++) {
          if (insituarr[i]==t[j]) { t2.push(insituarr[i]); };
        }//for j
      }//for i
      t = t2.slice();
    }//if insituchk

    t2 = new Array();
    if (document.getElementById("remotechk").checked) {
      flag =true;
      for (i=0; i<remotearr.length; i++) {
        for (j=0; j<t.length; j++) {
          if (remotearr[i]==t[j]) { t2.push(remotearr[i]); };
        }//for j
      }//for i
      t = t2.slice();
      wasremote = true;
    } else {
      wasremote = false;
    }//if remotechk

    t2 = new Array();
    if (document.getElementById("ipschk").checked) {
      flag =true;
      for (i=0; i<ipsarr.length; i++) {
        for (j=0; j<t.length; j++) {
          if (ipsarr[i]==t[j]) { t2.push(ipsarr[i]); };
        }//for j
      }//for i
      t = t2.slice();
    }//if ipschk

    t2 = new Array();
    if (document.getElementById("geochk").checked) {
      flag =true;
      for (i=0; i<geoarr.length; i++) {
        for (j=0; j<t.length; j++) {
          if (geoarr[i]==t[j]) { t2.push(geoarr[i]); };
        }//for j
      }//for i
      t = t2.slice();
    }//if geochk

    t2 = new Array();
    if (document.getElementById("planetchk").checked) {
      flag =true;
      for (i=0; i<planetarr.length; i++) {
        for (j=0; j<t.length; j++) {
          if (planetarr[i]==t[j]) { t2.push(planetarr[i]); };
        }//for j
      }//for i
      t = t2.slice();
    }//if planetchk
    
    if (flag==true) {
      for (i=0; i<t.length; i++) {
        st = 'cat'+t[i];
        document.getElementById(st).style.display="table-row";
      }
    }

    document.getElementById("showallchk").checked=false;
    document.getElementById("showmorechk").checked=false;
    stripedTable();
  }//showlist

  function clearall(){
    document.getElementById("solarchk").checked=false;
    document.getElementById("flarechk").checked=false;
    document.getElementById("cmechk").checked=false;
    document.getElementById("swindchk").checked=false;
    document.getElementById("partchk").checked=false;
    document.getElementById("insituchk").checked=false;
    document.getElementById("remotechk").checked=false;
    document.getElementById("ipschk").checked=false;
    document.getElementById("geochk").checked=false;
    document.getElementById("planetchk").checked=false;

    document.getElementById("clearchk").checked=false;
    document.getElementById("showallchk").checked=false;
    document.getElementById("showmorechk").checked=false;
//    document.getElementById("selectallchk").checked=false;
    document.getElementById("idtitlesearch").value='';
    document.getElementById("idmetadatasearch").value='';
//    document.getElementById("idgooglesearch").value='';
    document.getElementById("allremotechk").checked=true;
    //showlist();
    allnone();
    deletednone();
  }//clearall

  function selectall(){
    document.getElementById("solarchk").checked=true;
    document.getElementById("flarechk").checked=true;
    document.getElementById("cmechk").checked=true;
    document.getElementById("swindchk").checked=true;
    document.getElementById("partchk").checked=true;
    document.getElementById("insituchk").checked=true;
    document.getElementById("remotechk").checked=true;
    document.getElementById("ipschk").checked=true;
    document.getElementById("geochk").checked=true;

    document.getElementById("showallchk").checked=false;
//    document.getElementById("selectallchk").checked=false;
    showlist();
  }//selectall

  //show deleted catalogues
  function showmore() {
    //allnone();
    //deletednone();
    document.getElementById("idtitlesearch").value='';
    var oldshowmorechk=document.getElementById("showmorechk").checked;//save
    clearall();
    document.getElementById("showmorechk").checked=oldshowmorechk;//restore

    document.getElementById("showallchk").checked=false;
    if (document.getElementById("showmorechk").checked)
      for (i=0; i<deletedarr.length;i++) {
        st = 'cat'+deletedarr[i];
        document.getElementById(st).style.display="table-row";
      }
    return true;
  }//showmore

  function desc(text) {
    alert(text);
  }//desc

  function titlesearch() {
    allnone();
    deletednone();
    document.getElementById("showallchk").checked=false;

    var text;
    text = document.getElementById("idtitlesearch").value.toUpperCase();
    //desc(text);
    //desc(descarr[parseInt(catarr[0])].toUpperCase().indexOf(text));
    //desc(text+text.length);
    t = catarr.concat(deletedarr);

    for (i=0; i<t.length; i++) {
      var description = descarr[parseInt(t[i])];
      description = description.toUpperCase();
      if (description.indexOf(text)>-1) {
        st = 'cat'+t[i];
        document.getElementById(st).style.display="table-row";
      }
    }
    if (text.length==0) {
      allnone();
      deletednone();
    }
  }//titlesearch

  function metadatasearch(e) {
    //http://www.asquare.net/javascript/tests/KeyCode.html
    if (e.keyCode == 13) {
      text = document.getElementById("idmetadatasearch").value;
      window.open('hec_gui_metadatasearch.php?q='+text);
    }
  }//metadatasearch


  function googlesearch(e) {
    if (e.keyCode == 13) {
      text = document.getElementById("idgooglesearch").value;
      window.open('http://www.google.com/search?q=site%3A140.105.77.30%2Fhec%2Fstfc&q='+text);
    }
  }//googlesearch


  function InfoMouseOver(id,text) {
     document.getElementById(id).innerHTML =text;
     document.getElementById(id).style.backgroundColor='yellow';
     document.getElementById(id).style.fontWeight='normal';
  }
   
  function InfoMouseOut(id,text) {
    document.getElementById(id).innerHTML =text;
    document.getElementById(id).style.backgroundColor='ivory';
    document.getElementById(id).style.fontWeight='bold';
  }//mouseOut


  function myOnsubmit() {
//    document.getElementById("fromtime").value = document.getElementByName("y_from").value;
//    desc("xxx");//document.getElementByName("y_from").value);
    return false;
  }//myOnsubmit


  function popup(mylink, windowname) {
    if (! window.focus)return true;
    var href;
    if (typeof(mylink) == 'string')
       href=mylink;
    else
       href=mylink.href;
    window.open(href, windowname, 'width=400,height=200,scrollbars=yes');
    return false;
  }//popup

