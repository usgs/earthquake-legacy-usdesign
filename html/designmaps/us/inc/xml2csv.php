<?php
	$filedir = '../output';
	include_once $_SERVER['DOCUMENT_ROOT'].'/template/static/functions.inc.php';
	include_once 'constants.inc.php';
	include_once 'reports.inc.php';
	$fileid = param('fileid', false);

	if (!$fileid) {
		header('Location: /notfound.php');
	}

	$file = $filedir . '/' . $fileid . '.xml';

	$xml = simplexml_load_file($file);
	$edition = $xml['edition'];
	include_once 'dcodes/' . $edition . '/info.inc.php';

	// Tell the browser we are sending a CSV file so it opens the "save" dialog
	header('Content-Type: text/csv');
	// Tell the browser what the name of the file is called...
	header('Content-Disposition: attachment; filename=results.csv');

	$columns = array(
		// Output these specially, so they get the full precision
		'Lat' => function ($location) { return $location->latitude; },
		'Long' => function ($location) { return $location->longitude; },
		'SC' => 'siteclass',
		'RC' => function ($location) use ($RISK_CATEGORY_ARR) {
			if ($location->riskcategory['index'] == -1) {
				return 'Undefined';
			} else {
				return $RISK_CATEGORY_ARR[(int) $location->riskcategory['index']];
			}
		},
		'S_SUH' => 'ssuh',
		'S_1UH' => 's1uh',
		'C_RS' => 'crs',
		'C_R1' => 'cr1',
		'S_SD' => 'ssd',
		'S_1D' => 's1d',
		'S_S' => 'ss',
		'S_1' => 's1',
		'F_a' => 'fa',
		'F_v' => 'fv',
		'S_MS' => 'srs',
		'S_M1' => 'sr1',
		'S_DS' => 'sds',
		'S_D1' => 'sd1',
		'T_L' => 'tl',
		'PGA' => 'pga',
		'F_PGA' => 'fpga',
		'PGA_M' => 'pgam',
		'SDC' => 'designcategory',
	);

	if (isset($BATCH_COLUMNS)) {
		// Set any changed or added columns from the design code, removing any
		// that it set to NULL
		$columns = array_merge(array_diff_key($columns, $BATCH_COLUMNS), array_filter($BATCH_COLUMNS));
	}

	$output = fopen('php://output', 'w');
	ini_set('error_reporting', E_ALL);

	// Print CSV header row
	fputcsv($output, array_keys($columns));

	// Loop over all the points and return the batch results
	foreach($xml->location as $location) {
		fputcsv($output, array_map(function($elem) use ($location) {
			if (is_callable($elem)) {
				return (string) $elem($location);
			} else if (is_numeric((string) $location->{$elem})) {
				return dataFormat($location->{$elem}, 3);
			} else {
				return (string) $location->{$elem};
			}
		}, $columns));
	}
?>
