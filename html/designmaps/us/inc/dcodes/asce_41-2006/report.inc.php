<?php
	$APP_WEB_DIR = (isset($_SERVER['APP_WEB_DIR'])) ?
		$_SERVER['APP_WEB_DIR'] : $_SERVER['REDIRECT_APP_WEB_DIR'];
	
	include_once $APP_WEB_DIR . '/inc/constants.inc.php';
?>
	<h2>Section 1.6.1 &ndash; General Procedure for Hazard Due to Ground Shaking</h2>

	<ul class="equations">
		<li>
			<span>From Section 1.6.1.<?php print $_EDITION_VARIANT == 'BSE-1' ?  2 : 1 ?></span>
			S<sub>S</sub> = <?php print $_data->fmt('ss') . " g"; ?>
		</li>
		<li>
			<span>From Section 1.6.1.<?php print $_EDITION_VARIANT == 'BSE-1' ?  2 : 1 ?></span>
			S<sub>1</sub> = <?php print $_data->fmt('s1') . " g"; ?>
		</li>
	</ul>


	<h2>Section 1.6.1.4 &ndash; Adjustment for Site Class</h2>
	<p>
		The authority having jurisdiction (not the USGS), site-specific
		geotechnical data, and/or the default has classified the site as
		<?php print $SITE_CLASS_ARR[$_siteclass]; ?>, based on the site soil 
		properties in accordance with Section 1.6.1.4.
	</p>
	<?php include_once 'inc/dcodes/siteclass/usgs-2002.inc.php'; ?>

<?php last_page() ?>

	<div class="mapwrapper" style="margin:32px 0;">
	<?php
		$fafvcalc = new FaFvCalc();
		print $fafvcalc->getFaTableMarkup($_data->num('ss'), $_siteclass, array(
			'caption' => 'Table 1&ndash;4. Values of F<sub>a</sub> as a Function ' .
			'of Site Class and Mapped Short-Period Spectral Response Acceleration ' .
			'S<sub>s</sub>',
			'title' => 'Mapped Spectral Acceleration at Short Period ' .
			'S<sub>s</sub>',
			'see_also' => 'Site-specific investigation required',
		));
	?>
	</div>

	<div class="mapwrapper" style="margin:32px 0;">
	<?php
		print $fafvcalc->getFvTableMarkup($_data->num('s1'), $_siteclass, array(
			'caption' => 'Table 1&ndash;5. Values of F<sub>v</sub> as a Function ' .
			'of Site Class and Mapped Spectral Response Acceleration at 1-Sec ' .
			'Period S<sub>1</sub>',
			'title' => 'Mapped Spectral Acceleration at Short Period ' .
			'S<sub>1</sub>',
			'see_also' => 'Site-specific investigation required',
		));
	?>
	</div>

	<ul class="equations">
		<li>
			<span>Equation (1&ndash;4):</span>
			S<sub>XS</sub> = F<sub>a</sub>S<sub>S</sub> = 
			<?php printf("%s x %s = %3.3f g",
				$_data->fmt('fa'), $_data->fmt('ss'), 
				round($_data->num('fa') * $_data->num('ss'), $PRECISION)); ?>
		</li>
		<li>
			<span>Equation (1&ndash;5):</span>
			S<sub>X1</sub> = F<sub>v</sub>S<sub>1</sub> = 
			<?php printf("%s x %s = %3.3f g",
				$_data->fmt('fv'), $_data->fmt('s1'), 
				round($_data->num('fv') * $_data->num('s1'), $PRECISION)); ?>
		</li>
	</ul>

	<?php
		// This is temporary and should be offloaded to backend processing.
		$_sms = $_fa * $_ss;
		$_smsr = number_format(round($_sms, $PRECISION), $PRECISION);

		$_sm1 = $_fv * $_s1;
		$_sm1r = number_format(round($_sm1, $PRECISION), $PRECISION);
	?>

<?php /*

	<h2>Section 11.4.4 &mdash; Design Spectral Acceleration Parameters</h2>
	<ul class="equations">
		<li>
			<span>Equation (11.4&ndash;3):</span>
			S<sub>DS</sub> = &#8532; S<sub>MS</sub> =
			<?php print "&#8532; x $_smsr = $_sdsr g"; ?>
		</li>
		<li>
			<span>Equation (11.4&ndash;4):</span>
			S<sub>D1</sub> = &#8532; S<sub>M1</sub> =
			<?php print "&#8532; x $_sm1r = $_sd1r g"; ?>
		</li>
	</ul>

	<h2>Section 11.4.5 &mdash; Design Response Spectrum</h2>
	<ul class="equations">
		<li>
			<span>
				From Figure 22-<?php print $FIGURES['t_l'][$_region];?>
			</span>
			T<sub>L</sub> = <?php print $_tl; ?> seconds
		</li>
	</ul>

	<div class="mapwrapper">
		<span class="imagecaption">
			Figure 11.4&ndash;1: Design Response Spectrum
		</span>
		<img alt="Chart" class="chart" src="<?php 
			print preg_replace('/chtt.*?;/','detailed=true&amp;',
					str_replace(" ", "+", $_images[0])) ?>&amp;chs=550x360" />
	</div>

 last_page()

	<h2>
		Section 11.4.6 &mdash; Maximum Considered Earthquake (MCE)
		Response Spectrum
	</h2>
	<div class="mapwrapper">
		<span class="imagecaption">
			The MCE Response Spectrum is determined by
			multiplying the design response spectrum by 1.5.
		</span>
		<img alt="Chart" class="chart" src="<?php 
			print preg_replace('/chtt.*?;/','detailed=true&amp;',
					str_replace(" ", "+", $_images[1])) ?>&amp;chs=550x360" />
	</div>
 */ ?>
