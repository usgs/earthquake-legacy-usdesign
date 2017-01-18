<?php
$EDITION_BASIS = '2002 USGS seismic hazard data';

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
		'rc' => 'Seismic Use Group',
);

$RISK_CATEGORY_ARR = array(
	-1 => 'None Supplied',
	0 => 'I/II',
	3 => 'III (e.g. essential facilities)'
);

$LONG_RISK_CATEGORIES = array(
	-1 => 'None Supplied',
	0 => 'Seismic Use Group I/II',
	3 => 'Seismic Use Group III (e.g. essential facilities)'
);


$BATCH_COLUMNS = array(
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
		'US' => 1,
		'HI' => 10,
		'AK' => 11,
		'PR' => 13,
	),
	's_1' => array(
		'US' => 2,
		'HI' => 10,
		'AK' => 12,
		'PR' => 13,
	),
	't_l' => array(
		'US' => 16,
		'HI' => 19,
		'AK' => 18,
		'PR' => 20,
	),
);

$MAP_URL = "https://earthquake.usgs.gov/hazards/designmaps/downloads/pdfs/NEHRP-2003-Figure3.3-%02d.pdf";
$MAP_TEXT = "Figure 3.3-%d";

$SHOWN_SPECTRA = array('design');

$T_L_PROVIDED = false;
