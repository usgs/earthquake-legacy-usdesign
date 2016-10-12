<?php

/**
 * template.inc.php
 * common template tasks, include this file to use the template
 *
 * @author jmfee
 * @version 2.1 2008/01/24
 */
if (!defined("DOCUMENT_ROOT")) {
	if (isset($_SERVER['DOCUMENT_ROOT'])) {
		define("DOCUMENT_ROOT", $_SERVER['DOCUMENT_ROOT']);
	} else {
		define("DOCUMENT_ROOT", "/var/www/html");
	}
}

date_default_timezone_set('UTC');

if (!defined("TEMPLATE_DIR")) {
	define("TEMPLATE_DIR", $_SERVER['DOCUMENT_ROOT'] . '/template');
} else {
	//template already included
	return;
}

if (!isset($HTTPS)) {
	if (
			(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
			|| (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
	) {
		$HTTPS = true;
	} else {
		$HTTPS = false;
	}
}



//global functions
include_once TEMPLATE_DIR . '/static/functions.inc.php';



if (!isset($TEMPLATE_INCLUDE_PATHS)) {
	$TEMPLATE_INCLUDE_PATHS = array();
}
// load template include paths
$_paths = findFileInPaths(
	"_template", // directory should be named "_template"
	getRequestPaths(), // use all directories in request path
	false // return all found directories
	);
foreach ($_paths as $path) {
	$TEMPLATE_INCLUDE_PATHS[] = $path;
}
// other alterative include locations
$TEMPLATE_INCLUDE_PATHS[] = DOCUMENT_ROOT . '/template_override';
$TEMPLATE_INCLUDE_PATHS[] = DOCUMENT_ROOT . '/../includes';


if (!function_exists("getTemplateFile")) {
	function getTemplateFile($path) {
		global $TEMPLATE_INCLUDE_PATHS;

		$override = findFileInPaths(
				$path, // file to find
				$TEMPLATE_INCLUDE_PATHS, // template override paths
				true);
		if (file_exists($override)) {
			return $override;
		} else {
			return TEMPLATE_DIR . '/' . $path;
		}
	}
}


// give vhost opportunity to include own functions
$site_functions = getTemplateFile('static/site_functions.inc.php');
if (file_exists($site_functions)) {
	include_once $site_functions;
}

// give vhost opportunity to set own defaults
$site_config = getTemplateFile('static/site_config.inc.php');
if (file_exists($site_config)) {
	include_once $site_config;
}



//template variables
if (!isset($BODYCLASS)) { $BODYCLASS = ''; }
if (!isset($BUFFER)) { $BUFFER = false; }
if (!isset($CONTACT) || $CONTACT === '')
  { $CONTACT = 'sis_eq_questions@usgs.gov'; }
if (!isset($CONTENTCLASS)) { $CONTENTCLASS = ''; }
if (!isset($ENCODING)) { $ENCODING = 'utf-8'; }
if (!isset($HEAD)) { $HEAD = ''; }
if (!isset($SCRIPT_FILENAME)) { $SCRIPT_FILENAME = $_SERVER['SCRIPT_FILENAME']; }
if (!isset($SCRIPTS)) { $SCRIPTS = ''; }
if (!isset($STYLESHEETS)) { $STYLESHEETS = '';}
if (!isset($TEMPLATE)) { $TEMPLATE = 'default'; }
if (!isset($WIDGETS)) { $WIDGETS = ''; }
$DISCLAIMERS = array_merge(
	array(
				'<img src="/template/images/small_offsite_arrow.gif" ' .
						'alt="Offsite Link" title="Offsite Link"> ' .
				'<a href="http://www.doi.gov/disclaimer.cfm" ' .
						'title="Department of the Interior - Disclaimer of ' .
								'Liability and Endorsement">DOI</a>' .
				' and ' .
				'<a href="http://www.usgs.gov/laws/info_policies.html" ' .
						'title="USGS - Information Policies">USGS</a> ' .
				'link policies apply.'
	),
	(isset($DISCLAIMERS)&&is_array($DISCLAIMERS))?$DISCLAIMERS:array()
);

if (!isset($TITLE)) { $TITLE = ''; }

// variables that control the addthis feature...
// $SHARE - whether to show the share links at the bottom of the page (default true).
if (!isset($SHARE)) { $SHARE = true; }
// $TWEET_FORMAT - default format for sharing tweets... (2011-03-07: not currently implemented; all Twitter links follow default format)
if (!isset($TWEET_FORMAT)) { $TWEET_FORMAT = "{{title}} {{url}} via @usgs"; }


//set after SCRIPT_FILENAME is defined
if (!isset($MODIFIED)) { $MODIFIED = filemtime($SCRIPT_FILENAME); } else {
	//send modified header...

	header('Last-Modified: ' . str_replace("+0000", "GMT", date("r", $MODIFIED)));
}


// new template compatibility
if (!isset($FOOT)) { $FOOT = ''; }
if (isset($NAVIGATION)) {
	if ($NAVIGATION === false) {
		// implies one column
		$TEMPLATE = 'onecolumn';
	} else if ($NAVIGATION === true) {
		// implies two-column
		$TEMPLATE = 'default';
	}
}


//include the layout
$TEMPLATE_FILE = getTemplateFile('/templates/template.' . $TEMPLATE . '.inc.php');
if (!file_exists($TEMPLATE_FILE)) {
	$TEMPLATE = 'default';
	$TEMPLATE_FILE = getTemplateFile('/templates/template.' . $TEMPLATE . '.inc.php');
}
include $TEMPLATE_FILE;


//keep content from being displayed twice
exit(0);
