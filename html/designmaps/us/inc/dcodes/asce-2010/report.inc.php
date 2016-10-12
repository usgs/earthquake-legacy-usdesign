<?php
	$APP_WEB_DIR = (isset($_SERVER['APP_WEB_DIR'])) ?
		$_SERVER['APP_WEB_DIR'] : $_SERVER['REDIRECT_APP_WEB_DIR'];
	
	include_once $APP_WEB_DIR . '/inc/constants.inc.php';
	include_once $APP_WEB_DIR . '/inc/designcategory.inc.php';
?>
	<h2>Section 11.4.1 &mdash; Mapped Acceleration Parameters</h2>

	<p>
	Note: Ground motion values provided below are for the direction of maximum
	horizontal spectral response acceleration.  They have been converted from
	corresponding geometric mean ground motions computed by the USGS by applying
	factors of 1.1 (to obtain S<sub>S</sub>) and 1.3 (to obtain S<sub>1</sub>).
	Maps in the 2010 ASCE-7 Standard are provided for Site Class B.
	Adjustments for other Site Classes are made, as needed, in Section 11.4.3.
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

	<h2>Section 11.4.2 &mdash; Site Class</h2>
	<p>
		The authority having jurisdiction (not the USGS), site-specific
		geotechnical data, and/or the default has classified the site as
		<?php print $SITE_CLASS_ARR[$_siteclass]; ?>, based on the site soil 
		properties in accordance with Chapter 20.
	</p>
	<span class="imagecaption">Table 20.3&ndash;1 Site Classification</span>

	<?php include_once 'inc/dcodes/siteclass/usgs-2008.inc.php'; ?>

<?php new_page() ?>

	<?php
		$fafvcalc = new FaFvCalc();
		$_fa      = $fafvcalc->getFa($_data->num('ss'), $_siteclass);
		$_fv      = $fafvcalc->getFv($_data->num('s1'), $_siteclass);
		$_fpga    = $fafvcalc->getFpga($_data->num('pga'), $_siteclass);
	?>

	<h2>
		Section 11.4.3 &mdash; Site Coefficients and Risk&ndash;Targeted Maximum
		Considered Earthquake (<abbr title="Maximum Considered Earthquake"
		>MCE<sub>R</sub></abbr>) Spectral Response Acceleration Parameters
	</h2>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFaTableMarkup($_data->num('ss'), $_siteclass, array(
			'title' => 'Mapped MCE <sub>R</sub> Spectral Response Acceleration ' .
				'Parameter at Short Period',
			'caption' => 'Table 11.4&ndash;1: Site Coefficient F<sub>a</sub>',
		));
	?>
	</div>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFvTableMarkup($_data->num('s1'), $_siteclass, array(
			'title' => 'Mapped MCE <sub>R</sub> Spectral Response Acceleration ' .
				'Parameter at 1&ndash;s Period',
			'caption' => 'Table 11.4&ndash;2: Site Coefficient F<sub>v</sub>',
		));
	?>
	</div>

<?php new_page() ?>

	<ul class="equations">
		<li>
			<span>Equation (11.4&ndash;1):</span>
			S<sub>MS</sub> = F<sub>a</sub>S<sub>S</sub> = 
			<?php printf("%s x %s = %3.3f g",
				dataFormat($_fa), $_data->fmt('ss'), 
				round($_fa * $_data->num('ss'), $PRECISION)); ?>
		</li>
		<li>
			<span>Equation (11.4&ndash;2):</span>
			S<sub>M1</sub> = F<sub>v</sub>S<sub>1</sub> = 
			<?php printf("%s x %s = %3.3f g",
				dataFormat($_fv), $_data->fmt('s1'), 
				round($_fv * $_data->num('s1'), $PRECISION)); ?>
		</li>
	</ul>

	<?php
		// This is temporary and should be offloaded to backend processing.
		$_smsr = dataFormat($_fa * $_data->num('ss'));

		$_sm1r = dataFormat($_fv * $_data->num('s1'));
	?>

	<h2>Section 11.4.4 &mdash; Design Spectral Acceleration Parameters</h2>
	<ul class="equations">
		<li>
			<span>Equation (11.4&ndash;3):</span>
			S<sub>DS</sub> = &#8532; S<sub>MS</sub> =
			<?php print "&#8532; x $_smsr = " . $_data->fmt('sds'); ?> g
		</li>
		<li>
			<span>Equation (11.4&ndash;4):</span>
			S<sub>D1</sub> = &#8532; S<sub>M1</sub> =
			<?php print "&#8532; x $_sm1r = " . $_data->fmt('sd1'); ?> g
		</li>
	</ul>

	<h2>Section 11.4.5 &mdash; Design Response Spectrum</h2>
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
			Figure 11.4&ndash;1: Design Response Spectrum
		</span>
		<?php outputSpectrumChart(
			$_data->spectrum[0],
			550,
			360,
			true
		); ?>
	</div>

