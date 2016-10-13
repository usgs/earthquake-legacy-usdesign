<?php
	include_once $APP_WEB_DIR . '/inc/constants.inc.php';
?>
	<h2>Section 11.4.1 &mdash; Mapped Acceleration Parameters</h2>

	<ul class="equations">
		<li>
			<span>From Figure 22-<?php print $ASCE_SS_FIGURES[$_region]; ?></span>
			S<sub>S</sub> = <?php print "$_ssr g"; ?>
		</li>
		<li>
			<span>From Figure 22-<?php print $ASCE_S1_FIGURES[$_region]; ?></span>
			S<sub>1</sub> = <?php print "$_s1r g"; ?>
		</li>
	</ul>


	<h2>Section 11.4.2 &mdash; Site Class</h2>
	<?php include_once 'siteclass.inc.php'; ?>

<?php new_page() ?>

	<h2>
		Section 11.4.3 &mdash; Site Coefficients and Risk&ndash;Targeted Maximum
		Considered Earthquake (<abbr title="Maximum Considered Earthquake"
		>MCE<sub>R</sub></abbr>) Spectral Response Acceleration Parameters
	</h2>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFaTableMarkup($_ss, $_sc, array(
			'title' => 'Mapped MCE <sub>R</sub> Spectral Response Acceleration ' .
				'Parameter at Short Period',
			'caption' => 'Table 11.4&ndash;1: Site Coefficient F<sub>a</sub>',
		));
	?>
	</div>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFvTableMarkup($_s1, $_sc, array(
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
			<?php printf("%s x %s = %3.3f g", $_far, $_ssr,
				round($_fa * $_ss, $PRECISION)); ?>
		</li>
		<li>
			<span>Equation (11.4&ndash;2):</span>
			S<sub>M1</sub> = F<sub>v</sub>S<sub>1</sub> =
			<?php printf("%s x %s = %3.3f g", $_fvr, $_s1r,
				round($_fv * $_s1, $PRECISION)); ?>
		</li>
	</ul>

	<?php
		// This is temporary and should be offloaded to backend processing.
		$_sms = $_fa * $_ss;
		$_smsr = number_format(round($_sms, $PRECISION), $PRECISION);

		$_sm1 = $_fv * $_s1;
		$_sm1r = number_format(round($_sm1, $PRECISION), $PRECISION);
	?>

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
				From Figure 22-<?php print $ASCE_TL_FIGURES[$_region];?>
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

<?php new_page() ?>

	<h2>
		Section 11.4.6 &mdash; Risk-Targeted Maximum Considered Earthquake
		(MCE<sub>R</sub>) Response Spectrum
	</h2>
	<div class="mapwrapper">
		<span class="imagecaption">
			The MCE<sub>R</sub> Response Spectrum is determined by
			multiplying the design response spectrum by 1.5.
		</span>
		<img alt="Chart" class="chart" src="<?php
			print preg_replace('/chtt.*?;/','detailed=true&amp;',
					str_replace(" ", "+", $_images[1])) ?>&amp;chs=550x360" />
	</div>

<?php last_page() ?>

    <h2>
        Section 11.8.3 &mdash; Additional Geotechnical Investigation Report
        Requirements for Seismic Design Categories D through F
    </h2>
    <ul class="equations">
        <li>
            <span>
					From Figure 22&ndash;<?php print $ASCE_PGA_FIGURES[$_region]; ?>
				</span>
            PGA =
            <?php print "$_pgar g"; ?>
        </li>
        <li>
            <span>Equation (11.8&ndash;1):</span>
            PGA<sub>M</sub> = F<sub>PGA</sub>PGA =
            <?php printf("%s x %s = %s g", $_fpgar, $_pgar,
                round($_fpga * $_pga, $PRECISION)); ?>
        </li>
    </ul>
	<?php
		print $fafvcalc->getFpgaTableMarkup($_pga, $_sc, array(
			'title' => 'Mapped MCE Geometric Mean Peak Ground Acceleration, PGA',
			'caption' => 'Table 11.8&ndash;1: Site Coefficient F<sub>PGA</sub>',
		));
	?>
