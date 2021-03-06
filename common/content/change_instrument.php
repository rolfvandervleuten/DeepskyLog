<?php
// change_instrument.php
// allows the instrument owner or an administrator to change an instrument
// or another user to view the instrument details
if ((! isset ( $inIndex )) || (! $inIndex))
	include "../../redirect.php";
elseif (! ($instrumentid = $objUtil->checkGetKey ( 'instrument' )))
	throw new Exception ( LangException007b );
elseif (! ($objInstrument->getInstrumentPropertyFromId ( $instrumentid, 'name' )))
	throw new Exception ( "Instrument not found in change_instrument.php, please contact the developers with this message:" . $eyepieceid );
else
	change_instrument ();
function change_instrument() {
	global $baseURL, $instrumentid, $loggedUser, $objInstrument, $objPresentations, $objUtil;
	$disabled = " disabled=\"disabled\"";
	if (($loggedUser) && ($objUtil->checkAdminOrUserID ( $objInstrument->getInstrumentPropertyFromId ( $instrumentid, 'observer', '' ) )))
		$disabled = "";
	$content = ($disabled ? "" : "<input type=\"submit\" class=\"btn btn-primary pull-right\" name=\"change\" value=\"" . LangChangeInstrumentButton . "\" />&nbsp;");
	$name = $objInstrument->getInstrumentPropertyFromId ( $instrumentid, 'name' );
	echo "<div id=\"main\">";
	echo "<form role=\"form\" action=\"" . $baseURL . "index.php\" method=\"post\"><div>";
	echo "<input type=\"hidden\" name=\"indexAction\" value=\"validate_instrument\" />";
	echo "<input type=\"hidden\" name=\"id\" value=\"" . $instrumentid . "\" />";
	echo "<h4>" . (($name == "Naked eye") ? InstrumentsNakedEye : $name) . "</h4>";
	echo "<hr />";
	echo $content;
	
	echo "<div class=\"form-group\">
 	       <label for=\"filtername\">" . LangAddInstrumentField1 . "</label>";
	echo "<input value=\"" . $name . "\" type=\"text\" required class=\"form-control\" maxlength=\"64\" name=\"instrumentname\" size=\"30\" " . $disabled . " />"; 
	echo "</div>";
	
	$content = "<input value=\"" . round ( $objInstrument->getInstrumentPropertyFromId ( $instrumentid, 'diameter' ), 0 ) . "\" type=\"number\" min=\"0.01\" step=\"0.01\" class=\"form-control\" required maxlength=\"64\" name=\"diameter\" size=\"10\" " . $disabled . " />";
	$content .= "<select name=\"diameterunits\" class=\"form-control\"" . $disabled . " >";
	$content .= "<option>inch</option>";
	$content .= "<option selected=\"selected\">mm</option>";
	$content .= "</select>";

	echo "<div class=\"form-group\">
 	       <label for=\"filtername\">" . LangAddInstrumentField2 . "</label>";
	echo "<div class=\"form-inline\">";
	echo $content;
	echo "</div>";
	echo "</div>";
	
	
	echo "<div class=\"form-group\">
 	       <label for=\"filtername\">" . LangAddInstrumentField5 . "</label>";
	echo "<div class=\"form-inline\">";
	echo $objInstrument->getInstrumentEchoListType ( $objInstrument->getInstrumentPropertyFromId ( $instrumentid, 'type' ), $disabled );
	echo "</div></div>";
	
	$content = "<input value=\"" . (($fl = round ( $objInstrument->getInstrumentPropertyFromId ( $instrumentid, 'fd' ) * $objInstrument->getInstrumentPropertyFromId ( $instrumentid, 'diameter' ), 0 )) ? $fl : "") . "\" type=\"number\" min=\"0.01\" step=\"0.01\" required class=\"form-control\" maxlength=\"64\" name=\"focallength\" size=\"10\" " . $disabled . " />";
	$content .= "<select class=\"form-control\" name=\"focallengthunits\" " . $disabled . " >";
	$content .= "<option>inch</option>";
	$content .= "<option selected=\"selected\">mm</option>";
	$content .= "</select>";
	$content .= ' ' . LangAddInstrumentOr . ' ' . LangAddInstrumentField3 . ' ';
	$content .= "<input type=\"number\" min=\"0.01\" step=\"0.01\" required class=\"form-control\" maxlength=\"64\" name=\"fd\" size=\"10\"  " . $disabled . " />";

	echo "<div class=\"form-group\">
 	       <label for=\"filtername\">" . LangAddInstrumentField4 . "</label>";
	echo "<div class=\"form-inline\">";
	echo $content;
	echo "</div></div>";
	
	echo "<div class=\"form-group\">
 	       <label for=\"filtername\">" . LangAddInstrumentField6 . "</label>";
	echo "<div class=\"form-inline\">";
	echo "<input value=\"" . (($fm = $objInstrument->getInstrumentPropertyFromId ( $instrumentid, 'fixedMagnification' )) ? $fm : "") . "\" type=\"number\" min=\"0.1\" step=\"0.1\" class=\"form-control\" maxlength=\"10\" name=\"fixedMagnification\" size=\"5\" " . $disabled . " />";
	echo "</div></div>";
	
	echo "<hr />";
	echo "</div></form>";
	echo "</div>";
}
?>
