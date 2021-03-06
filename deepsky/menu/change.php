<?php
// change.php
// menu which allows the user to add or change things in the database
global $inIndex, $loggedUser, $objUtil;

if ((! isset ( $inIndex )) || (! $inIndex))
	include "../../redirect.php";
elseif (! ($loggedUser))
	throw new Exception ( LangException001 );
elseif (! ($objUtil->checkAdminOrUserID ( $loggedUser )))
	throw new Exception ( LangException012 );
else
	menu_change ();
function menu_change() {
	global $baseURL, $loggedUser, $menuAddChange, $objUtil;
	if ($loggedUser) {
		reset ( $_GET );
		echo "<ul class=\"nav navbar-nav\">
			  <li class=\"dropdown\">
	       <a class=\"tour1 tour3\" href=\"http://" . $_SERVER ['SERVER_NAME'] . $_SERVER ["REQUEST_URI"] . "#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">" . LangChangeMenuTitle . "<b class=\"caret\"></b></a>";
		echo " <ul class=\"dropdown-menu\">";
		echo "  <li><a href=\"" . $baseURL . "index.php?indexAction=quickpick&titleobjectaction=Zoeken&source=quickpick&myLanguages=true&object=&newObservationQuickPick=Nieuwe waarneming\">" . LangChangeMenuItem2 . "</a></li>";
		echo "  <li class=\"disabled\">─────────────────</li>";
		echo "  <li><a data-toggle=\"modal\" data-target=\"#addList\">" . LangAddNewList . "</a></li>";
		echo "  <li><a href=\"" . $baseURL . "index.php?indexAction=add_session\">" . LangChangeMenuItem9 . "</a></li>";
		echo "  <li class=\"disabled\">─────────────────</li>";
		echo "  <li><a href=\"" . $baseURL . "index.php?indexAction=add_instrument\">" . LangChangeMenuItem3 . "</a></li>";
		echo "  <li><a href=\"" . $baseURL . "index.php?indexAction=add_location\">" . LangChangeMenuItem4 . "</a></li>";
		echo "  <li><a href=\"" . $baseURL . "index.php?indexAction=add_eyepiece\">" . LangChangeMenuItem6 . "</a></li>";
		echo "  <li><a href=\"" . $baseURL . "index.php?indexAction=add_filter\">" . LangChangeMenuItem7 . "</a></li>";
		echo "  <li><a href=\"" . $baseURL . "index.php?indexAction=add_lens\">" . LangChangeMenuItem8 . "</a></li>";
		echo "  <li class=\"disabled\">─────────────────</li>";
		echo "  <li><a href=\"" . $baseURL . "index.php?indexAction=add_object\">" . LangChangeMenuItem5 . "</a></li>";
		echo " </ul>";
		echo "</li>
			  </ul>";
	}
}
?>
