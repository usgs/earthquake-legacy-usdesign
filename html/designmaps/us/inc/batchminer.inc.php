<?php
	include_once 'appconfig.inc.php';
	include_once 'constants.inc.php';
	if ( param('vanilla') == 'true' ) {
		$TITLE = 'Batch Results';
		include_once $_SERVER['DOCUMENT_ROOT'] . '/template/template.inc.php';
	}
	$LOG_DIR  = "${APP_LIB_DIR}/logs";
	$OUT_DIR  = "${APP_WEB_DIR}/output";
	$MAX_RECORDS = 100;

	$EDITIONS = array('nehrp-1997', 'asce-1998', 'ibc-2000', 'nehrp-2000',
	'asce-2002', 'ibc-2003', 'ibc-2004', 'nehrp-2003', 'asce-2005', 'ibc-2006',
	'ibc-2009', 'asce_41-2006', 'asce_41-2006', 'nehrp-2009', 'asce-2010',
	'ibc-2012', 'aashto-2009', 'asce_41-2013', 'asce_41-2013', 'asce_41-2013',
	'asce_41-2013', 'asce_41-2013', 'nehrp-2015'
	);
	$edition = param('designCode', 'nehrp-2009');
	$edition_idx = array_search($edition, $EDITIONS);
	$variant = intval(param('designCodeVariant', '0'));
	if ($edition_idx === FALSE) die("Unknown edition $edition");

	$edition_idx += $variant;

	$cmd_stub = '/usr/bin/java ' .
			'-Djava.security.egd=file:///dev/urandom ' .
			"-jar ${APP_LIB_DIR}/DesignTool2009.jar %s %d";
	$descriptors = array(
		0 => array('pipe', 'r'), // STDIN
		1 => array('pipe', 'w'), // STDOUT
		2 => array('pipe', 'w')  // STDERR
	);

	$lines = file($_FILES['batchfile']['tmp_name'], FILE_IGNORE_NEW_LINES);

	$error = '';

	$XML =  '<?xml version="1.0" encoding="UTF-8"?>';

	$XML .= '<batch_output';
	if(count($lines) < $MAX_RECORDS) {

		$command = sprintf($cmd_stub, $_FILES['batchfile']['tmp_name'], $edition_idx);
		$process = proc_open($command, $descriptors, $pipes, $APP_LIB_DIR);
		if (is_resource($process)) {
			fclose($pipes[0]);
			$output = stream_get_contents($pipes[1]);
			$error_output = stream_get_contents($pipes[2]);
			fclose($pipes[1]);
			fclose($pipes[2]);

			$return = proc_close($process);

			if ($error_output) {
				$error = $error_output;
			} else {
				$XML .= ' status="' . count($lines) . '" edition="' . $edition . '">';
				$XML .= $output;
			}
		} else {
			$error = 'Could not start ' . $command;
		}
	} else {
		$error = ' Too many records in batch file.' .
		        " Limit is ${MAX_RECORDS} locations per batch, " . count($lines) .
				" were sent.";
	}

	$XML .= '</batch_output>';

	$id = uniqid('batch.', true);
	$fp = fopen("${OUT_DIR}/${id}.xml", 'w');
	fwrite($fp, $XML);
	fclose($fp);
?>

<a href="<?php print $APP_URL_PATH; ?>/output/<?php print $id; ?>.xml" title="XML Format"
	target="_blank">XML Format</a><br />
<a href="<?php print $APP_URL_PATH; ?>/inc/xml2csv.php?fileid=<?php print $id; ?>"
	title="Excel Format">Excel File Format</a>

<script type="text/javascript"> /* <![CDATA[ */

try {
	<?php if ($error) {
		echo 'self.top.showBatchError("';
		echo addcslashes($error, "\"\n");
		echo '");';
	} else {
		echo "self.top.showBatchResults(";
		echo "'$id','";
		echo $_FILES['batchfile']['name'], "',";
		echo time() * 1000, ",'";
		echo $DCODES[$edition]['shortname'], "','";
		echo isset($VARIANTS[$edition]) ? $VARIANTS[$edition][$variant] : '', "'";
		echo ");";
	} ?>
}
catch (e) { /* Ignore it. */ }

/* ]]> */ </script>
