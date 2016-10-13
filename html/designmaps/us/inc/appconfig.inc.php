<?php
	include_once $_SERVER['DOCUMENT_ROOT'].'/template/static/functions.inc.php';

	function get_config($envvar) {
		return (
			isset($_SERVER['REDIRECT_' . $envvar])
			? $_SERVER['REDIRECT_' . $envvar]
			: (
				isset($_SERVER[$envvar])
				? $_SERVER[$envvar]
				: ''
			)
		);
	}


	define('PHASE_DEVEL', 0);
	define('PHASE_RELEASE', 1);
	// $APP_PHASE = array_search(get_config('APP_PHASE'), array('DEVEL', 'RELEASE'));

	// $APP_DATA_DIR = get_config('APP_DATA_DIR');
	// $APP_BASE_DIR = get_config('APP_BASE_DIR');

	// $APP_WEB_DIR = get_config('APP_WEB_DIR');
	// $APP_LIB_DIR = get_config('APP_LIB_DIR');
	// $APP_CNF_DIR = get_config('APP_CNF_DIR');

	// $APP_URL_PATH = get_config('APP_URL_PATH');

	$APP_VERSION = '3.0.0';

	$APP_PHASE = PHASE_RELEASE;
	$APP_WEB_DIR = '/var/www/html/designmaps/us';
	$APP_LIB_DIR = '/var/www/lib';

	$APP_URL_PATH = '/designmaps/us';

	$APP_MAPQUEST_KEY = 'Fmjtd%7Cluub2qa7n0%2Can%3Do5-9u7a9z';
