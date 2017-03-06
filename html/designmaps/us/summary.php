<?php
	include_once 'inc/appconfig.inc.php';
	include_once 'inc/appfunctions.inc.php';
	include_once 'inc/reports.inc.php';
	include_once 'inc/input_params.inc.php';

	if (!strstr($TITLE, 'Report')) { $TITLE .= ' Summary Report'; }
	$HEAD_LAST = '
		<link rel="stylesheet" href="css/summary.css"/>
	';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/template/template.inc.php';
	$_data = loadResultXML();
?>
	<div id="controls">
		<a id="printlink" href="javascript:void(null);"
			onclick="window.print();">Print</a>
		<a id="longreport" href="report.php?<?php
			print htmlspecialchars($_SERVER['QUERY_STRING']); ?>"
			title="Click for full report">View Detailed Report</a>
	</div>

<?php
	$twig = setupTwigEnvironment($_edition);

	try {
		$template = $twig->loadTemplate('summary.html');
	} catch (Twig_Error_Loader $e) {
		$template = $twig->loadTemplate('base/summary.html');
	}

	$spectra = array();

	if (count($_data->spectrum) !== 0) {
		if (isset($_data->spectrum[0]['type'])) {
			foreach ($_data->spectrum as $spectrum) {
				$spectra[(string) $spectrum['type']] = $spectrum;
			}
		} else {
			$spectra['design'] = $_data->spectrum[0];
			$spectra['mce'] = $_data->spectrum[1];
		}
	}

	$_data->as = $_data->num('pga') * $_data->num('fpga');

	if ($_EDITION_VARIANT) {
		$variant_labels = array();
		foreach ($LABELS as $name => $contents) {
			$variant_labels[$name] = is_array($contents) ? $contents[$_EDITION_VARIANT] : $contents;
		}
	} else {
		$variant_labels = $LABELS;
	}

	$template->display(array(
		'today' => new DateTime(),
		'detailed_url' => 'report.php?' . $_SERVER['QUERY_STRING'],
		'data' => $_data,
		'spectra' => $spectra,
		'edition' => array(
			'name' => $_EDITION_NAME,
			'variant' => $_EDITION_VARIANT,
			'basis' => $_EDITION_BASIS
		),
		'SHOWN_SPECTRA' => (isset($SHOWN_SPECTRA) ? $SHOWN_SPECTRA : array('mce', 'design')),
		'LABELS' => $variant_labels,
		'GLOBALS' => $GLOBALS // TEMPORARY HACK - JPW 2011-11-04
	));
?>

<div id="disclaimer">
	Although this information is a product of the U.S. Geological Survey, we
	provide no warranty, expressed or implied, as to the accuracy of the data
	contained therein. This tool is not a substitute for technical
	subject-matter knowledge.
</div>
<script type="text/javascript">/* <![CDATA[ */
	// try {
	// 	if (document.domain.indexOf('usgs.gov') !== -1) {
	// 		document.domain = "usgs.gov";
	// 	}
	// } catch (e) { /* Ignore */ }

	try {
		window.opener.popup_succeeded();
	} catch(e) {
		try {console.log(e.stack);} catch (e1) {/*Ignore*/}
	}
/* ]]> */</script>
