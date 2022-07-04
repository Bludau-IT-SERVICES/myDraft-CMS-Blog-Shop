<?php
session_start();			
$path = dirname(__FILE__);

include_once($path.'/include/inc_basic-functions.php');
include_once($path.'/include/inc_config-data.php');
#error_reporting(E_ALL);
#ini_set('display_errors', TRUE); // evtl. hilfreich

#########################################################################
# Includes für MyDraft und Smarty
#########################################################################

define('MYDRAFT_DIR', $_SERVER['DOCUMENT_ROOT'].'/');
# Smarty Installationsverzeichnis
define('SMARTY_DIR', MYDRAFT_DIR.'framework/smarty/');
# Initalisierung von Mydraft 
include(MYDRAFT_DIR . 'libs/mydraft.setup.php');

# Klasse laden
$core_domain_info_ary = getDomainInfo();

$_SESSION['domain_id'] = $core_domain_info_ary['domain_id'];
$_SESSION['template_folder'] = "/".$core_domain_info_ary['template_folder'];  // Wird pro Domain festgelegt


$mydraft = new Mydraft;
$mydraft->tpl->assign('target_url', urldecode($_GET['url']));
$mydraft->tpl->assign('domain_name', $_SERVER['HTTP_HOST']);
$mydraft->tpl->assign('CORE_PLATTFORMNAME', CORE_SERVER_PLATTFORM_NAME);

$mydraft->displayCMSPage('frameset.tpl','frameset','true');
?>