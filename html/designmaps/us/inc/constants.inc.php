<?php
	include_once 'appconfig.inc.php';
	date_default_timezone_set('UTC');

	// Important globals
	$SHORT_SITE_CLASSES = array(
		-1 => 'None Supplied',
		'A',
		'B',
		'C',
		'D',
		'E',
		'F'
	);

	$SITE_CLASS_ARR = array(
		-1 => 'None Supplied',
		'Site Class A',
		'Site Class B',
		'Site Class C',
		'Site Class D',
		'Site Class E',
		'Site Class F'
	);

	$LONG_SITE_CLASSES = array(
		-1 => 'None Supplied',
		'Site Class A &ndash; &ldquo;Hard Rock&rdquo;',
		'Site Class B &ndash; &ldquo;Rock&rdquo;',
		'Site Class C &ndash; &ldquo;Very Dense Soil and Soft ' .
		'Rock&rdquo;',
		'Site Class D &ndash; &ldquo;Stiff Soil&rdquo;',
		'Site Class E &ndash; &ldquo;Soft Clay Soil&rdquo;',
		'Site Class F &ndash; &ldquo;Requires Site Response ' .
			'Analysis&rdquo;'
	);

	$SHORT_RISK_CATEGORIES = array(
		-1 => 'N/A',
		'I',
		'II',
		'III',
		'IV',
	);

	$RISK_CATEGORY_ARR = array(
		-1 => 'None Supplied',
		0 => 'I/II/III',
		3 => 'IV (e.g. essential facilities)'
	);

	$LONG_RISK_CATEGORIES = array(
		-1 => 'None Supplied',
		0 => 'Risk Category I/II/III',
		3 => 'Risk Category IV (e.g. essential facilities)'
	);

	$DCODES = array(
		'nehrp-2015' => array(
			'shortname' => 'Proposed for 2015 NEHRP',
			'name' => '2015 NEHRP Proposed Seismic Provisions',
			'basis' => 'USGS hazard data available in 2014',
			'phase' => PHASE_DEVEL,
		),
		'asce_41-2013' => array(
			'shortname' => '2013 ASCE 41',
			'name' => 'ASCE 41-13 Retrofit Standard',
			'basis' => 'USGS hazard data available in 2008',
			'phase' => PHASE_RELEASE,
		),
		'ibc-2012' => array(
			'shortname' => '2012/15 IBC',
			'name' => '2012/2015 International Building Code',
			'basis' => 'USGS hazard data available in 2008',
			'phase' => PHASE_RELEASE,
		),
		'asce-2010' => array(
			'shortname' => '2010 ASCE 7 (w/March 2013 errata)',
			'name' => 'ASCE 7-10 Standard',
			'basis' => 'USGS hazard data available in 2008',
			'phase' => PHASE_RELEASE,
		),
		'aashto-2009' => array(
			'shortname' => '2009 AASHTO',
			'name' => '2009 AASHTO Guide Specifications for LRFD Seismic Bridge Design',
			'basis' => 'USGS hazard data available in 2002',
			'phase' => PHASE_RELEASE,
		),
		'ibc-2009' => array(
			'shortname' => '2006/09 IBC',
			'name' => '2006/2009 International Building Code',
			'basis' => 'USGS hazard data available in 2002',
			'phase' => PHASE_RELEASE,
		),
		'nehrp-2009' => array(
			'shortname' => '2009 NEHRP',
			'name' => '2009 NEHRP Recommended Seismic Provisions',
			'basis' => 'USGS hazard data available in 2008',
			'phase' => PHASE_RELEASE,
		),
		'asce_41-2006' => array(
			'shortname' => '2006 ASCE 41',
			'name' => 'ASCE 41-06 Retrofit Standard',
			'basis' => 'USGS hazard data available in 2002',
			'phase' => PHASE_DEVEL,
		),
		'asce-2005' => array(
			'shortname' => '2005 ASCE 7',
			'name' => 'ASCE 7-05 Standard',
			'basis' => 'USGS hazard data available in 2002',
			'phase' => PHASE_RELEASE,
		),
		'nehrp-2003' => array(
			'shortname' => '2003 NEHRP',
			'name' => '2003 NEHRP Recommended Seismic Provisions',
			'basis' => 'USGS hazard data available in 2002',
			'phase' => PHASE_RELEASE,
		),
	);

	$DCODES = array_filter($DCODES, function ($dcode) use ($APP_PHASE) {
		return $APP_PHASE <= $dcode['phase'];
	});

	$VARIANTS = array(
		'asce_41-2013' => array(
			'BSE-2N',
			'BSE-1N',
			'BSE-2E',
			'BSE-1E',
			'Custom'
		),
		'asce_41-2006' => array(
			'BSE-1',
			'BSE-2'
		)
	);
	
	$g = '<span>(g)</span>';

	$PRECISION = 3; // Decimal precision for output

	// Projection parameters. PR uses a different projection
	$ALBERS_PARAMS = array(
		'US' => array(
			'scale'   => 28500000,
			'x0pixel' => 281,
			'y0pixel' => 206,
			'phi0'    => 37.5,
			'phi1'    => 29.5,
			'phi2'    => 45.5,
			'lambda0' => -95.0,
			'dpi'     => 75
		),
		'AK' => array(
			'scale'   => 20000000,
			'x0pixel' => 219,
			'y0pixel' => 219,
			'phi0'    => 60.0,
			'phi1'    => 55.0,
			'phi2'    => 65.0,
			'lambda0' => -160.0,
			'dpi'     => 75
		),
		'HI' => array(
			'scale'   => 8000000,
			'x0pixel' => 275,
			'y0pixel' => 180,
			'phi0'    => 20.5,
			'phi1'    => 18.0,
			'phi2'    => 23.0,
			'lambda0' => -157.5,
			'dpi'     => 150
		)
	);

	$MAP_OVERVIEW_PARAMS = array(
		'WORLD' => 'center=0,0&amp;zoom=0',
		'US'    => 'center=40,-97&amp;zoom=2',
		'AK'    => 'center=60,-165&amp;zoom=2',
		'HI'    => 'center=20.612,-157.281&amp;zoom=5',
		'PR'    => 'center=18.5,-66.5&amp;zoom=6'
	);
?>
