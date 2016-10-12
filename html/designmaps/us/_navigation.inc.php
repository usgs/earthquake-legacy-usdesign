<?php
	require_once 'inc/appconfig.inc.php';

	$section  = '/designmaps/us';
	$ehpsection = '/hazards/designmaps';

	print side_nav_header();

	print side_nav_item("${ehpsection}", 'Seismic Design Maps &amp; Tools');

	print side_nav_group("${ehpsection}/usdesign.php", 'US Seismic Design Maps',
		side_nav_item("${section}/application.php", 'Use the Tool') .
		side_nav_item("${ehpsection}/usdesigndoc.php", 'Documentation &amp; Help') .
		side_nav_item("${section}/changelog.php", 'Recent Changes')
	);

	print side_nav_footer();
?>
