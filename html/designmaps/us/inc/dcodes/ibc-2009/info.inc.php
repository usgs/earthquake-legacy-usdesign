<?php
$LABELS = array(
		'ss_raw' => 'S<sub>S</sub>',
		's1_raw' => 'S<sub>1</sub>',
		'ss_mod' => 'S<sub>MS</sub>',
		's1_mod' => 'S<sub>M1</sub>',
		'ss_dsn' => 'S<sub>DS</sub>',
		's1_dsn' => 'S<sub>D1</sub>',
		'pga_raw' => 'PGA',
		'pga_mod' => 'PGA<sub>M</sub>',
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
	'T_L' => null,
	'PGA' => null,
	'F_PGA' => null,
	'PGA_M' => null,
);

$FIGURES = array(
	's_s' => array(
		'US' => '1',
		'AK' => '11',
		'HI' => '10',
		'PR' => '13'
	),
	's_1' => array(
		'US' => '2',
		'AK' => '12',
		'HI' => '10',
		'PR' => '13'
	)
);

$MAP_URL = "https://earthquake.usgs.gov/hazards/designmaps/downloads/pdfs/IBC-2006-Figure1613_5(%02d).pdf";
$MAP_TEXT = "Figure 1613.5(%d)";

$T_L_PROVIDED = false;

?>
