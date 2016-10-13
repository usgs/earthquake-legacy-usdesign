<?php
	$APP_WEB_DIR = (isset($_SERVER['APP_WEB_DIR'])) ?
		$_SERVER['APP_WEB_DIR'] : $_SERVER['REDIRECT_APP_WEB_DIR'];

	include_once $APP_WEB_DIR . '/inc/constants.inc.php';
	include_once $APP_WEB_DIR . '/inc/designcategory.inc.php';
?>
	<h2>Section 1613.3.1 &mdash; Mapped acceleration parameters</h2>
	
	<p>
	Note: Ground motion values provided below are for the direction of maximum horizontal spectral response acceleration.
	They have been converted from corresponding geometric mean ground motions computed by the USGS by applying factors of 1.1 (to obtain S<sub>S</sub>) and 1.3 (to obtain S<sub>1</sub>).
	Maps in the 2012/2015 International Building Code are provided for Site Class B.
	Adjustments for other Site Classes are made, as needed, in Section 1613.3.3.
	</p>

	<ul class="equations">
		<li>
			<span>
				From <?php map_link('s_s') ?>
			</span>
			S<sub>S</sub> = <?php print $_data->fmt('ss'); ?> g
		</li>
		<li>
			<span>
				From <?php map_link('s_1') ?>
			</span>
			S<sub>1</sub> = <?php print $_data->fmt('s1'); ?> g
		</li>
	</ul>

	<h2>Section 1613.3.2 &mdash; Site class definitions</h2>

	<p>
		The authority having jurisdiction (not the USGS), site-specific
		geotechnical data, and/or the default has classified the site as
		<?php print $SITE_CLASS_ARR[$_siteclass]; ?>, based on the site soil 
		properties in accordance with Section 1613.
	</p>

	<span class="imagecaption">2010 ASCE-7 Standard &ndash; Table 20.3-1<br />SITE CLASS DEFINITIONS</span>

	<?php include_once 'inc/dcodes/siteclass/usgs-2008.inc.php'; ?>

<?php new_page() ?>

	<h2>
		Section 1613.3.3 &mdash; Site coefficients and adjusted maximum
		considered earthquake spectral response acceleration parameters
	</h2>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		$fafvcalc = new FaFvCalc();
		print $fafvcalc->getFaTableMarkup($_data->num('ss'), $_siteclass, array(
			'title' => 'Mapped Spectral Response Acceleration at Short Period',
			'caption' => 'TABLE 1613.3.3(1)<br />VALUES OF SITE COEFFICIENT F<sub>a</sub>',
		));
	?>
	</div>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFvTableMarkup($_data->num('s1'), $_siteclass, array(
			'title' => 'Mapped Spectral Response Acceleration at 1&ndash;s Period',
			'caption' => 'TABLE 1613.3.3(2)<br />VALUES OF SITE COEFFICIENT F<sub>v</sub>',
		));
	?>
	</div>

<?php new_page() ?>

	<ul class="equations">
		<li>
			<span>Equation (16-37):</span>
			S<sub>MS</sub> = F<sub>a</sub>S<sub>S</sub> = 
			<?php printf(
				"%s x %s = %3.3f g",
				$_data->fmt('fa'),
				$_data->fmt('ss'), 
				round($_data->num('fa') * $_data->num('ss'), $PRECISION)
			); ?>
		</li>
		<li>
			<span>Equation (16-38):</span>
			S<sub>M1</sub> = F<sub>v</sub>S<sub>1</sub> = 
			<?php printf(
				"%s x %s = %3.3f g",
				$_data->fmt('fv'),
				$_data->fmt('s1'), 
				round($_data->num('fv') * $_data->num('s1'), $PRECISION)
			); ?>
		</li>
	</ul>

	<?php
		// This is temporary and should be offloaded to backend processing.
		$_smsr = dataFormat($_data->num('fa') * $_data->num('ss'));

		$_sm1r = dataFormat($_data->num('fv') * $_data->num('s1'));
	?>

	<h2>Section 1613.3.4 &mdash; Design spectral response acceleration parameters</h2>
	<ul class="equations">
		<li>
			<span>Equation (16-39):</span>
			S<sub>DS</sub> = &#8532; S<sub>MS</sub> =
			<?php print "&#8532; x $_smsr = " . $_data->fmt('sds'); ?> g
		</li>
		<li>
			<span>Equation (16-40):</span>
			S<sub>D1</sub> = &#8532; S<sub>M1</sub> =
			<?php print "&#8532; x $_sm1r = " . $_data->fmt('sd1'); ?> g
		</li>
	</ul>

<?php last_page() ?>

	<h2>Section 1613.3.5 &mdash; Determination of seismic design category</h2>
	<div class="mapwrapper" style="margin: 16px 0">
		<span class="imagecaption">
			TABLE 1613.3.5(1)<br>SEISMIC DESIGN CATEGORY BASED ON SHORT-PERIOD
			(0.2 second) RESPONSE ACCELERATION
		</span>
		<?php outputDCTable(
			$_riskcategory,
			'sds', $_data->num('sds'), 
			$_data->designcategory
		); ?>
	</div>
	<div class="mapwrapper" style="margin: 16px 0">
		<span class="imagecaption">
			TABLE 1613.3.5(2)<br>SEISMIC DESIGN CATEGORY BASED ON 1-SECOND PERIOD
			RESPONSE ACCELERATION
		</span>
		<?php outputDCTable(
			$_riskcategory,
			'sd1', $_data->num('sd1'), 
			$_data->designcategory
		); ?>
	</div>
	<p>
	Note: When S<sub>1</sub> is greater than or equal to 0.75g, the Seismic Design Category 
	is <strong>E</strong> for buildings in Risk Categories I, II, and III, and
	<strong>F</strong> for those in Risk Category IV, irrespective of the above.
	</p>
	<ul class="equations">
		<li class="summary">
			Seismic Design Category &equiv; &ldquo;the more severe design
			category in accordance with Table 1613.3.5(1) or 1613.3.5(2)&rdquo;
			= <?php print $_data->designcategory ?>
		</li>
	</ul>
	<p>
	Note: See Section 1613.3.5.1 for alternative approaches to calculating Seismic
	Design Category.
	</p>
