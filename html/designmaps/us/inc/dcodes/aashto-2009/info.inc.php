<?php
$LABELS = array(
		'ss_raw' => 'S<sub>S</sub>',
		's1_raw' => 'S<sub>1</sub>',
		'ss_mod' => 'S<sub>MS</sub>',
		's1_mod' => 'S<sub>M1</sub>',
		'ss_dsn' => 'S<sub>DS</sub>',
		's1_dsn' => 'S<sub>D1</sub>',
		'mod_spec' => 'MCE_R',
		'dsn_spec' => 'Design',
		'rc' => 'Risk Category',
);

$BATCH_COLUMNS = array(
	'RC' => null,
	'S_SUH' => null,
	'S_1UH' => null,
	'C_RS' => null,
	'C_R1' => null,
	'S_SD' => null,
	'S_1D' => null,
	'PGA_M' => null,
	'S_M1' => null,
	'S_MS' => null,
	'T_L' => null,
	'PGA' => 'pga',
	'S_S' => 'ss',
	'S_1' => 's1',
	'F_PGA' => 'fpga',
	'F_a' => 'fa',
	'F_v' => 'fv',
	'A_s' => function ($location) {
		return dataFormat(doubleval($location->pga) * doubleval($location->fpga));
	},
	'S_DS' => 'sds',
	'S_D1' => 'sd1',
	'SDC' => 'designcategory',
);

$FIGURES = array(
	's_s' => array(
		'US' => '3',
		'AK' => '19',
		'HI' => '17',
		'PR' => '22'
	),
	's_1' => array(
		'US' => '4',
		'AK' => '20',
		'HI' => '17',
		'PR' => '22'
	),
	'pga' => array(
		'US' => '2',
		'AK' => '18',
		'HI' => '16',
		'PR' => '21',
		'GUAM' => '11'
	)
);

$MAP_URL = 'http://earthquake.usgs.gov/hazards/designmaps/downloads/pdfs/AASHTO-2009-Figure-3.4.1-%d.pdf';
$MAP_TEXT = 'Figure 3.4.1-%d';

$T_L_PROVIDED = false;

$SHOWN_SPECTRA = array('design');

?>