<?php new_page() ?>

	<h2>
		Section 11.4.6 &mdash; Risk-Targeted Maximum Considered Earthquake 
		(MCE<sub>R</sub>) Response Spectrum
	</h2>
	<div class="mapwrapper">
		<span class="imagecaption">
			The MCE<sub>R</sub> Response Spectrum is determined by
			multiplying the design response spectrum above by 1.5.
		</span>
		<?php outputSpectrumChart(
			$_data->spectrum[1],
			550,
			360,
			true
		); ?>
	</div>

<?php new_page() ?>

    <h2>
        Section 11.8.3 &mdash; Additional Geotechnical Investigation Report
        Requirements for Seismic Design Categories D through F
    </h2>
    <ul class="equations">
        <li>
            <span>
				From <?php map_link('pga') ?>
				</span>
            PGA = <?php print $_data->fmt('pga'); ?>
        </li>
        <li>
            <span>Equation (11.8&ndash;1):</span>
            PGA<sub>M</sub> = F<sub>PGA</sub>PGA =
            <?php printf(
				"%s x %s = %s g",
				$_data->fmt('fpga'),
				$_data->fmt('pga'),
                round($_data->num('fpga') * $_data->num('pga'), $PRECISION)
			); ?>
        </li>
    </ul>
	<?php
		print $fafvcalc->getFpgaTableMarkup($_data->num('pga'), $_siteclass, array(
			'title' => 'Mapped MCE Geometric Mean Peak Ground Acceleration, PGA',
			'caption' => 'Table 11.8&ndash;1: Site Coefficient F<sub>PGA</sub>',
		));
	?>

	<h2>
		Section 21.2.1.1 &mdash; Method 1 (from Chapter 21 &ndash; Site-Specific
		Ground Motion Procedures for Seismic Design)
	</h2>
    <ul class="equations">
        <li>
            <span>
				From <?php map_link('c_rs') ?>
				</span>
            C<sub>RS</sub> =
            <?php printf('%3.3f', $_data->num('crs')); ?>
        </li>
        <li>
            <span>
				From <?php map_link('c_r1') ?>
				</span>
            C<sub>R1</sub> =
            <?php printf('%3.3f', $_data->num('cr1')); ?>
        </li>
    </ul>

<?php last_page() ?>

	<h2>Section 11.6 &mdash; Seismic Design Category</h2>
	<div class="mapwrapper" style="margin: 16px 0">
		<span class="imagecaption">
			Table 11.6-1 Seismic Design Category Based on Short Period Response 
			Acceleration Parameter
		</span>
		<?php outputDCTable(
			$_riskcategory,
			'sds', $_data->num('sds'), 
			$_data->designcategory
		); ?>
	</div>
	<div class="mapwrapper" style="margin: 16px 0">
		<span class="imagecaption">
			Table 11.6-2 Seismic Design Category Based on 1-S Period Response 
			Acceleration Parameter
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
			category in accordance with Table 11.6-1 or 11.6-2&rdquo;
			= <?php print $_data->designcategory ?>
		</li>
	</ul>
	<p>
	Note: See Section 11.6 for alternative approaches to calculating Seismic
	Design Category.
	</p>
