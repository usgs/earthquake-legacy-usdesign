<?php
	include_once 'appconfig.inc.php';
	include_once 'libs/Twig/Autoloader.php';
	Twig_Autoloader::register();

	function dataFormat($num, $prec = null) {
		global $PRECISION;
		$prec = is_null($prec) ? $PRECISION : $prec;

		return number_format(round(doubleval($num), $prec), $prec);
	}

	function twig_filter_numfmt($number, $type = null) {
		switch ($type) {
			case 'lat': return prettyLat(doubleval($number));
			case 'lng': return prettyLng(doubleval($number));
			// Otherwise, assume it's a precision
			default: return dataFormat(doubleval($number), $type);
		}
	}

	//function twig_func_usd_marked_map($lat, $lng, $size, $type = 'zoomed') {
		//global $GMAP_PARAMS;

		//$base_url = 'http://maps.google.com/staticmap?';
		//$params = array(
			//'key' => GMap2::getGoogleKey(),
			//'markers' => $lat . ',' . $lng . ',' . (
				//$type == 'overview' ? 'tinygreen' : 'greenx'
			//),
			//'size' => $size,
		//);

		//if ($type == 'overview') {
			//$base_url .= html_entity_decode($GMAP_PARAMS[getRegionName($lat, $lng)]) . '&';
		//} else {
			//$params['center'] = $lat . ',' . $lng;
			//$params['zoom'] = 11;
		//}

		//return $base_url . http_build_query($params);
	//}

	/*
	function twig_func_usd_marked_map($lat, $lng, $size, $type = 'zoomed') {
		global $APP_MAPQUEST_KEY, $MAP_OVERVIEW_PARAMS;

		$base_url = 'http://open.mapquestapi.com/staticmap/v4/getmap?';
		$params = array(
			'key' => urldecode($APP_MAPQUEST_KEY),
			'pcenter' => $lat . ',' . $lng,
			'size' => $size,
		);

		if ($type == 'overview') {
			$params['scalebar'] = 'false';
			$base_url .= html_entity_decode($MAP_OVERVIEW_PARAMS[getRegionName($lat, $lng)]) . '&';
		} else {
			$params['center'] = $lat . ',' . $lng;
			$params['zoom'] = 11;
		}

		return $base_url . http_build_query($params);
	}
	*/

	function twig_func_usd_marked_map ($lat, $lng, $size, $type = 'zoomed') {
		$base_url = 'http://services.arcgisonline.com/arcgis/rest/services' .
				'/NatGeo_World_Map/MapServer/export?';

		if ($type === 'zoomed') {
			$minlat = $lat - 0.1;
			$maxlat = $lat + 0.1;
			$minlng = $lng - 0.1;
			$maxlng = $lng + 0.1;
		} else {
			$minlat = 24.6;
			$maxlat = 50.0;
			$minlng = -125.0;
			$maxlng = -65.0;
		}

		$params = array(
			'bboxSR' => '4326',
			'format' => 'jpg',
			'f'      => 'image',
			'size'   => $size,
			'bbox'   => "${minlng},${minlat},${maxlng},${maxlat}"
		);

		return $base_url . http_build_query($params);
	}

	function twig_func_usd_spectrum($spectrum, $size, $options = null) {
		list($width, $height) = explode('x', $size);
		ob_start();
		outputSpectrumChart(
			$spectrum,
			$width,
			$height,
			FALSE,
			(isset($options) ? $options : array())
		);
		return ob_get_clean();
	}

	function setupTwigEnvironment($edition) {
		global $APP_WEB_DIR;

		$loader = new Twig_Loader_Filesystem(array(
			"$APP_WEB_DIR/inc/dcodes/$edition",
			"$APP_WEB_DIR/inc/dcodes"
		));

		$twig = new Twig_Environment($loader, array('debug' => true));
		$twig->addExtension(new Twig_Extension_Debug());
		$twig->addFunction(
			'param',
			new Twig_Function_Function('param')
		);
		$twig->addFunction(
			'usd_marked_map',
			new Twig_Function_Function('twig_func_usd_marked_map')
		);
		$twig->addFunction(
			'usd_spectrum',
			new Twig_Function_Function(
				'twig_func_usd_spectrum',
				array('is_safe' => array('all'))
			)
		);
		$twig->addFunction(
			'to_float',
			new Twig_Function_Function(
				'floatval',
				array('is_safe' => array('all'))
			)
		);
		$twig->addFilter(
			'numfmt',
			new Twig_Filter_Function(
				'twig_filter_numfmt',
				array('is_safe' => array('all'))
			)
		);

		return $twig;
	}

	class ResultsXMLElement extends SimpleXMLElement {
		public function num($property) {
			return doubleval($this->{$property});
		}

		public function fmt($property, $precision = null) {
			return dataFormat($this->num($property), $precision);
		}
	}

	function loadResultXML() {
		global $APP_WEB_DIR;

		$OUT_DIR = "${APP_WEB_DIR}/output";
		$_result_id = param('resultid', false);
		if (!$_result_id || !preg_match('/^[A-Za-z0-9.]+$/', $_result_id)) {
			die('Invalid result ID');
		}

		return simplexml_load_string(
			file_get_contents("compress.zlib://${OUT_DIR}/${_result_id}.xml.gz"),
			'ResultsXMLElement'
		);
	}

	function outputSpectrumChart($spectrum, $width, $height, $detailed = FALSE, $options = null) {
		global $APP_URL_PATH, $LABELS;
		if ($options == null) $options = array();

		$name = str_replace(
			'Site Modified Sa Vs T',
			$LABELS['mod_spec'] . ' Response Spectrum',
			str_replace(
				$LABELS['dsn_spec'] . ' Spectrum Sa Vs T',
				$LABELS['dsn_spec'] . ' Response Spectrum',
				$spectrum->name
			)
		);

		$minX = $spectrum->min['x'];
		$minY = $spectrum->min['y'];
		$maxX = $spectrum->max['x'];
		$maxY = $spectrum->max['y'];

		$xvals = array();
		$yvals = array();

		foreach ($spectrum->point as $point) {
			$xvals[intval($point['index'])] = doubleval($point['x']);
			$yvals[intval($point['index'])] = doubleval($point['y']);
		}

		$params = array(
			'chg' => '10,20',
			'cht' => 'lxy',
			'chs' => $width . 'x' . $height,
			'chtt' => $name,
			'subscript' => (
				strpos($name, 'MCE') === FALSE ?
				'D' :
				'M'
			),
			'chxt' => 'x,y',
			'chco' => '000000',
			'chxr' => "0,$minX,$maxX|1,$minY,$maxY",
			'chd' => 't:' . implode(',', $xvals) . '|' . implode(',', $yvals),
			'chds' => "$minX,$maxX,$minY,$maxY"
		);

		if ($options) $params = array_merge($params, $options);

		if ($detailed || isset($options['detailed'])) {
			unset($params['chtt']);
			$params['detailed'] = 'true';
		}

		printf(
			'<img src="%s/images/spectra.php?%s" width="%d" height="%d" ' .
			'class="chart" alt="%s" />',
			$APP_URL_PATH,
			http_build_query($params),
			$width,
			$height,
			$name . ' (' . $spectrum['index'] . ')'
		);
	}

	function new_page() {
		print '</div><div class="page">';
	}

	function last_page() {
		print '</div><div class="last-page">';
	}

	function map_link($type) {
		global $MAP_URL, $MAP_TEXT, $FIGURES, $_region;
		print '<a class="citation" href="';

		if (isset($FIGURES['special_filenames']) &&
			isset($FIGURES['special_filenames'][$FIGURES[$type][$_region]])) {
			print $FIGURES['special_filenames'][$FIGURES[$type][$_region]];
		} else {
			printf($MAP_URL, $FIGURES[$type][$_region]);
		}
		print '">';
		printf($MAP_TEXT, $FIGURES[$type][$_region]);
		print '</a>';
	}

?>
