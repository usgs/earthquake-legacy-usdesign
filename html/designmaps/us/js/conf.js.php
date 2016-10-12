<?php
	include_once '../inc/appconfig.inc.php';
	header('Content-Type: text/javascript');
?>

var BASE_URL = window.location.protocol + '//' + window.location.host + 
		'<?php print $APP_URL_PATH; ?>';
