<?php 
// new_observation.php
// add a new observation to the database

if((!isset($inIndex))||(!$inIndex)) include "../../redirect.php";
elseif(!$loggedUser) throw new Exception(LangException002);
else new_observation();

function new_observation()
{ global $baseURL,$loggedUser,$DSOcatalogs,
         $ClusterTypeA,$ClusterTypeB,$ClusterTypeC,$ClusterTypeD,$ClusterTypeE,$ClusterTypeF,$ClusterTypeG,$ClusterTypeH,$ClusterTypeI,$ClusterTypeX,
         $objObservation,$objObject,$objFilter,$objLens,$objEyepiece,$objLanguage,$objObserver,$objInstrument,$objLocation,$objPresentations,$objUtil;

	echo "<script type=\"text/javascript\" src=\"".$baseURL."lib/javascript/checkUtils.js\"></script>";
	// Script to change the visibility when we are observing a resolved open cluster
	echo "<script type=\"text/javascript\">
	      function setOptions(opn, oc1, oc2, oc3, oc4, oc5, oc6, oc7, vis1, vis2, vis3, vis4, vis5, vis6, vis7)
	      {
	        var selbox = document.getElementById('visibility');
	        if(opn == true) {
	        selbox.options.length = 0;
	          if (document.getElementById('resolved').checked == true) {
	            selbox.options[selbox.options.length] = new Option('-----','0');
	            selbox.options[selbox.options.length] = new Option(oc1,'1');
	            selbox.options[selbox.options.length] = new Option(oc2,'2');
	            selbox.options[selbox.options.length] = new Option(oc3,'3');
	            selbox.options[selbox.options.length] = new Option(oc4,'4');
	            selbox.options[selbox.options.length] = new Option(oc5,'5');
	            selbox.options[selbox.options.length] = new Option(oc6,'6');
	            selbox.options[selbox.options.length] = new Option(oc7,'7');
	          } else {
	            selbox.options[selbox.options.length] = new Option('-----','0');
	            selbox.options[selbox.options.length] = new Option(vis1,'1');
	            selbox.options[selbox.options.length] = new Option(vis2,'2');
	            selbox.options[selbox.options.length] = new Option(vis3,'3');
	            selbox.options[selbox.options.length] = new Option(vis4,'4');
	            selbox.options[selbox.options.length] = new Option(vis5,'5');
	            selbox.options[selbox.options.length] = new Option(vis6,'6');
	            selbox.options[selbox.options.length] = new Option(vis7,'7');
	          }
	        }
	      }
	      </script>";
	
	echo "	<script type=\"text/javascript\" src=\"".$baseURL."lib/javascript/CalendarPopupCC.js\"></script>";
	echo "	<script type=\"text/javascript\" >";
	echo "	var cal = new CalendarPopup();";
	echo "  function SetObsDate(y,m,d)";
	echo "  {";
	echo "    document.getElementById('day').value = d;";
	echo "    document.getElementById('month').value = m;";
	echo "    document.getElementById('year').value = y;";													 
	echo "	}";
	echo "	</script>";
	
	echo "<div id=\"main\">";
	if(($observationid=$objUtil->checkGetKey('observation'))&&($objUtil->checkAdminOrUserID($objObservation->getDsObservationProperty($_GET['observation'],'observerid'))))
	  $object=$objObservation->getDsObservationProperty($observationid,'objectname');
	else
	{ $observationid=0;
	  $object=$objUtil->checkPostKey('object', $objUtil->checkGetKey('object'));
	}
	if($object&&($objUtil->checkArrayKey($_SESSION,'addObs',0)==$objUtil->checkPostKey('timestamp',-1)))
	{ echo "<form action=\"".$baseURL."index.php\" method=\"post\" enctype=\"multipart/form-data\"><div>";
		echo "<input type=\"hidden\" name=\"indexAction\"   value=\"validate_observation\" />";
		echo "<input type=\"hidden\" name=\"titleobject\"   value=\"".LangQuickPickNewObservation."\" />";
		echo "<input type=\"hidden\" name=\"observationid\" value=\"".$observationid."\" />";
		echo "<input type=\"hidden\" name=\"timestamp\"     value=\"" . $_POST['timestamp'] . "\" />";
		echo "<input type=\"hidden\" name=\"object\"        value=\"" . $object . "\" />";
		if($observationid)
		{ $content="<input type=\"submit\" name=\"changeobservation\" value=\"" . LangChangeObservationButton . "\" />&nbsp;";
		  $objPresentations->line(array("<h4>".LangNewObservationSubtitle3B."<span class=\"requiredField\">".LangNewObservationSubtitle3A."</span>".LangNewObservationSubtitle3C.$object."</h4>",$content),"LR",array(80,20),30);
	  }
		else
		{ $content="<input type=\"submit\" name=\"addobservation\" value=\"" . LangViewObservationButton1 . "\" />&nbsp;";
		  $objPresentations->line(array("<h4>".LangNewObservationSubtitle3."<span class=\"requiredField\">".LangNewObservationSubtitle3A."</span>".LangNewObservationSubtitle3C.$object."</h4>",$content),"LR",array(80,20),30);
	  }
	  echo "<hr />";
		echo "<div class=\"inputDiv\">";
	  // Location =====================================================================================================================================================================
	  $sites = $objLocation->getSortedLocationsList("name", $loggedUser,1);
		$theLoc=(($observationid)?$objObservation->getDsObservationProperty($_GET['observation'],'locationid'):$objUtil->checkPostKey('site'));
		$contentLoc="<select class=\"inputfield requiredField\" name=\"site\">";
		while(list($key,$value)=each($sites))
			$contentLoc.="<option ".(($value[0]==$theLoc)?"selected=\"selected\"":'')." value=\"".$value[0]."\">".$value[1]."</option>";
		$contentLoc.="</select>&nbsp;";
	  // Date and time =====================================================================================================================================================================
	  if($observationid)
	  { if($objObserver->getObserverProperty($loggedUser,'UT'))
	    { $date = sscanf($objObservation->getDsObservationProperty($observationid,'date'), "%4d%2d%2d");
		    $timestr = $objObservation->getDsObservationProperty($observationid,'time');
	    }
	    else
	    { $date = sscanf($objObservation->getDsObservationLocalDate($observationid), "%4d%2d%2d");
		    $timestr = $objObservation->getDsObservationLocalTime($observationid);
	    } 
	    if($timestr>=0)
	    { $time=sscanf(sprintf("%04d", $timestr),"%2d%2d");
	      $theHour=$time[0];
	  	  $theMinute=$time[1];
	    }
	    else
	    { $theHour="";
		    $theMinute="";
	    }
	  	$theDay=$date[2];
	  	$theMonth=$date[1];
	  	$theYear=$date[0];
	  }
	  elseif($objUtil->checkPostKey('month'))
	  { $theDay=$objUtil->checkPostKey('day');
	    $theMonth=$objUtil->checkPostKey('month');
	    $theYear=$objUtil->checkPostKey('year');
	    $theHour=$objUtil->checkPostKey('hours');
		  $theMinute=$objUtil->checkPostKey('minutes');
	  }
	  else
	  { $yesterday=date('Ymd',strtotime('-1 day'));
	    $theYear=substr($yesterday,0,4);
	    $theMonth=substr($yesterday,4,2);
	    $theDay=substr($yesterday,6,2);
	    $theHour="";
		  $theMinute="";
	  }
	  $contentDate ="<input type=\"text\" class=\"inputfield requiredField centered\" maxlength=\"2\" size=\"3\"  name=\"day\" id=\"day\" value=\"".$theDay."\" onkeypress=\"return checkPositiveInteger(event);\" />";
		$contentDate.="&nbsp;&nbsp;";
		$contentDate.="<select name=\"month\" id=\"month\" class=\"inputfield requiredField centered\">";
		for($i= 1;$i<13;$i++)
			$contentDate.="<option value=\"".$i."\"".(($theMonth==$i)?" selected=\"selected\"" : "").">".$GLOBALS['Month'.$i]."</option>";
		$contentDate.="</select>";
		$contentDate.="&nbsp;&nbsp;";
		$contentDate.="<input type=\"text\" class=\"inputfield requiredField centered\" maxlength=\"4\" size=\"4\"  name=\"year\" id=\"year\" onkeypress=\"return checkPositiveInteger(event);\" value=\"".$theYear."\" />";
		$contentTime ="<input type=\"text\" class=\"inputfield centered\" maxlength=\"2\" size=\"2\"name=\"hours\" value=\"".$theHour."\" />";
		$contentTime.="&nbsp;&nbsp;";
		$contentTime.="<input type=\"text\" class=\"inputfield centered\" maxlength=\"2\" size=\"2\" name=\"minutes\" value=\"".$theMinute."\" />&nbsp;&nbsp;";
		// Instrument =====================================================================================================================================================================
		$instr=$objInstrument->getSortedInstrumentsList("name", $loggedUser,1);
		$theInstrument=(($observationid)?$objObservation->getDsObservationProperty($observationid,'instrumentid'):$objUtil->checkPostKey('instrument',0));
		$contentInstrument = "<select name=\"instrument\" class=\"inputfield requiredField\">";
		while(list($key,$value)=each($instr))
			$contentInstrument.="<option ".(($theInstrument==$key)?"selected=\"selected\"":'')." value=\"".$key."\">".$value."</option>";
		$contentInstrument.="</select>&nbsp;";
		// Description =====================================================================================================================================================================
		$theDescription=(($observationid)?$objPresentations->br2nl(html_entity_decode(preg_replace("/&amp;/", "&",$objObservation->getDsObservationProperty($observationid,'description')))):$objUtil->checkPostKey('description'));
		$contentDescription="<textarea name=\"description\" class=\"description inputfield requiredField\" cols=\"1\" rows=\"1\">".$theDescription."</textarea>";
		// Language =====================================================================================================================================================================
		$theLanguage=(($observationid)?$objObservation->getDsObservationProperty($observationid,'language'):(($tempLang=$objUtil->checkPostKey('description_language'))?$tempLang:$objObserver->getObserverProperty($loggedUser,'observationlanguage')));
		$allLanguages = $objLanguage->getAllLanguages($objObserver->getObserverProperty($loggedUser,'language'));
		$contentLanguage="<select name=\"description_language\"  class=\"inputfield requiredField\">";
		while (list ($key, $value) = each($allLanguages))
			$contentLanguage.= "<option value=\"".$key."\"".(($theLanguage==$key)?"selected=\"selected\"":'').">".$value."</option>";
		$contentLanguage.="</select>&nbsp;";
		// Limiting Magnitude and SQM =====================================================================================================================================================================
		$theLM=(($observationid)?$objObservation->getDsObservationProperty($observationid,'limmag'):$objUtil->checkPostKey('limit'));
		$contentLM="<input type=\"text\" class=\"inputfield centered\" maxlength=\"3\" name=\"limit\" size=\"3\"  value=\"".($theLM?sprintf("%1.1f",$theLM):'')."\" />";
		$theSQM=(($observationid)?((($tempSQM=$objObservation->getDsObservationProperty($_GET['observation'],'SQM'))!=-1)?$tempSQM:''):$objUtil->checkPostKey('sqm'));
		$contentSQM="<input type=\"text\" class=\"inputfield centered\" maxlength=\"4\" name=\"sqm\" size=\"4\"  value=\"".($theSQM?sprintf("%2.1f",$theSQM):'')."\" />";
		// Seeing =====================================================================================================================================================================
		$theSeeing=(($observationid)?$objObservation->getDsObservationProperty($observationid,'seeing'):$objUtil->checkPostKey('seeing',0));
		$contentSeeing ="<select name=\"seeing\" class=\"inputfield\">";
		$contentSeeing.="<option value=\"0\">-----</option>";
		for ($i = 1; $i < 6; $i++)
			$contentSeeing.="<option value=\"" . $i . "\"" . (($theSeeing == $i) ? " selected=\"selected\"" : '') . ">" . $GLOBALS['Seeing' . $i] . "</option>";
		$contentSeeing.="</select>&nbsp;";
		// Eyepiece =====================================================================================================================================================================
		$theEyepiece=(($observationid)?$objObservation->getDsObservationProperty($observationid,'eyepieceid'):$objUtil->checkPostKey('eyepiece'));
		$eyeps = $objEyepiece->getSortedEyepieces("focalLength",$loggedUser,1);
		$contentEyepiece ="<select name=\"eyepiece\" class=\"inputfield\">";
		$contentEyepiece.="<option value=\"\">-----</option>";
		while (list ($key, $value) = each($eyeps))
			$contentEyepiece.="<option value=\"".$value."\" ".(($value==$theEyepiece)?" selected=\"selected\" ":'').">".stripslashes($objEyepiece->getEyepiecePropertyFromId($value,'name'))."</option>";
		$contentEyepiece.="</select>&nbsp;";
		// Lens =====================================================================================================================================================================
		$theLens=(($observationid)?$objObservation->getDsObservationProperty($observationid,'lensid'):$objUtil->checkPostKey('lens'));
		$lns=$objLens->getSortedLenses("name",$loggedUser,1);
		$contentLens ="<select name=\"lens\" class=\"inputfield\">";
		$contentLens.="<option value=\"\">-----</option>";
		while (list ($key, $value) = each($lns))
			$contentLens.="<option value=\"".$value."\" ".(($value==$theLens)?" selected=\"selected\" ":'').">".stripslashes($objLens->getLensPropertyFromId($value,'name'))."</option>";
		$contentLens.="</select>&nbsp;";
		// Filter =====================================================================================================================================================================
		$theFilter=(($observationid)?$objObservation->getDsObservationProperty($observationid,'filterid'):$objUtil->checkPostKey('filter'));
		$filts=$objFilter->getSortedFilters("name",$loggedUser,1);
		$contentFilter ="<select name=\"filter\" class=\"inputfield\">";
		$contentFilter.="<option value=\"\">-----</option>";
		while (list ($key, $value) = each($filts))
			$contentFilter.="<option value=\"".$value."\" ".(($value==$theFilter)?" selected=\"selected\" ":'').">".stripslashes($objFilter->getFilterPropertyFromId($value,'name'))."</option>";
		$contentFilter.="</select>&nbsp;";
		// Magnification =====================================================================================================================================================================
		$theMagnification=($observationid?$objObservation->getDsObservationProperty($observationid,'magnification'):(($tempMag=$objUtil->checkPostKey('magnification'))?sprintf("%2d",$tempMag):'') );
		$contentMagnification="<input type=\"text\" class=\"inputfield centered\" maxlength=\"4\" name=\"magnification\" size=\"4\"  value=\"".$theMagnification."\" /> x";
		// Visibility =====================================================================================================================================================================
		$theVisibility=($observationid?$objObservation->getDsObservationProperty($observationid,'visibility'):$objUtil->checkPostKey('visibility'));
		$contentVisibility ="<select name=\"visibility\" id=\"visibility\" class=\"width300px inputfield\">";
		$contentVisibility.="<option value=\"0\">-----</option>";
		for($i=1;$i<8;$i++)
		{	$contentVisibility.="<option value=\"".$i."\" ".(($theVisibility==$i)?"selected=\"selected\" ":"").">".$GLOBALS['Visibility'.$i]."</option>";
		  $vis[$i] = $GLOBALS['Visibility'.$i];
		}
		$contentVisibility.="</select>&nbsp;";
	  // Visibility for resolved open clusters ========================================================================================================================================
	  $contentVisibilityOc ="<select name=\"visibility\" id=\"visibility\" class=\"width300px inputfield\">";
	  $contentVisibilityOc.="<option value=\"0\">-----</option>";
	  for($i=1;$i<8;$i++)
	  { $contentVisibilityOc.="<option value=\"".$i."\" ".(($theVisibility==$i)?"selected=\"selected\" ":"").">".$GLOBALS['VisibilityOC'.$i]."</option>";
	    $visOc[$i] = $GLOBALS['VisibilityOC'.$i];
	  }
	  $contentVisibilityOc.="</select>&nbsp;";
	  // Visibility for double stars ==================================================================================================================================================
	  $contentVisibilityDs ="<select name=\"visibility\" id=\"visibility\" class=\"width300px inputfield\">";
	  $contentVisibilityDs.="<option value=\"0\">-----</option>";
	  for($i=1;$i<4;$i++)
	    $contentVisibilityDs.="<option value=\"".$i."\" ".(($theVisibility==$i)?"selected=\"selected\" ":"").">".$GLOBALS['VisibilityDS'.$i]."</option>";
	  $contentVisibilityDs.="</select>&nbsp;";
	  // Diameter =====================================================================================================================================================================
		$theDiameter1=($observationid?(($tempD1=$objObservation->getDsObservationProperty($observationid,'largeDiameter'))?$tempD1:''):$objUtil->checkPostKey('largeDiam'));
		$theDiameter2=($observationid?(($tempD2=$objObservation->getDsObservationProperty($observationid,'smallDiameter'))?$tempD2:''):$objUtil->checkPostKey('smallDiam'));
		$theDiameterUnit=($observationid?'sec':$objUtil->checkPostKey('size_units'));
		$contentDiameter ="<input type=\"text\" class=\"inputfield centered\" maxlength=\"5\" name=\"largeDiam\" size=\"5\" value=\"".$theDiameter1."\" />";
		$contentDiameter.="&nbsp;x&nbsp;";
		$contentDiameter.="<input type=\"text\" class=\"inputfield centered\" maxlength=\"5\" name=\"smallDiam\" size=\"5\" value=\"".$theDiameter2."\" />";
		$contentDiameter.="&nbsp;";
		$contentDiameter.="<select name=\"size_units\" class=\"inputfield\">";
		$contentDiameter.="<option value=\"min\"".($theDiameterUnit=='min'?" selected=\"selected\"":"").">" . LangNewObjectSizeUnits1 . "</option>";
		$contentDiameter.="<option value=\"sec\"".($theDiameterUnit=='sec'?" selected=\"selected\"":"").">" . LangNewObjectSizeUnits2 . "</option>";
		$contentDiameter.="</select>&nbsp;";
		// Misc =====================================================================================================================================================================
	  $contentMisc1 ="<input type=\"radio\" name=\"stellarextended\" value=\"stellar\" ".(($objUtil->checkPostKey("stellarextended")=="stellar")?"checked=\"checked\" ":"")."/>" . LangViewObservationField35."&nbsp;";
		$contentMisc1.="<input type=\"radio\" name=\"stellarextended\" value=\"extended\" ".(($objUtil->checkPostKey("stellarextended")=="extended")?"checked ":"")." />" . LangViewObservationField36."&nbsp;";
		$contentMisc1.="<input type=\"checkbox\" name=\"mottled\" ".($objUtil->checkPostKey("mottled")?"checked ":"")."/>" . LangViewObservationField38."&nbsp;";
	  $contentMisc2="";$contentMisc3="";$contentMisc4="";
		if(in_array($objObject->getDsoProperty($object,'type'),array("ASTER","CLANB","OPNCL","AA1STAR","AA3STAR","AA4STAR","AA8STAR","GLOCL"))) 
		{ if(in_array($objObject->getDsoProperty($object,'type'), array("OPNCL"))) 
		  { $opn = true;
		  }	else 
		  { $opn = false;
		  }
		  $contentMisc2.="<input type=\"checkbox\" name=\"resolved\" id=\"resolved\" onclick=\"setOptions($opn, '$visOc[1]', '$visOc[2]', '$visOc[3]', '$visOc[4]', '$visOc[5]', '$visOc[6]', '$visOc[7]', '$vis[1]', '$vis[2]', '$vis[3]', '$vis[4]', '$vis[5]', '$vis[6]', '$vis[7]')\"".($objUtil->checkPostKey("resolved")?"checked ":"")."/>" . LangViewObservationField37."&nbsp;";
	    $contentMisc2.="<input type=\"checkbox\" name=\"unusualShape\" />" . LangViewObservationField41."&nbsp;";
			$contentMisc2.="<input type=\"checkbox\" name=\"partlyUnresolved\" />" . LangViewObservationField42."&nbsp;";
			$contentMisc2.="<input type=\"checkbox\" name=\"colorContrasts\" />" . LangViewObservationField43;
			if($objObject->getDsoProperty($object,'type')!="GLOCL")
			{ $contentMisc3.="<a href=\"http://www.deepskylog.org/twiki/bin/view/DeepskyLog/CharacterType".$objObserver->getObserverProperty($loggedUser,'language')."\" rel=\"external\" title=\"".LangViewObservationField40Expl."\" >".LangViewObservationField40."</a>";
			  $theClustertype=($observationid?$objObservation->getDsObservationProperty($observationid,'clusterType'):$objUtil->checkPostKey('clusterType'));
			  $contentMisc4 ="<select name=\"clusterType\" class=\"inputfield\">";
			  $contentMisc4.="<option value=\"\">-----</option>";
			  $contentMisc4.="<option value=\"A\"" . (($theClustertype == 'A') ? " selected=\"selected\" " : '') . ">A - ".$ClusterTypeA."</option>";
			  $contentMisc4.="<option value=\"B\"" . (($objUtil->checkPostKey('clusterType') == 'B') ? " selected=\"selected\" " : '') . ">B - ".$ClusterTypeB."</option>";
			  $contentMisc4.="<option value=\"C\"" . (($objUtil->checkPostKey('clusterType') == 'C') ? " selected=\"selected\" " : '') . ">C - ".$ClusterTypeC."</option>";
			  $contentMisc4.="<option value=\"D\"" . (($objUtil->checkPostKey('clusterType') == 'D') ? " selected=\"selected\" " : '') . ">D - ".$ClusterTypeD."</option>";
			  $contentMisc4.="<option value=\"E\"" . (($objUtil->checkPostKey('clusterType') == 'E') ? " selected=\"selected\" " : '') . ">E - ".$ClusterTypeE."</option>";
			  $contentMisc4.="<option value=\"F\"" . (($objUtil->checkPostKey('clusterType') == 'F') ? " selected=\"selected\" " : '') . ">F - ".$ClusterTypeF."</option>";
			  $contentMisc4.="<option value=\"G\"" . (($objUtil->checkPostKey('clusterType') == 'G') ? " selected=\"selected\" " : '') . ">G - ".$ClusterTypeG."</option>";
			  $contentMisc4.="<option value=\"H\"" . (($objUtil->checkPostKey('clusterType') == 'H') ? " selected=\"selected\" " : '') . ">H - ".$ClusterTypeH."</option>";
			  $contentMisc4.="<option value=\"I\"" . (($objUtil->checkPostKey('clusterType') == 'I') ? " selected=\"selected\" " : '') . ">I - ".$ClusterTypeI."</option>";
			  $contentMisc4.="<option value=\"X\"" . (($objUtil->checkPostKey('clusterType') == 'X') ? " selected=\"selected\" " : '') . ">J - ".$ClusterTypeX."</option>";
			  $contentMisc4.="</select>&nbsp;";
		  }
		} else if(in_array($objObject->getDsoProperty($object,'type'),array("DS","AA2STAR"))) 
		{
	    $contentMisc2.="<input type=\"checkbox\" name=\"equalBrightness\" />" . LangDetailDS1."&nbsp;";
	    $contentMisc2.="<input type=\"checkbox\" name=\"niceField\" />" . LangDetailDS2;
	    if($objObject->getDsoProperty($object,'type')!="GLOCL")
	    { $contentMisc4 =LangDetailDS3 . "&nbsp;";
	      $theComponent1Color=($observationid?$objObservation->getDsObservationProperty($observationid,'component1'):$objUtil->checkPostKey('component1'));
	      $contentMisc4.="<select name=\"component1\" class=\"inputfield\">";
	      $contentMisc4.="<option value=\"\">-----</option>";
	      $contentMisc4.="<option value=\"1\"" . (($theComponent1Color == '1') ? " selected=\"selected\" " : '').">".LangDetailDSColor1."</option>";
	      $contentMisc4.="<option value=\"2\"" . (($objUtil->checkPostKey('component1') == '2') ? " selected=\"selected\" " : '').">".LangDetailDSColor2."</option>";
	      $contentMisc4.="<option value=\"3\"" . (($objUtil->checkPostKey('component1') == '3') ? " selected=\"selected\" " : '').">".LangDetailDSColor3."</option>";
	      $contentMisc4.="<option value=\"4\"" . (($objUtil->checkPostKey('component1') == '4') ? " selected=\"selected\" " : '').">".LangDetailDSColor4."</option>";
	      $contentMisc4.="<option value=\"5\"" . (($objUtil->checkPostKey('component1') == '5') ? " selected=\"selected\" " : '').">".LangDetailDSColor5."</option>";
	      $contentMisc4.="<option value=\"6\"" . (($objUtil->checkPostKey('component1') == '6') ? " selected=\"selected\" " : '').">".LangDetailDSColor6."</option>";
	      $contentMisc4.="</select>&nbsp;";
	      
	      $contentMisc4.="&nbsp;" . LangDetailDS4 . "&nbsp;";
	      $theComponent2Color=($observationid?$objObservation->getDsObservationProperty($observationid,'component2'):$objUtil->checkPostKey('component2'));
	      $contentMisc4.="<select name=\"component2\" class=\"inputfield\">";
	      $contentMisc4.="<option value=\"\">-----</option>";
	      $contentMisc4.="<option value=\"1\"" . (($theComponent2Color == '1') ? " selected=\"selected\" " : '').">".LangDetailDSColor1."</option>";
	      $contentMisc4.="<option value=\"2\"" . (($objUtil->checkPostKey('component2') == '2') ? " selected=\"selected\" " : '').">".LangDetailDSColor2."</option>";
	      $contentMisc4.="<option value=\"3\"" . (($objUtil->checkPostKey('component2') == '3') ? " selected=\"selected\" " : '').">".LangDetailDSColor3."</option>";
	      $contentMisc4.="<option value=\"4\"" . (($objUtil->checkPostKey('component2') == '4') ? " selected=\"selected\" " : '').">".LangDetailDSColor4."</option>";
	      $contentMisc4.="<option value=\"5\"" . (($objUtil->checkPostKey('component2') == '5') ? " selected=\"selected\" " : '').">".LangDetailDSColor5."</option>";
	      $contentMisc4.="<option value=\"6\"" . (($objUtil->checkPostKey('component2') == '6') ? " selected=\"selected\" " : '').">".LangDetailDSColor6."</option>";
	      $contentMisc4.="</select>&nbsp;";
	    }
		}
		// Presentation =====================================================================================================================================================================
		$contentDateText = "<a href=\"#\" onclick=\"cal.showNavigationDropdowns();
	                             cal.setReturnFunction('SetObsDate');
															 cal.showCalendar('DateAnchor2');
	                             return false;\" 
										 name=\"DateAnchor2\" 
										 id=\"DateAnchor2\">" . LangViewObservationField5 . "</a>"; 
		
		$objPresentations->line(array("<a href=\"".$baseURL."index.php?indexAction=add_site\" title=\"".LangChangeAccountField7Expl."\" >".LangViewObservationField4."&nbsp;*"."</a>",$contentLoc,
		                              $contentDateText."&nbsp;*",$contentDate,
		                              "<a href=\"".$baseURL."index.php?indexAction=add_instrument\" title=\"".LangChangeAccountField8Expl."\" >".LangViewObservationField3."&nbsp;*"."</a>",$contentInstrument),
		                        "RLRLRL",array(11,15,7,27,11,28),35,array("fieldname",""));
		$objPresentations->line(array(LangViewObservationField8 . "&nbsp;*",
		                              $contentDescription),
		                        "RL",array(11,89),130,array("fieldname",""));
		$objPresentations->line(array("",LangViewObservationField29 . "&nbsp;*&nbsp;".$contentLanguage,
		                              LangViewObservationField12,"<input type=\"file\" name=\"drawing\" class=\"inputfield\" />",
		                              "<a href=\"http://www.distant-targets.be/beschrijven\" rel=\"external\">" . LangViewObservationFieldHelpDescription . "</a>"),
		                        "LLRLR",array(11,25,19,30,15));                              
		$objPresentations->line(array("","<hr />"),"LL",array(12,87),10);
		$objPresentations->line(array((($objObserver->getObserverProperty($loggedUser,'UT')) ? LangViewObservationField9: LangViewObservationField9lt),$contentTime.LangViewObservationField11,
		                              LangViewObservationField6,$contentSeeing,
		                              LangViewObservationField7,$contentLM,LangViewObservationField34,$contentSQM),
		                        "RLRLRLRL",array(11,25,8,13,17,10,6,10),30,array("fieldname","","fieldname","","fieldname","","fieldname",""));
		$objPresentations->line(array("","<hr />"),"LL",array(12,87),10);
		$objPresentations->line(array("<a href=\"" . $baseURL . "index.php?indexAction=add_eyepiece\" title=\"".LangViewObservationField30Expl."\">".LangViewObservationField30."</a>",$contentEyepiece,
		                              "<a href=\"" . $baseURL . "index.php?indexAction=add_lens\" title=\"".LangViewObservationField32Expl."\" >".LangViewObservationField32."</a>",$contentLens,
		                              "<a href=\"" . $baseURL . "index.php?indexAction=add_filter\" title=\"".LangViewObservationField31Expl."\" >".LangViewObservationField31."</a>",$contentFilter),
		                        "RLRLRL",array(11,22,11,22,11,22),30,array("fieldname","","fieldname","","fieldname",""));
		$objPresentations->line(array(LangViewObservationField39,
		                              $contentMagnification),
		                        "RL",array(11,89),30,array("fieldname",""));
		$objPresentations->line(array("","<hr />"),"LL",array(12,87),10);
		
		// Check if we are observing a double star. If it is the case, us VisibilityDs
	  if(in_array($objObject->getDsoProperty($object,'type'),array("DS","AA2STAR"))) 
	  { $objPresentations->line(array(LangViewObservationField22,$contentVisibilityDs,
		                                LangViewObservationField33,$contentDiameter),
		                          "RLRL",array(11,33,22,33),30,array("fieldname",""));
	  } else {
	    $objPresentations->line(array(LangViewObservationField22,$contentVisibility,
	                                  LangViewObservationField33,$contentDiameter),
	                            "RLRL",array(11,33,22,33),30,array("fieldname",""));
	  }
		$objPresentations->line(array("",$contentMisc1.$contentMisc2,$contentMisc3,$contentMisc4),
		                        "LLRL",array(11,52,11,25),30);
		echo "</div>";
		echo "</div></form>";
		
		echo "<hr />";
		$seen = $objObject->getDSOseenLink($object);
		
		$objPresentations->line(array("<h4>".LangViewObjectTitle."&nbsp;".$object."&nbsp;:&nbsp;".$seen."</h4>",$objPresentations->getDSSDeepskyLiveLinks1($object)),
		                        "LR",array(50,50),30);
		$objPresentations->line(array($objPresentations->getDSSDeepskyLiveLinks2($object)),
		                        "R",array(100),20);
		echo "<hr />";
		$objObject->showObject($object);
	} 
	else // no object found or not pushed on search button yet
	{ $objPresentations->line(array("<h4>".LangNewObservationTitle."</h4>"),"L",array(),30);
	  echo "<hr />";
	  $content =LangNewObservationSubtitle1a . ", ";
		$content.="<a href=\"" . $baseURL . "index.php?indexAction=add_csv\">" . LangNewObservationSubtitle1b . "</a>" . LangNewObservationSubtitle1abis;
		$content.="<a href=\"" . $baseURL . "index.php?indexAction=add_xml\">" . LangNewObservationSubtitle1c . "</a>";
		$objPresentations->line(array($content),"L",array(),50);
		echo "<form action=\"" . $baseURL . "index.php\" method=\"post\"><div>";
		echo "<input type=\"hidden\" name=\"indexAction\"   value=\"add_observation\" />";
		echo "<input type=\"hidden\" name=\"titleobject\"   value=\"".LangQuickPickNewObservation."\" />";
		$content ="<select name=\"catalog\" class=\"inputfield\">";
		$content.="<option value=\"\">&nbsp;</option>";
		while (list ($key, $value) = each($DSOcatalogs))
			$content.="<option value=\"$value\">$value</option>";
		$content.="</select>";
		$content.="&nbsp;";
		$content.="<input type=\"text\" class=\"inputfield\" maxlength=\"255\" name=\"number\" size=\"50\" value=\"\" />";
		$content3="<input type=\"submit\" name=\"objectsearch\" value=\"" . LangNewObservationButton1 . "\" />";
		$objPresentations->line(array(LangQueryObjectsField1,$content,$content3),"RLR",array(20,60,20),30,array('fieldname'));
	  echo "<hr />";
		echo "</div></form>";
	}
	echo "</div>";
}
?>
