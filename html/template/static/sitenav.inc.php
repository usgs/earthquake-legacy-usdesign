<?php
	print side_nav_header();

	print side_nav_item_list('/earthquakes/','Earthquakes', array('/ens/', '/earthquakes/'));
	print side_nav_item_list('/hazards/','Hazards', array('/hazards/'));
	print side_nav_item_list('/data/','Data<span style="font-size: .85em;"> &amp; </span>Products', array('/data/'));
	print side_nav_item_list('/learn/','Learn', array('/learn/'));
	print side_nav_item_list('/monitoring/','Monitoring', array('/monitoring/'));
	print side_nav_item_list('/research/','Research', array('/research/'));

	print side_nav_footer();

?>
