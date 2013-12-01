<?php
define('MODX_API_MODE', true);
include_once(dirname(__FILE__)."/index.php");

$modx->db->connect();
if (empty ($modx->config)) {
    $modx->getSettings();
}

$actionID = 1658800; //ID документа у которого изменился alias

require_once(dirname(__FILE__).'/'.MGR_DIR.'/includes/URIManager.class.php');
$uriManager = new URIManager($modx);
$total = $uriManager->update($actionID);
echo "Обновлено записей: ".$total;