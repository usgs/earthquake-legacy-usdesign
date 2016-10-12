<?php
	if(!isset($TEMPLATE)) {
		ob_start();
		$BUFFERING = true;
		chdir('inc'); // The working directory is important
		include_once 'dataminer.inc.php';
		chdir('..');
		$xml_string = ob_get_clean();
	
		$xml = simplexml_load_string($xml_string);

		if(!function_exists("create_imgurl")) {
			/**
			 * This is a PHP implementation of the Javascript function. Make sure
			 * the Javascript and PHP implementations stay in sync!!!
			 */
			function create_imgurl($_spectrum, $_edition) {
				$url  = 'images/spectra.php?cht=lxy';
				$url .= '&amp;chg=10,20'; // Grid Background
				// Chart title
				$url  .= '&amp;chtt=' . (string) $_spectrum->name;
				$subscript = 'D';
				if (strpos((string) $_spectrum->name, 'RTE')) {
					if ($_edition == '0_0') {
						$subscript = 'R';
					} else {
						$subscript = 'M';
					}
				}
				$url .= '&amp;subscript=' . $subscript;
				$url .= '&amp;chxt=x,y';    // Label x/y axis
				$url .= '&amp;chco=990000'; // Chart color
				
				$xvals = array();
				$yvals = array();
				
				foreach($_spectrum->point as $point) {
					$idx = (int) $point['index'];
					$xvals[$idx] = (float) $point['x'];
					$yvals[$idx] = (float) $point['y'];
				}
				
				$minX = $_spectrum->min; $minX = $minX['x'];
				$maxX = $_spectrum->max; $maxX = $maxX['x'];

				$minY = $_spectrum->min; $minY = $minY['y'];
				$maxY = $_spectrum->max; $maxY = $maxY['y'];

				$url .= '&amp;chxr=0,'.$minX.','.$maxX.'|1,'.$minY.','.$maxY;
				$url .= '&amp;chd=t:'.implode(',',$xvals).'|'.implode(',',$yvals);
				$url .= '&amp;chds='.$minX.','.$maxX.','.$minY.','.$maxY;
				
				return $url;
			}
		}


		$imgurl = array();
		foreach($xml->spectrum as $spectrum) {
			array_push($imgurl, create_imgurl($spectrum, $_POST['designCode']));
		}


	
		$_GET = array(
			'title'    => $_POST['latitude'] . ', ' . $_POST['longitude'],
			'reportTitle' => $_POST['reportTitle'],
			'latitude' => (string) $xml->latitude,
			'longitude' => (string) $xml->longitude,
			'siteclass' => (string) $xml->siteclass,
			'ss' => (string) $xml->ss,
			's1' => (string) $xml->s1,
			'srs' => (string) $xml->srs,
			'sr1' => (string) $xml->sr1,
			'sds' => (string) $xml->sds,
			'sd1' => (string) $xml->sd1,
			'ssuh' => (string) $xml->ssuh,
			's1uh' => (string) $xml->s1uh,
			'ssd' => (string) $xml->ssd,
			's1d' => (string) $xml->s1d,
			'crs' => (string) $xml->crs,
			'cr1' => (string) $xml->cr1,
			'edition' => $_POST['designCode'],
			'gkey' => 'ABQIAAAAouVgKwJzoxAoM26Ci5QN2xT0fazSD1VpH7Mi_' .
		          'uflQ_dFOWTAeBSP85u4czcmAB0VMriQP1rL9NdgRg',
			'imgUrl' => $imgurl
		);
		// Because the numerical siteclass was given on the post.
		$_POST['siteclass'] = (string) $xml->siteclass;

		$PHP_SELF = $_SERVER['DOCUMENT_ROOT'] . '/design/tool/report.php';
		$SCRIPT_FILENAME = $_SERVER['DOCUMENT_ROOT'] . '/design/tool/report.php';

		include_once 'report.php';
	}
?>
