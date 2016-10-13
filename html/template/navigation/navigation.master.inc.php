<?php

print "<!-- _navigation -->";

// new template compatibility
if (isset($NAVIGATION)) {
	if ($NAVIGATION && $NAVIGATION !== true) {
		echo $NAVIGATION;
		return;
	}
}


if (!isset($SIDE_NAVIGATION)) {
	// search for nearest _navigation.inc.php
	$SIDE_NAVIGATION = findNearestFile("_navigation.inc.php");
}

if (is_file($SIDE_NAVIGATION)) {
	include $SIDE_NAVIGATION;
}
