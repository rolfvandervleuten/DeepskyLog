<?php
// top_objects.php
// generates an overview of all observed objects and their rank 

$_GET['source']='top_objects';
require_once 'deepsky/content/data_get_objects.php';

$step=25;
echo"<div id=\"main\">";
echo"<h2>" . LangTopObjectsTitle . "</h2>";
list($min,$max)=$objUtil->printNewListHeader($_SESSION['Qobj'], $baseURL."index.php?indexAction=rank_objects", $min, $step, "");
$objObject->showObjects($link, 'Qobj', $min, $max, $myList, '', 1);
list($min,$max)=$objUtil->printNewListHeader($_SESSION['Qobj'], $baseURL."index.php?indexAction=rank_objects", $min, $step, "");
echo "</div>";

?>
