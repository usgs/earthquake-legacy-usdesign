<?php

$url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (!isset($MODIFIED)) {
	$MODIFIED = filemtime($_SERVER['SCRIPT_FILENAME']);
}
$lastModified = date ("F d, Y H:i:s", $MODIFIED) . ' UTC';

//BEGIN USGS Footer Template
?>
<div id="usgsnav">
	<ul>
		<li><a href="https://www.usgs.gov/laws/accessibility.html" title="Accessibility Policy (Section 508)">Accessibility</a></li>
		<li><a href="https://www.usgs.gov/foia/"><abbr title="Freedom of Information Act">FOIA</abbr></a></li>
		<li><a href="https://www.usgs.gov/laws/privacy.html" title="Privacy policies of the U.S. Geological Survey.">Privacy</a></li>
		<li><a href="https://www.usgs.gov/laws/policies_notices.html" title="Policies and notices that govern information posted on USGS Web sites.">Policies and Notices</a></li>
		<li><a href="https://www.nehrp.gov" class="nehrp">In partnership with <abbr title="National Earthquake Hazards Reduction Program">n<span class="e">e</span>hrp</abbr></a></li>
		<li><a href="https://www.usa.gov/" class="usagov" title="USAGov: Government Made Easy"><img src="/template/images/usagov.jpg" alt="USA.gov" width="90" height="26" /></a></li>
	</ul>
</div>
<div id="footer">
	<p>
		<a href="https://www.doi.gov/index.cfm">U.S. Department of the Interior</a> | <a href="https://www.usgs.gov/">U.S. Geological Survey</a><br />
		Page URL: <?php print htmlspecialchars($url); ?><br />
		Page Contact Information: <a href="mailto:

<?php
// make sure contact emails are fully qualified, since that is a new requirement
// jf - 2015-09-10

// treat $CONTACT as a comma separated list of emails
$emails = explode(',', $CONTACT);
// check each email in list
for ($e = 0, $elen=count($emails); $e < $elen; $e++) {
  $email = $emails[$e];
  // check for @
  if (strpos($email, '@') === FALSE) {
    // not qualified with a domain name, add default "usgs.gov"
    $emails[$e] = $email . '@usgs.gov';
  }
}
$CONTACT = implode(',', $emails);

// output list of email addresses
echo $CONTACT;

		?>?subject=EHP%20Website%20Email%20">Contact Us</a><br />
		Page Last Modified: <?php print $lastModified; ?>
	</p>
	<?php foreach($DISCLAIMERS as $D) { print '<small class="disclaimer">' . $D . '</small>'; } ?>
</div>
<?php //END USGS Footer Template ?>
