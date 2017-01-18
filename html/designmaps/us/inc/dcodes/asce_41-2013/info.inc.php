<?php
$LABELS = array(
	'ss_raw' => array(
		'BSE-2N' => 'S<sub>S,BSE-2N</sub>',
		'BSE-1N' => 'S<sub>S,BSE-1N</sub>',
		'BSE-2E' => 'S<sub>S,5/50</sub>',
		'BSE-1E' => 'S<sub>S,20/50</sub>',
		'Custom' => 'S<sub>S,Custom</sub>'
	),
	's1_raw' => array(
		'BSE-2N' => 'S<sub>1,BSE-2N</sub>',
		'BSE-1N' => 'S<sub>1,BSE-1N</sub>',
		'BSE-2E' => 'S<sub>1,5/50</sub>',
		'BSE-1E' => 'S<sub>1,20/50</sub>',
		'Custom' => 'S<sub>1,Custom</sub>'
	),
	'ss_mod' => array(
		'BSE-2N' => 'S<sub>XS,BSE-2N</sub>',
		'BSE-1N' => 'S<sub>XS,BSE-1N</sub>',
		'BSE-2E' => 'S<sub>XS,BSE-2E</sub>',
		'BSE-1E' => 'S<sub>XS,BSE-1E</sub>',
		'Custom' => 'S<sub>XS,Custom</sub>'
	),
	's1_mod' => array(
		'BSE-2N' => 'S<sub>X1,BSE-2N</sub>',
		'BSE-1N' => 'S<sub>X1,BSE-1N</sub>',
		'BSE-2E' => 'S<sub>X1,BSE-2E</sub>',
		'BSE-1E' => 'S<sub>X1,BSE-1E</sub>',
		'Custom' => 'S<sub>X1,Custom</sub>'
	),
	'mod_spec' => 'MCE',
	'dsn_spec' => 'Design'
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
	'PGA' => null,
	'S_S' => 'ss',
	'S_1' => 's1',
	'F_PGA' => null,
	'F_a' => 'fa',
	'F_v' => 'fv',
	'S_DS' => null,
	'S_D1' => null,
	'S_XS' => 'srs',
	'S_X1' => 'sr1',
	'SDC' => null
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

$MAP_URL = "https://earthquake.usgs.gov/hazards/designmaps/downloads/pdfs/2010_ASCE-7_Figure_22-%d.pdf";
$MAP_TEXT = "Figure 22-%d";

$T_L_PROVIDED = false;

?>
