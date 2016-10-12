<?php
	$APP_WEB_DIR = (isset($_SERVER['APP_WEB_DIR'])) ?
		$_SERVER['APP_WEB_DIR'] : $_SERVER['REDIRECT_APP_WEB_DIR'];
	
	include_once $APP_WEB_DIR . '/inc/constants.inc.php';
	include_once $APP_WEB_DIR . '/inc/designcategory.inc.php';
?>
	<h2>Section 3.3.1 &mdash; Mapped acceleration parameters</h2>
	
	<p>
		Maps in the 2003 NEHRP Provisions are provided for Site Class B.
		Adjustments for other Site Classes are made, as needed, in Section 3.3.2.
	</p>

	<ul class="equations">
		<li>
			<span>From <?php map_link('s_s') ?></span>
			S<sub>S</sub> = <?php print dataFormat($_data->ss); ?> g
		</li>
		<li>
			<span>From <?php map_link('s_1') ?></span>
			S<sub>1</sub> = <?php print dataFormat($_data->s1); ?> g
		</li>
	</ul>

	<h2>Section 3.5.1 &mdash; Site Class definitions</h2>
	<p>
		The authority having jurisdiction (not the USGS), site-specific
		geotechnical data, and/or the default has classified the site as
		<?php print $SITE_CLASS_ARR[$_siteclass]; ?>, based on the site soil 
		properties in accordance with Chapter 3.
	</p>

	<?php include_once 'siteclass.inc.php'; ?>

<?php new_page() ?>

	<?php
		$fafvcalc = new FaFvCalc();
	?>

	<h2>
		Section 3.3.2 &mdash; Site coefficients and adjusted acceleration parameters
	</h2>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFaTableMarkup($_data->num('ss'), $_siteclass, array(
			'caption' => 'Table 3.3&ndash;1: Site Coefficient F<sub>a</sub>',
			'title' => 'Mapped MCE Spectral Response Acceleration ' .
				'Parameter at 0.2s Period',
			'see_also' => 'Site-specific geotechnical investigation and ' .
				'dynamic site response analyses shall be performed.',
		));
	?>
	</div>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFvTableMarkup($_data->num('s1'), $_siteclass, array(
			'caption' => 'Table 3.3&ndash;2: Site Coefficient F<sub>v</sub>',
			'title' => 'Mapped MCE Spectral Response Acceleration ' .
				'Parameter at 1.0s Period',
			'see_also' => 'Site-specific geotechnical investigation and ' .
				'dynamic site response analyses shall be performed.',
		));
	?>
	</div>

<?php new_page() ?>

	<?php
		// This is temporary and should be offloaded to backend processing.
		$_smsr = dataFormat($_data->num('fa') * $_data->num('ss'));

		$_sm1r = dataFormat($_data->num('fv') * $_data->num('s1'));
	?>

	<ul class="equations">
		<li>
			<span>Equation (3.3&ndash;1):</span>
			S<sub>MS</sub> = F<sub>a</sub>S<sub>S</sub> = 
			<?php printf("%s x %s = %3.3f g",
				$_data->fmt('fa'), $_data->fmt('ss'), 
				round($_smsr, $PRECISION)); ?>
		</li>
		<li>
			<span>Equation (3.3&ndash;2):</span>
			S<sub>M1</sub> = F<sub>v</sub>S<sub>1</sub> = 
			<?php printf("%s x %s = %3.3f g",
				$_data->fmt('fv'), $_data->fmt('s1'), 
				round($_sm1r, $PRECISION)); ?>
		</li>
	</ul>

	<h2>Section 3.3.3 &mdash; Design acceleration parameters</h2>
	<ul class="equations">
		<li>
			<span>Equation (3.3&ndash;3):</span>
			S<sub>DS</sub> = &#8532; S<sub>MS</sub> =
			<?php print "&#8532; x $_smsr = " . $_data->fmt('sds'); ?> g
		</li>
		<li>
			<span>Equation (3.3&ndash;4):</span>
			S<sub>D1</sub> = &#8532; S<sub>M1</sub> =
			<?php print "&#8532; x $_sm1r = " . $_data->fmt('sd1'); ?> g
		</li>
	</ul>

	<h2>Section 3.3.4 &mdash; Design response spectrum</h2>
	<ul class="equations">
		<li>
			<span>
				From <?php map_link('t_l') ?>
			</span>
			T<sub>L</sub> = <?php print $_data->fmt('tl', 0); ?> seconds
		</li>
	</ul>

	<div class="mapwrapper">
		<span class="imagecaption">
			Figure 3.3&ndash;15 Design Response Spectrum
		</span>
		<?php outputSpectrumChart(
			$_data->spectrum[0],
			550,
			360,
			true
		); ?>
	</div>

<?php last_page() ?>

	<h2>Section 1.4.1 &mdash; Determination of Seismic Design Category</h2>
	<div class="mapwrapper" style="margin: 16px 0">
		<span class="imagecaption">
			Table 1.4-1 Seismic Design Category Based on S<sub>DS</sub>
		</span>
		<?php outputDCTable(
			$_riskcategory,
			'sds-nehrp-2003', $_data->num('sds'), 
			$_data->designcategory
		); ?>
	</div>
	<div class="mapwrapper" style="margin: 16px 0">
		<span class="imagecaption">
			Table 1.4-2 Seismic Design Category Based on S<sub>D1</sub>
		</span>
		<?php outputDCTable(
			$_riskcategory,
			'sd1-nehrp-2003', $_data->num('sd1'), 
			$_data->designcategory
		); ?>
	</div>
	<p>
	Note: When S<sub>1</sub> is greater than or equal to 0.75g, the Seismic Design Category 
	is <strong>E</strong> for structures in Seismic Use Groups I and II and
	<strong>F</strong> for those in Seismic Use Group III, irrespective of the above.
	</p>
	<ul class="equations">
		<li class="summary">
			Seismic Design Category &equiv; &ldquo;the more severe seismic design
			category determined in accordance with Table 1.4&ndash;1 and 
			1.4&ndash;2, irrespective of the fundamental period of vibration of 
			the structure, T&rdquo; = <?php print $_data->designcategory ?>
		</li>
	</ul>
	<p>
	Note: See Section 1.4.1 for alternative approaches to calculating Seismic
	Design Category.
	</p>
