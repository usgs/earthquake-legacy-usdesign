<?php
	//ini_set('error_reporting', -1);
	include_once 'inc/appconfig.inc.php';
	include_once 'inc/appfunctions.inc.php';
	include_once 'inc/input_params.inc.php';
	include_once 'inc/FaFvCalc.class.php';
	include_once 'inc/reports.inc.php';

	if (!strstr($TITLE, 'Report')) {
		$TITLE .= ' Detailed Report';
	}

	$HEAD_LAST = '
		<link rel="stylesheet" href="css/report.css"/>
		<link rel="stylesheet" href="css/fafv.css"/>
	';

	$FOOT = '
		<script src="js/citations.js"></script>
	';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/template/template.inc.php';

	$_data = loadResultXML();


	$spectra = array();

	if ($_data->spectrum[0]['type']) {
		foreach ($_data->spectrum as $spectrum) {
			$spectra[(string) $spectrum['type']] = $spectrum;
		}
	} else {
		$spectra['design'] = $_data->spectrum[0];
		$spectra['mce'] = $_data->spectrum[1];
	}

	if ($_EDITION_VARIANT) {
		$variant_labels = array();
		foreach ($LABELS as $name => $contents) {
			$variant_labels[$name] = is_array($contents) ? $contents[$_EDITION_VARIANT] : $contents;
		}
		$LABELS = $variant_labels;
	}

	if (!isset($BUFFERING) || !$BUFFERING) {
?>
<div id="controls">
	<a id="printlink" href="javascript:void(null);"
		onclick="window.print();">Print</a>
	<a href="summary.php?<?php
		print htmlspecialchars($_SERVER['QUERY_STRING']); ?>"
		title="Click to view short report" id="shortreport">
		View Summary Report
	</a>
</div>
<?php } ?>

<h2>
	<?php
		print $_EDITION_NAME;
		if ($_EDITION_VARIANT) {
			print ", $_EDITION_VARIANT";
		}

		if ($_custom_pe50) {
			print ", $_custom_pe50% in 50 year values";
		}
	?>
    (<?php print prettyLat($_latitude) . ', ' . prettyLng($_longitude); ?>)
</h2>

<p id="inputs">
	<?php print $LONG_SITE_CLASSES[param('siteclass')] ?><?php if (param('riskcategory', -1) != -1) { ?>,
		<?php print $LONG_RISK_CATEGORIES[param('riskcategory', -1)] ?>
	<?php } ?>
</p>

<div class="page">
<?php
	include_once 'inc/dcodes/' . $_edition . '/report.inc.php';
?>
</div>
