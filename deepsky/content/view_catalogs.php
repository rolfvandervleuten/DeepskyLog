<?php
global $baseURL;

echo "<script type=\"text/javascript\" src=\"" . $baseURL . "deepsky/content/view_catalogs.js\"></script>";
echo '<div id="catalogs" class="catalogs">';

// Show a drop-down with all catalogs
include_once $baseURL . "/lib/catalogs.php";
$objCatalog = new catalogs();
$catalogs = $objCatalog->getCatalogs();

echo '<select onchange="view_catalog(this.value);">';
foreach ($catalogs as $key => $value) {
  print '<option><value="' . $value . '">' . $value . '</a></option>';
}
echo '</select>';
echo '<br /><br />';
echo '<div id="view_catalogs_right" class="view_catalogs_right">';
echo LangClickToViewCatalogDetails;
echo '</div>';
echo '</div>';
?>
