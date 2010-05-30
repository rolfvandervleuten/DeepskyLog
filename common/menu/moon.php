<?php // moon.php - menu which shows the moon phase
echo "<div class=\"menuDiv\">";
reset($_GET);
$link="";
while(list($key,$value)=each($_GET))
  if($key!="menuMoon")
    $link.="&amp;".$key."=".urlencode($value);
reset($_GET);
echo "<p  class=\"menuHead\">";
if($loggedUser&&$objObserver->getObserverProperty($loggedUser, 'stdLocation')) {
  if($menuMoon=="collapsed")
    echo "<a href=\"".$baseURL."index.php?menuMoon=expanded".$link."\" title=\"".LangMenuExpand."\">+</a> ";
  else
    echo "<a href=\"".$baseURL."index.php?menuMoon=collapsed".$link."\" title=\"".LangMenuCollapse."\">-</a> ";
}
$theYear=$objUtil->checkSessionKey('globalYear',date("Y"));
$theMonth=$objUtil->checkSessionKey('globalMonth',date("n"));
$theDay=$objUtil->checkSessionKey('globalDay',date('j'));
$theHour="";
$theMinute="";

$date = $theYear . "-". $theMonth . "-" . $theDay;
$time = "23:59:59";
$tzone = "GMT";
$dateTimeText=date($dateformat, mktime(0, 0, 0, $theMonth, $theDay, $theYear));
echo LangMoonMenuTitle."<br /><span class=\"menuText\" style=\"font-weight:normal;\">";
if($menuMoon!="collapsed")
  echo"(op ".$dateTimeText.")";
echo "</span>"."</p>";

  
/*$theYear=$_SESSION['globalYear'];
$theMonth=$_SESSION['globalMonth'];
$theDay=$_SESSION['globalDay'];
$theHour="";
$theMinute="";
*/
//temp suggestion to allow trunk to work for some testing by david

// Only show the current moon phase
include_once "lib/moonphase.inc.php";
include_once "lib/astrocalc.php";
$moondata = phase(strtotime($date . ' ' . $time . ' ' . $tzone));

$MoonIllum  = $moondata[1];
$MoonAge    = $moondata[2];
$nextNewMoonText=LangMoonMenuNewMoon.": ";
$phases = array();
$phases = phasehunt(strtotime($date));
$nextNewMoonText.=date("j M", $phases[4]);
  
