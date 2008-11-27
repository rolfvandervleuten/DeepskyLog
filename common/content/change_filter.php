<?php
// change_filter.php
// form which allows the administrator to change a filter

echo "<div id=\"main\">";
echo "<h2>";
echo stripslashes($objFilter->getFilterName($_GET['filter']));
echo "</h2>";
echo "<form action=\"".$baseURL."index.php\" method=\"post\" />";
echo "<input type=\"hidden\" name=\"indexAction\" value=\"validate_filter\">";
echo "<table>";
tableFieldnameFieldExplanation(LangAddFilterField1,"<input type=\"text\" class=\"inputfield\" maxlength=\"64\" name=\"filtername\" size=\"30\" value=\"".stripslashes($objFilter->getFilterName($_GET['filter']))."\" />",LangAddFilterField1Expl);
echo "<tr>";
echo "<td class=\"fieldname\">";
echo LangAddFilterField2; 
echo "</td>";
echo "<td>";
echo "<select name=\"type\">";
$type=$objFilter->getFilterType($_GET['filter']);
echo "<option".(($type==FilterOther)?" option selected=\"selected\" ":"")."value=\"".FilterOther."\">".FiltersOther."</option>";
echo "<option".(($type==FilterBroadBand)?" option selected=\"selected\" ":"")."value=\"".FilterBroadBand."\">".FiltersBroadBand."</option>";
echo "<option".(($type==FilterNarrowBand)?" option selected=\"selected\" ":"")."value=\"".FilterNarrowBand."\">".FilterNarrowBand."</option>";
echo "<option".(($type==FilterOIII)?" option selected=\"selected\" ":"")."value=\"".FilterOIII."\">".FilterOIII."</option>";
echo "<option".(($type==FilterHAlpha)?" option selected=\"selected\" ":"")."value=\"".FilterHAlpha."\">".FilterHAlpha."</option>";
echo "<option".(($type==FilterColor)?" option selected=\"selected\" ":"")."value=\"".FilterColor."\">".FilterColor."</option>";
echo "<option".(($type==FilterCorrective)?" option selected=\"selected\" ":"")."value=\"".FilterCorrective."\">".FilterCorrective."</option>";
echo "</select>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=\"fieldname\">";
echo LangAddFilterField3;
echo "</td>";
echo "<td>";
echo "<select name=\"color\">";
$color=$objFilter->getColor($_GET['filter']);
echo "<option value=\"\">&nbsp;</option>";
echo "<option".(($color==FilterColorLightRed)?"option selected=\"selected\" ":"")."value=\"".FilterColorLightRed."\">".FiltersColorLightRed."</option>";
echo "<option".(($color==FilterColorRed)?"option selected=\"selected\" ":"")."value=\"".FilterColorRed."\">".FilterColorRed."</option>";
echo "<option".(($color==FilterColorDeepRed)?"option selected=\"selected\" ":"")."value=\"".FilterColorDeepRed."\">".FilterColorDeepRed."</option>";
echo "<option".(($color==FilterColorOrange)?"option selected=\"selected\" ":"")."value=\"".FilterColorOrange."\">".FilterColorOrange."</option>";
echo "<option".(($color==FilterColorLightYellow)?"option selected=\"selected\" ":"")."value=\"".FilterColorLightYellow."\">".FilterColorLightYellow."</option>";
echo "<option".(($color==FilterColorDeepYellow)?"option selected=\"selected\" ":"")."value=\"".FilterColorDeepYellow."\">".FilterColorDeepYellow."</option>";
echo "<option".(($color==FilterColorYellow)?"option selected=\"selected\" ":"")."value=\"".FilterColorYellow."\">".FilterColorYellow."</option>";
echo "<option".(($color==FilterColorYellowGreen)?"option selected=\"selected\" ":"")."value=\"".FilterColorYellowGreen."\">".FilterColorYellowGreen."</option>";
echo "<option".(($color==FilterColorLightGreen)?"option selected=\"selected\" ":"")."value=\"".FilterColorLightGreen."\">".FilterColorLightGreen."</option>";
echo "<option".(($color==FilterColorGreen)?"option selected=\"selected\" ":"")."value=\"".FilterColorGreen."\">".FilterColorGreen."</option>";
echo "<option".(($color==FilterColorMediumBlue)?"option selected=\"selected\" ":"")."value=\"".FilterColorMediumBlue."\">".FilterColorMediumBlue."</option>";
echo "<option".(($color==FilterColorPaleBlue)?"option selected=\"selected\" ":"")."value=\"".FilterColorPaleBlue."\">".FilterColorPaleBlue."</option>";
echo "<option".(($color==FilterColorBlue)?"option selected=\"selected\" ":"")."value=\"".FilterColorBlue."\">".FilterColorBlue."</option>";
echo "<option".(($color==FilterColorDeepBlue)?"option selected=\"selected\" ":"")."value=\"".FilterColorDeepBlue."\">".FilterColorDeepBlue."</option>";
echo "<option".(($color==FilterColorDeepViolet)?"option selected=\"selected\" ":"")."value=\"".FilterColorDeepViolet."\">".FilterColorDeepViolet."</option>";
echo "</select>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=\"fieldname\">";
echo LangAddFilterField4;
echo "</td>";
echo "<td>";
echo "<input type=\"text\" class=\"inputfield\" maxlength=\"5\" name=\"wratten\" size=\"5\" value=\"".stripslashes($objFilter->getWratten($_GET['filter']))." />";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td class=\"fieldname\">".LangAddFilterField5."</td>";
echo "<td>";
echo "<input type=\"text\" class=\"inputfield\" maxlength=\"5\" name=\"schott\" size=\"5\" value=\"".stripslashes($objFilter->getSchott($_GET['filter']))." />";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td></td>";
echo "<td>";
echo "<input type=\"submit\" name=\"change\" value=\"".LangChangeFilterButton."\" />";
echo "<input type=\"hidden\" name=\"id\" value=\"".$_GET['filter']."\" />";
echo "</td>";
echo "<td></td>";
echo "</tr>";
echo "</table>";
echo "</form>";
echo "</div>";
?>
