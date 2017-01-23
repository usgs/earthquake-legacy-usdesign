<?php
	include_once 'appconfig.inc.php';

	ob_start();
	include_once 'data-api.inc.php';
	$result = ob_get_contents();
	ob_end_clean();

	$protocol = (
		(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'Off') ||
		(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
				$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		? 'https://' : 'http://');

  $node = isset($CONFIG['CROSS_NODE_PREFIX']) ?
  		$CONFIG['CROSS_NODE_PREFIX'] : '';
	$hostname = $protocol . $CONFIG['SERVER_NAME'] . ':' .
			$CONFIG['SERVER_PORT'] . $node;

	$OUT_DIR = "${APP_WEB_DIR}/output";
	$result_id = uniqid('single.', true);
	$gzout = gzopen("$OUT_DIR/${result_id}.xml.gz", 'wb');

	gzwrite($gzout, $result);
	gzclose($gzout);

	// Don't send a header if we are buffering (i.e. no javascript version)
	if(!isset($BUFFERING) || !$BUFFERING) {
		// We're not buffing, so send the JSON info
		header('Content-Type: application/json');
		if ($errors) {
			print json_encode(array('error' => $errors));
		} else {
			print str_replace('\/', '/', json_encode(array(
				'result_id' => $result_id,
				'source_host' => $hostname . $APP_URL_PATH
			)));
		}
	} else {
		header('HTTP/1.1 304 Found');
		header("Location: ${hostname}${APP_URL_PATH}/output/${result_id}.xml.gz");
	}
?>