// Convert $MoonIllum to percent and round to whole percent.
$MoonIllum = round( $MoonIllum, 2 );
$MoonIllum *= 100;


  // 1) Check if logged in
  if($loggedUser&&$objObserver->getObserverProperty($loggedUser, 'stdLocation')) {
    // 2) Get the julian day of today...
    $jd = gregoriantojd($theMonth, $theDay, $theYear);
    
    // 3) Get the standard location of the observer
    $longitude = $objLocation->getLocationPropertyFromId($objObserver->getObserverProperty($loggedUser, 'stdLocation'), 'longitude');
    $latitude = $objLocation->getLocationPropertyFromId($objObserver->getObserverProperty($loggedUser, 'stdLocation'), 'latitude');
    if((!($objUtil->checkSessionKey('efemerides'))) || ($_SESSION['efemerides']['base']!=$jd."/".$longitude."/".$latitude))
    { if ($longitude > -199) 
      { $timezone=$objLocation->getLocationPropertyFromId($objObserver->getObserverProperty($loggedUser, 'stdLocation'),'timezone');
	
	      $dateTimeZone=new DateTimeZone($timezone);
	      $datestr=sprintf("%02d",$_SESSION['globalMonth'])."/".sprintf("%02d",$_SESSION['globalDay'])."/".$_SESSION['globalYear'];
	      $dateTime = new DateTime($datestr, $dateTimeZone);
	      // Geeft tijdsverschil terug in seconden
	      $timedifference = $dateTimeZone->getOffset($dateTime);
	      $timedifference = $timedifference / 3600.0;
	      if (strncmp($timezone, "Etc/GMT", 7) == 0) {
	        $timedifference = -$timedifference;
	      }
	
	      // Calculate the rise and set time of the moon
	      $moon = $objAstroCalc->calculateMoonRiseTransitSettingTime($jd, $longitude, $latitude, $timedifference);
	
	      // SUNRISE and SET, TWILIGHT...
	      date_default_timezone_set ("UTC");
	      $timestr = $theYear . "-" . $theMonth . "-" . $theDay;
	
	      $sun_info = date_sun_info(strtotime($timestr), $latitude, $longitude);
	    
	      $srise = $sun_info["sunrise"];
	      if ($srise > 1) {
	        $srise = date("H:i", $srise + $timedifference * 60 * 60);
	      } else {
	        $srise = "-";
	      }
	      
	      $sset = $sun_info["sunset"];
	      if ($sset > 1) {
	        $sset = date("H:i", $sset + $timedifference * 60 * 60);
	      } else {
	        $sset = "-";
	      }
	
	      $nautb = $sun_info["nautical_twilight_begin"];
	      if ($nautb > 1) {
	        $nautb = date("H:i", $nautb + $timedifference * 60 * 60);
	      } else {
	        $nautb = "-";
	      }
	
	      $naute = $sun_info["nautical_twilight_end"];
	      if ($naute > 1) {
	        $naute = date("H:i", $naute + $timedifference * 60 * 60);
	      } else {
	        $naute = "-";
	      }
	      
	      $astrob = $sun_info["astronomical_twilight_begin"];
	      if ($astrob > 1) {
	        $astrob = date("H:i", $astrob + $timedifference * 60 * 60);
	      } else {
	        $astrob = "-";
	      }
	
	      $astroe = $sun_info["astronomical_twilight_end"];
	      if ($astroe > 1) {
	        $astroe = date("H:i", $astroe + $timedifference * 60 * 60);
	      } else {
	        $astroe = "-";
	      }
	      $_SESSION['efemerides']['base']=$jd."/".$longitude."/".$latitude;
	      $_SESSION['efemerides']['astrob']=$astrob;
	      $_SESSION['efemerides']['astroe']=$astroe;
	      $_SESSION['efemerides']['nautb']=$nautb;
	      $_SESSION['efemerides']['naute']=$naute;
	      $_SESSION['efemerides']['srise']=$srise;
	      $_SESSION['efemerides']['sset']=$sset;
	      $_SESSION['efemerides']['moon0']=$moon[0];
	      $_SESSION['efemerides']['moon2']=$moon[2];
	      
      }
    }  
if($menuMoon!="collapsed") {
    //echo "<span class=\"menuText\">".LangMoonRise." : " . $_SESSION['efemerides']['moon0'] . "<br />";
	  // Setting of the moon
	  //echo LangMoonSet." : " . $_SESSION['efemerides']['moon2'] . "<br />";


    
    /*
    echo LangMoonSun . " : " . $_SESSION['efemerides']['srise'] . " - " . $_SESSION['efemerides']['sset'];
    echo "<br />" . LangMoonTwilight . " : ";
    echo "<br />&nbsp;&nbsp;" . LangMoonNaut . " : " .  $_SESSION['efemerides']['nautb'] . " - " . $_SESSION['efemerides']['naute'];
    echo "<br />&nbsp;&nbsp;" . LangMoonAstro . " : " . $_SESSION['efemerides']['astrob'] . " - " . $_SESSION['efemerides']['astroe'];
    echo "</span><br />";
    */
    echo "<table class=\"centered\">";
    echo "<tr class=\"menuText\">";
    echo "<th>"."Nacht"."</th>"."<th>"."van"."</th>"."<th>"."tot"."</th>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>"."Maan"."</td>"."<td>".$_SESSION['efemerides']['moon0']."</td>"."<td>".$_SESSION['efemerides']['moon2']."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>"."Zon"."</td>"."<td>".$_SESSION['efemerides']['sset']."</td>"."<td>".$_SESSION['efemerides']['srise']."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>"."Naut"."</td>"."<td>".$_SESSION['efemerides']['naute']."</td>"."<td>".$_SESSION['efemerides']['nautb']."</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>"."Astro"."</td>"."<td>".$_SESSION['efemerides']['astroe']."</td>"."<td>".$_SESSION['efemerides']['astrob']."</td>";
    echo "</tr>";
    echo "</table>";
    
    $file = "m" . round(($MoonAge / SYNMONTH) * 40) . ".gif";
    echo "<p><span class=\"menuText\">".LangMoonMenuActualMoon."&nbsp;"."<img src=\"".$baseURL."/lib/moonpics/" . $file . "\" class=\"moonpic\" title=\"" . $MoonIllum . "%\" alt=\"" . $MoonIllum . "%\" /></span><p />";
    echo "<span class=\"menuText\">".$nextNewMoonText."</span><br />";
    
  }
}
echo "</div>";
?>
