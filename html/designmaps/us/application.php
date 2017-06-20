<?php
	require_once 'inc/appconfig.inc.php';
	$TITLE      = 'U.S. Seismic Design Maps';
	$CONTACT = 'smcgowan@usgs.gov';
	$HEAD = '
		<link rel="stylesheet" href="css/dialog.20141009.css"/>
		<link rel="stylesheet" href="css/locationview.css"/>
		<link rel="stylesheet" href="css/application.css"/>
		<style>
			.addthis_button {
				display: none;
			}
		</style>
	';
	$FOOT = '
		<script src="js/dialog.20141009.js"></script>
		<script src="js/locationview.js"></script>
		<script src="js/conf.js.php"></script>
		<script src="js/application.js"></script>
	';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/template/template.inc.php';
	include_once 'inc/constants.inc.php';
?>

<p class="callout">
	For seismic design parameter values from the 2015 NEHRP Recommended Seismic
	Provisions, which are being adopted into the 2016 ASCE 7 Standard and the
	2018 International Building Code, please see the
	<a href="/designmaps/beta/us/">Beta version of the U.S. Seismic Design Maps
	application</a>.
</p>

<p class="callout error">
	Within the 2013 ASCE 41 design code reference document option of this web
	tool, the &ldquo;Custom&rdquo; earthquake hazard level option is no longer
	available. However, outside of this tool, a USGS web service that includes
	the &ldquo;Custom&rdquo; option is now available
	<a href="/ws/designmaps/asce41-13.html">here</a>.
</p>

<div id="application">
	<ul class="nav-tabs">
		<li class="active"><a href="application.php">Application</a></li>
		<li><a href="batch.php">Batch Mode</a></li>
		<li><a href="https://earthquake.usgs.gov/hazards/designmaps/usdesigndoc.php">Help</a></li>
	</ul>
	<form method="post" action="noscript.inc.php" onsubmit="return false;" id="frm_app">
		<ol>
			<li>
			<label for="designCode">
				Design Code Reference Document
				<em>
					Consult your local design official if you need help selecting
					this.
				</em>
			</label>
			<select name="designCode" id="designCode" required>
				<?php include 'inc/design-code-selector.inc.php' ?>
			</select>
			</li>
			<li hidden>
			<label>
				Earthquake Hazard Level
				<em>
					The particular analysis procedure to use.
				</em>
			</label>
			<select name="designCodeVariant" id="designCodeVariant">
			</select>
			</li>
			<li hidden>
			<label>
				Probability of Exceedance (in 50 years)
				<em>
					The percent probability of ground motion exceedance in 50 years.
				</em>
			</label>
			<input name="hazardProbability" id="hazardProbability" value="10">
			</li>
			<li>
			<label for="reportTitle">
				Report Title
				<span style="font-size:.9em;color:#666666">(Optional)</span>
				<em>This will appear at the top of the generated report.</em>
			</label>
			<input type="text" name="reportTitle" id="reportTitle"
			value="<?php print param('title', ''); ?>"/>
			</li>
			<li>
			<label for="siteclass">
				Site Soil Classification
				<em>
					This is <strong>not</strong> automatically
					selected based on site location.
				</em>
			</label>
			<select name="siteclass" id="siteclass" required>
				<option value="">Please Select&hellip;</option>
				<option value="0">
				Site Class A &ndash; &ldquo;Hard Rock&rdquo;
				</option>
				<option value="1">
				Site Class B &ndash; &ldquo;Rock&rdquo;
				</option>
				<option value="2">
				Site Class C &ndash; &ldquo;Very Dense Soil and Soft Rock&rdquo;
				</option>
				<option value="3">
				Site Class D &ndash; &ldquo;Stiff Soil&rdquo;
				(Default)
				</option>
				<option value="4">
				Site Class E &ndash; &ldquo;Soft Clay Soil&rdquo;
				</option>
			</select>
			</li>
			<li hidden>
			<label for="riskcategory">
				Occupancy/Risk Category
				<em>
					Used to compute the seismic design category.
				</em>
			</label>
			<select name="riskcategory" id="riskcategory" required>
				<option value="">Please Select&hellip;</option>
				<option value="0">
				I or II or III
				</option>
				<option value="3">
				IV (e.g., essential facilities)
				</option>
			</select>
			</li>
			<li>
			<label for="latitude">
				Site Latitude
				<em>Decimal degrees for the site location.</em>
			</label>
			<input type="text" id="latitude" name="latitude"
			value="<?php print param('latitude', ''); ?>"/>
			</li>
			<li>
			<label for="longitude" id="forLongitude">
				Site Longitude
				<em>Decimal degrees for the site location.</em>
			</label>
			<input type="text" id="longitude" name="longitude"
			value="<?php print param('longitude', ''); ?>"/>
			</li>
			<li>
			<input type="submit" id="submitRequest" class="button" value="Compute Values" />
			</li>
		</ol>
	</form>
	<div id="popupBlocker">
		This application delivers output via a pop-up window.
		However, it appears your browser is set to block pop-ups.
		For information on how to enable pop-ups, please consult one of the following browser-specific references:
		<ul>
			<li>
				<a target="_blank" href="https://support.google.com/chrome/bin/answer.py?hl=en&answer=95472">
					Chrome
				</a>
			</li>
			<li>
				<a target="_blank" href="https://support.mozilla.org/en-US/kb/pop-blocker-settings-exceptions-troubleshooting#w_pop-up-blocker-settings">
					Firefox
				</a>
			</li>
			<li>
				<a target="_blank" href="https://windows.microsoft.com/en-US/windows-vista/Internet-Explorer-Pop-up-Blocker-frequently-asked-questions">
					Internet Explorer
				</a>
			</li>
			<li>
				<a target="_blank" href="https://support.apple.com/kb/PH4980">
					Safari
				</a>
			</li>
		</ul>
	</div>
	<div id="asce-erratum" title="Notification of ASCE 7-10 Erratum">
	<p>
		Soon, the American Society of Civil Engineers (ASCE) will release an
		<a target="_blank" href="http://www.asce.org/structural-engineering/sei-supplements-and-errata/"
		>erratum</a> for ASCE Standard 7-10 that affects this web application.
	</p>
	<p>
		The peak ground acceleration (PGA) maps in the first two printings of ASCE
		7-10 were developed using 0.6g for the floor of the deterministic cap,
		whereas the site-specific ground motion procedure (Section 21.5) specified
		0.5g. In the third and subsequent printings, the PGA maps will be based on
		the 0.5g floor, consistent with the site-specific procedure. In anticipation
		of this change, on December 12 of 2012 the floor of the deterministic PGA cap
		used in this web application was lowered to 0.5g for the 2010 ASCE 7
		&ldquo;Design Code Reference Document&rdquo; option.
	</p>
	<p>
		Prematurely, the floor was also lowered for the 2009 NEHRP option. Although
		the ASCE 7-10 erratum might ultimately be adopted by the developers of the
		2009 NEHRP Provisions, for the time being the PGA maps in the Provisions
		still reflect a 0.6g floor.
	</p>
	<p>
		Thus, as of April 2 of 2013, the floor of the deterministic PGA cap for the
		2009 NEHRP option of this web application has been reset to 0.6g. The floor
		for the 2010 ASCE 7 option remains 0.5g.
	</p>
	</div>

	<div id="mapdiv"></div>
</div>
