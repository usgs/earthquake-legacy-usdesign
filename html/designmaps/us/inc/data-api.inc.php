<?php
	include_once 'appconfig.inc.php';

	function fail_xml($message) {
		global $errors;
		$errors = $message;

		print '<?xml version="1.0" encoding="UTF-8"?><error>' . htmlspecialchars($message) . '</error>';
	}

	$EDITIONS = array('nehrp-1997', 'asce-1998', 'ibc-2000', 'nehrp-2000',
	'asce-2002', 'ibc-2003', 'ibc-2004', 'nehrp-2003', 'asce-2005', 'ibc-2006',
	'ibc-2009', 'asce_41-2006', 'asce_41-2006', 'nehrp-2009', 'asce-2010',
	'ibc-2012', 'aashto-2009', 'asce_41-2013', 'asce_41-2013', 'asce_41-2013',
	'asce_41-2013', 'asce_41-2013', 'nehrp-2015'
	);

	$latitude  = doubleval(param('latitude', ''));
	$longitude = doubleval(param('longitude', ''));
	$siteclass = intval(param('siteclass', '0'));
	$riskcategory = intval(param('riskcategory', '-1'));
	$variant = intval(param('variant', '0'));
	$pe50 = intval(param('pe50', '0'));
	$edition = array_search(param('edition', 'nehrp-2009'), $EDITIONS);

	header('Content-Type: application/xml');

	if ($edition === FALSE) {
		fail_xml("Unknown edition $edition");
		return;
	}

	$edition += $variant;
	error_log("Using command: " .  "$latitude $longitude $siteclass $riskcategory $edition $pe50");

	$result = proc_open("/usr/bin/java " .
		"-Djava.security.egd=file:///dev/urandom " .
		"-jar ${APP_LIB_DIR}/DesignTool2009.jar " .
			"$latitude $longitude $siteclass $riskcategory $edition $pe50",
		array(
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w')
		),
		$pipes,
		NULL
	);

	$errors = '';

	if (is_resource($result)) {
		stream_set_blocking($pipes[2], FALSE);

		$output = stream_get_contents($pipes[1]);
		$errors = stream_get_contents($pipes[2]);

		fclose($pipes[1]);
		fclose($pipes[2]);
		proc_close($result);

		if ($errors) {
			fail_xml($errors);
		} else {
			print $output;
		}
	} else {
		fail_xml('Could not run backend process');
	}
?>
