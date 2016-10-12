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
		'rc' => 'Risk Category'
);

$BATCH_COLUMNS = array(
	'S_SUH' => null,
	'S_1UH' => null,
	'S_SD' => null,
	'S_1D' => null,
	'C_RS' => 'crs',
	'C_R1' => 'cr1',
	'SDC' => function($location) { return $location->designcategory; },
);

$FIGURES = array(
	'special_filenames' => array(
		'8' => '2010_ASCE-7_Figures_22-8_and_22-9.pdf',
		'9' => '2010_ASCE-7_Figures_22-8_and_22-9.pdf',
		'10' => '2010_ASCE-7_Figures_22-10_and_22-11.pdf',
		'11' => '2010_ASCE-7_Figures_22-10_and_22-11.pdf',
		'13' => '2010_ASCE-7_Figures_22-13_and_22-14.pdf',
		'14' => '2010_ASCE-7_Figures_22-13_and_22-14.pdf',
		'15' => '2010_ASCE-7_Figures_22-15_and_22-16.pdf',
		'16' => '2010_ASCE-7_Figures_22-15_and_22-16.pdf'
	),
	'pga' => array(
		'US' => '7',
		'HI' => '8',
		'AK' => '9',
		'PR' => '10'
	),
	's_s' => array(
		'US' => '1',
		'HI' => '5',
		'AK' => '3',
		'PR' => '6'
	),
	's_1' => array(
		'US' => '2',
		'HI' => '5',
		'AK' => '4',
		'PR' => '6'
	),
	't_l' => array(
		'US' => '12',
		'AK' => '13',
		'HI' => '14',
		'PR' => '15'
	),
	'c_rs' => array(
		'US' => '17',
		'AK' => '17',
		'HI' => '17',
		'PR' => '17'
	),
	'c_r1' => array(
		'US' => '18',
		'AK' => '18',
		'HI' => '18',
		'PR' => '18'
	)
);

$MAP_URL = "http://earthquake.usgs.gov/hazards/designmaps/downloads/pdfs/2010_ASCE-7_Figure_22-%d.pdf";
$MAP_TEXT = "Figure 22-%d";

$T_L_PROVIDED = true;

?>
