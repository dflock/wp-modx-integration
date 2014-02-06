<?php

define("IN_PARSER_MODE", "true");
define("IN_MANAGER_MODE", "false");

if (!defined('MODX_API_MODE')) {
    define('MODX_API_MODE', false);
}

// initialize the variables prior to grabbing the config file
$database_type = '';
$database_server = '';
$database_user = '';
$database_password = '';
$dbase = '';
$table_prefix = '';
$base_url = '';
$base_path = '';
$manager_path = '../manager';

// get the required includes
include_once($manager_path.'/includes/config.inc.php');

// start session 
#startCMSSession();

// initiate a new document parser
$database_type = 'mysql';
include_once($manager_path.'/includes/document.parser.class.inc.php');
$modx = new DocumentParser;


// set some parser options
$modx->minParserPasses = 1; // min number of parser recursive loops or passes
$modx->maxParserPasses = 10; // max number of parser recursive loops or passes
$modx->dumpSQL = false;
$modx->dumpSnippets = false;
$modx->tstart = $tstart; // feed the parser the execution start time

// Debugging mode:
$modx->stopOnNotice = false;

#// execute the parser if index.php was not included
#if (!MODX_API_MODE) {
#    $modx->executeParser();
#}

// Start-up MODx
$modx->db->connect();
$modx->getSettings();

$modx->documentIdentifier = '86'; //ID of the Weblink Resource to the Blog shows its parent as active in the nav.
$modx->documentObject['pagetitle'] = get_bloginfo('name'); //sets the pagetitle for the titlebar and the content

// Get plugin local config
include_once('modx_config.php');
// Selectively override global modx config from plugin local version
$modx->config['base_url'] = $modx_config['base_url'];
$modx->config['site_url'] = $modx_config['base_url'];
?>
