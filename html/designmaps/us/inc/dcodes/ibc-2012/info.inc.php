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
		'mod_spec' => 'MCE_R',
		'dsn_spec' => 'Design',
		'rc' => 'Risk Category',
);

$BATCH_COLUMNS = array(
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
		'AK' => '4',
		'HI' => '3',
		'PR' => '6'
	),
	's_1' => array(
		'US' => '2',
		'AK' => '5',
		'HI' => '3',
		'PR' => '6'
	)
);

$MAP_URL = "https://earthquake.usgs.gov/hazards/designmaps/downloads/pdfs/IBC-2012-Fig1613p3p1(%d).pdf";
$MAP_TEXT = "Figure 1613.3.1(%d)";

$T_L_PROVIDED = false;

?>
