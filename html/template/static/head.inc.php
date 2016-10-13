<?php
	// master.js needs to be in head, until all content no longer depends on it in the head
?>
	<script type="text/javascript" src="/template/js/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="/template/js/master_20150623.js"></script>
<?php
	// moved up above

	if(isset($ENCODING) and strlen($ENCODING) > 0) {
		$encoding = $ENCODING;
	} else {
		$encoding = "iso-8859-1";
	}

	if(isset($HEAD)) {
		//need to print this first, base and alternate only seem to stick when first
		print $HEAD;
	}
	
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php print $encoding; ?>"/>
<meta name="keywords" content="aftershock,earthquake,epicenter,fault,foreshock,geologist,geophysics,hazard,hypocenter,intensity,intensity scale,magnitude,magnitude scale,mercalli,plate,richter,seismic,seismicity,seismogram,seismograph,seismologist,seismology,subduction,tectonics,tsunami,quake,sismologico,sismologia"/>
<meta name="google-site-verification" content="5Bw3LIEvwx8C0JA5hWKosAweW7kXIw0LS5OR438MOqU" />
<meta name="description" content="USGS Earthquake Hazards Program, responsible for monitoring, reporting, and researching earthquakes and earthquake hazards"/>

<!--[if lt IE 7]>
<link rel="stylesheet" href="/template/css/ietextonly.css" type="text/css" media="all" />
<![endif]-->

<!--[if gte IE 7]><!-->
	<link rel="stylesheet" type="text/css" media="all" href="/template/css/reset.css"/>
	<link rel="stylesheet" type="text/css" media="all" href="/template/css/master.css"/>
<!--<![endif]-->

<link rel="stylesheet" media="print" type="text/css" href="/template/css/print.css" />

<?php 
	//user javascripts
  // Process JS
	// Scripts are printed in the page foot.inc.php

	//user stylesheets
  // Process $STYLESHEETS
  // Print link tags


	if (isset($HEAD_LAST)) {
		print $HEAD_LAST;
	}

