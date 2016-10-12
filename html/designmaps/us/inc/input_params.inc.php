<?php
	// Import globals
	include_once 'appconfig.inc.php';
	include_once 'constants.inc.php';
	include_once 'appfunctions.inc.php';
	include_once 'FaFvCalc.class.php';
	include_once 'FaFvCalcNEHRP2015.class.php';

	// Read in the URL parameters.
	$_title     = param('title', false);
	$_report    = param('reportTitle', false);
	$_latitude  = doubleval(param('latitude', false));
	$_longitude = doubleval(param('longitude', false));
	$_siteclass = param('siteclass', 3);
	$_riskcategory = param('riskcategory', -1);
	$_custom_pe50 = param('pe50', 0);

	$_edition   = param('edition', 'nehrp-2009');
	$_dcode_info = $DCODES[$_edition];
	# Simple check, also protects us against filename-injection attacks
	if (!isset($_dcode_info)) {
		die("Internal error: unknown edition $_edition");
	}
	$_EDITION_NAME = $_dcode_info['name'];
	$_EDITION_BASIS = $_dcode_info['basis'];
	$_variant = intval(param('variant', 0));
	if (isset($VARIANTS[$_edition])) {
		if (isset($VARIANTS[$_edition][$_variant])) {
			$_EDITION_VARIANT = $VARIANTS[$_edition][$_variant];
		} else {
			die("Internal error: unknown variant $_variant of $_edition");
		}
	} else {
		$_EDITION_VARIANT = '';
	}
	include_once 'dcodes/' . $_edition . '/info.inc.php';

	$_region    = getRegionName($_latitude, $_longitude);

	// Calculated values
	
	$TEMPLATE = param('template', 'default');
	$TITLE = 'Design Maps';
?>
