<?php
echo "<script type=\"text/javascript\" src=\"".$baseURL."lib/javascript/wz_jsgraphics.js\"></script>";
echo "<script type=\"text/javascript\" src=\"".$baseURL."lib/javascript/atlaspage.js\"></script>";
$ra=0;
$decl=0;
$object=$objUtil->checkRequestKey('object');
if($object)
{ $ra=$objObject->getDsoProperty($object,'ra',0);
  $decl=$objObject->getDsoProperty($object,'decl',0);
}
echo "<script type=\"text/javascript\">";
echo "atlaspagera=".$ra.";atlaspagedecl=".$decl.";";
while(list($name,$value)=each($atlasPageText))
  echo $name."Txt='".$value."';";
echo "</script>";



echo "<div id=\"myDiv\" style=\"position:absolute;top:0px;left:0px;height:100%;width:100%;margin:0%;background-color:#555555;border-style:none;border-color:#FF0000;\" onmousemove=\"canvasOnMouseMove(event);\">";
echo "</div>"; 



//echo "<div id=\"gridDiv\"  style=\"position:absolute;top:120px;left:170px;height:400px;width:400px;cursor:crosshair;background-color:#ffFF00;\">&nbsp;</div>";
?>