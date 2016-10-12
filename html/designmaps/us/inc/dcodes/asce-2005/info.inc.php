<?php
$LABELS = array(
		'ss_raw' => 'S<sub>S</sub>',
		's1_raw' => 'S<sub>1</sub>',
		'ss_mod' => 'S<sub>MS</sub>',
		's1_mod' => 'S<sub>M1</sub>',
		'ss_dsn' => 'S<sub>DS</sub>',
		's1_dsn' => 'S<sub>D1</sub>',
		'mod_spec' => 'MCE',
		'dsn_spec' => 'Design',
		'rc' => 'Occupancy Category',
);

$RISK_CATEGORY_ARR = array(
	-1 => 'None Supplied',
	'I/II/III',
	'II',
	'III',
	'IV',
);

$LONG_RISK_CATEGORIES = array(
	-1 => 'None Supplied',
	'Occupancy Category I/II/III',
	'Occupancy Category II',
	'Occupancy Category III',
	'Occupancy Category IV',
);

$BATCH_COLUMNS = array(
	'OC' => function ($location) use ($RISK_CATEGORY_ARR) {
		if ($location->riskcategory['index'] == -1) {
			return 'Undefined';
		} else {
			return $RISK_CATEGORY_ARR[(int) $location->riskcategory['index']];
		}
	},
	'S_SUH' => null,
	'S_1UH' => null,
	'C_RS' => null,
	'C_R1' => null,
	'S_SD' => null,
	'S_1D' => null,
	'PGA' => null,
	'F_PGA' => null,
	'PGA_M' => null,
);

$FIGURES = array(
	's_s' => array(
		'US' => '1',
		'HI' => '10',
		'AK' => '11',
		'PR' => '13',
		'GUAM' => '14'
	),
	's_1' => array(
		'US' => '2',
		'HI' => '10',
		'AK' => '12',
		'PR' => '13',
		'GUAM' => '14'
	),
	't_l' => array(
		'US' => '15',
		'AK' => '17',
		'HI' => '18',
		'PR' => '19',
		'GUAM' => '20'
	)
);

$MAP_URL = "http://earthquake.usgs.gov/hazards/designmaps/downloads/pdfs/ASCE7-2005-Figure22-%02d.pdf";
$MAP_TEXT = "Figure 22-%d";

$T_L_PROVIDED = false;

?>
