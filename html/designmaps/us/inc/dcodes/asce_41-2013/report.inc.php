<?php
	include_once $APP_WEB_DIR . '/inc/constants.inc.php';
?>
	<h2>
		Section 2.4.1 &ndash; General Procedure for Hazard Due to Ground Shaking
	</h2>

	<ul class="equations">
		<?php if ($_EDITION_VARIANT == 'BSE-1N') { ?>
		<p>
			Provided as a reference for Equation (2-4) and Equation (2-5), respectively:
		</p>
		<li>
			<span>From Section 2.4.1.1</span>
			S<sub>S,BSE-2N</sub> = <?php print $_data->fmt('ss') . " g"; ?>
		</li>
		<li>
			<span>From Section 2.4.1.1</span>
			S<sub>1,BSE-2N</sub> = <?php print $_data->fmt('s1') . " g"; ?>
		</li>
		<?php } else { ?>
		<?php if ($_EDITION_VARIANT == 'BSE-1E') { ?>
		<p>
			20%/50-year maximum direction spectral response acceleration for
			0.2s and 1.0s periods, respectively:
		</p>
		<?php } else if ($_EDITION_VARIANT == 'BSE-2E') { ?>
		<p>
			5%/50-year maximum direction spectral response acceleration for
			0.2s and 1.0s periods, respectively:
		</p>
		<?php } ?>
		<li>
			<span>From Section 2.4.1.<?php print $_variant + 1 ?></span>
			<?php print $LABELS['ss_raw'] ?> = <?php print $_data->fmt('ss') . " g"; ?>
		</li>
		<li>
			<span>From Section 2.4.1.<?php print $_variant + 1 ?></span>
			<?php print $LABELS['s1_raw'] ?> = <?php print $_data->fmt('s1') . " g"; ?>
		</li>
		<?php } ?>
	</ul>


	<h2>Section 2.4.1.6 &ndash; Adjustment for Site Class</h2>
	<p>
		The authority having jurisdiction (not the USGS), site-specific
		geotechnical data, and/or the default has classified the site as
		<?php print $SITE_CLASS_ARR[$_siteclass]; ?>, based on the site soil
		properties in accordance with Section 2.4.1.6.1.
	</p>
	<?php include_once 'inc/dcodes/siteclass/usgs-2002.inc.php'; // Not typo, 2002 is correct ?>

<?php new_page() ?>

	<div class="mapwrapper" style="margin:32px 0;">
	<?php
		$fafvcalc = new FaFvCalc();
		print $fafvcalc->getFaTableMarkup($_data->num('ss'), $_siteclass, array(
			'caption' => 'Table 2&ndash;3. Values of F<sub>a</sub> as a Function ' .
			'of Site Class and Mapped Short-Period Spectral Response Acceleration ' .
			'S<sub>s</sub>',
			'title' => 'Mapped Spectral Acceleration at Short-Period ' .
			'S<sub>s</sub>',
			'see_also' => 'Site-specific geotechnical and dynamic site response '.
			'analyses shall be performed'
		));
	?>
	</div>

	<div class="mapwrapper" style="margin:32px 0;">
	<?php
		print $fafvcalc->getFvTableMarkup($_data->num('s1'), $_siteclass, array(
			'caption' => 'Table 2&ndash;4. Values of F<sub>v</sub> as a Function ' .
			'of Site Class and Mapped Spectral Response Acceleration at 1&nbsp;s ' .
			'Period S<sub>1</sub>',
			'title' => 'Mapped Spectral Acceleration at 1 s Period ' .
			'S<sub>1</sub>',
			'see_also' => 'Site-specific geotechnical and dynamic site response '.
			'analyses shall be performed'
		));
	?>
	</div>

<?php new_page() ?>

	<ul class="equations">
		<?php if ($_EDITION_VARIANT == 'BSE-1N') { ?>
		<li>
			<span>Provided as a reference for Equation (2-4):</span>
			S<sub>XS,BSE-2N</sub> = F<sub>a</sub>S<sub>S,BSE-2N</sub> =
			<?php printf("%s x %s g = %3.3f g",
				$_data->fmt('fa'), $_data->fmt('ss'),
				round($_data->num('fa') * $_data->num('ss'), $PRECISION)); ?>
		</li>
		<li>
			<span>Provided as a reference for Equation (2-5):</span>
			S<sub>X1,BSE-2N</sub> = F<sub>v</sub>S<sub>1,BSE-2N</sub> =
			<?php printf("%s x %s g = %3.3f g",
				$_data->fmt('fv'), $_data->fmt('s1'),
				round($_data->num('fv') * $_data->num('s1'), $PRECISION)); ?>
		</li>
		<li>
			<span>Equation (2&ndash;4):</span>
			S<sub>XS,BSE-1N</sub> = &#8532; x S<sub>XS,BSE-2N</sub> =
			<?php printf("&#8532; x %3.3f g = %3.3f g",
				round($_data->num('fa') * $_data->num('ss'), $PRECISION),
				round($_data->num('fa') * $_data->num('ss'), $PRECISION) * 2 / 3); ?>
		</li>
		<li>
			<span>Equation (2&ndash;5):</span>
			S<sub>X1,BSE-1N</sub> = &#8532; x S<sub>X1,BSE-2N</sub> =
			<?php printf("&#8532; x %3.3f g = %3.3f g",
				round($_data->num('fv') * $_data->num('s1'), $PRECISION),
				round($_data->num('fv') * $_data->num('s1'), $PRECISION) * 2 / 3); ?>
		</li>
		<?php } else if ($_EDITION_VARIANT == 'BSE-2E') { ?>
		<li>
			<span>Provided as a reference for Equation (2-4):</span>
			F<sub>a</sub><?php print $LABELS['ss_raw'] ?> =
			<?php printf('%s x %s g = %3.3f g',
				$_data->fmt('fa'),
				$_data->fmt('ss'),
				round($_data->num('fa') * $_data->num('ss'), $PRECISION)
			); ?>
		</li>
		<li>
			<span>Provided as a reference for Equation (2-5):</span>
			F<sub>v</sub><?php print $LABELS['s1_raw'] ?> =
			<?php printf('%s x %s g = %3.3f g',
				$_data->fmt('fv'),
				$_data->fmt('s1'),
				round($_data->num('fv') * $_data->num('s1'), $PRECISION)
			); ?>
		</li>
		<li>
			<span>Provided as a reference for Equation (2-4):</span>
			S<sub>XS,BSE-2N</sub> = F<sub>a</sub>S<sub>S,BSE-2N</sub> =
			<?php echo $_data->fmt('bse_2n_sxs') ?> g
		</li>
		<li>
			<span>Provided as a reference for Equation (2-5):</span>
			S<sub>X1,BSE-2N</sub> = F<sub>v</sub>S<sub>1,BSE-2N</sub> =
			<?php echo $_data->fmt('bse_2n_sx1') ?> g
		</li>
		<li>
			<span>Equation (2&ndash;4):</span>
			S<sub>XS,BSE-2E</sub> = MIN[F<sub>a</sub><?php print $LABELS['ss_raw'] ?>, S<sub>XS,BSE-2N</sub>] =
			<?php printf('MIN[%3.3fg, %sg] = %sg',
				round($_data->num('fa') * $_data->num('ss'), $PRECISION),
				$_data->fmt('bse_2n_sxs'),
				$_data->fmt('srs')
			); ?>
		</li>
		<li>
			<span>Equation (2&ndash;5):</span>
			S<sub>X1,BSE-2E</sub> = MIN[F<sub>v</sub><?php print $LABELS['s1_raw'] ?>, S<sub>X1,BSE-2N</sub>] =
			<?php printf('MIN[%3.3fg, %sg] = %sg',
				round($_data->num('fv') * $_data->num('s1'), $PRECISION),
				$_data->fmt('bse_2n_sx1'),
				$_data->fmt('sr1')
			); ?>
		</li>
		<?php } else if ($_EDITION_VARIANT == 'BSE-1E') { ?>
		<li>
			<span>Provided as a reference for Equation (2-4):</span>
			F<sub>a</sub><?php print $LABELS['ss_raw'] ?> =
			<?php printf('%s x %s g = %3.3f g',
				$_data->fmt('fa'),
				$_data->fmt('ss'),
				round($_data->num('fa') * $_data->num('ss'), $PRECISION)
			); ?>
		</li>
		<li>
			<span>Provided as a reference for Equation (2-5):</span>
			F<sub>v</sub><?php print $LABELS['s1_raw'] ?> =
			<?php printf('%s x %s g = %3.3f g',
				$_data->fmt('fv'),
				$_data->fmt('s1'),
				round($_data->num('fv') * $_data->num('s1'), $PRECISION)
			); ?>
		</li>
		<li>
			<span>Provided as a reference for Equation (2-4):</span>
			S<sub>XS,BSE-1N</sub> = &#8532; x S<sub>XS,BSE-2N</sub> = &#8532; x F<sub>a</sub>S<sub>S,BSE-2N</sub> =
			<?php echo $_data->fmt('bse_1n_sxs'); ?> g
		</li>
		<li>
			<span>Provided as a reference for Equation (2-5):</span>
			S<sub>X1,BSE-1N</sub> = &#8532; x S<sub>X1,BSE-2N</sub> = &#8532; x F<sub>v</sub>S<sub>1,BSE-2N</sub> =
			<?php echo $_data->fmt('bse_1n_sx1'); ?> g
		</li>
		<li>
			<span>Equation (2&ndash;4):</span>
			S<sub>XS,BSE-1E</sub> = MIN[F<sub>a</sub><?php print $LABELS['ss_raw'] ?>, S<sub>XS,BSE-1N</sub>] =
			<?php printf('MIN[%3.3fg, %sg] = %sg',
				round($_data->num('fa') * $_data->num('ss'), $PRECISION),
				$_data->fmt('bse_1n_sxs'),
				$_data->fmt('srs')
			); ?>
		</li>
		<li>
			<span>Equation (2&ndash;5):</span>
			S<sub>X1,BSE-1E</sub> = MIN[F<sub>v</sub><?php print $LABELS['ss_raw'] ?>, S<sub>X1,BSE-1N</sub>] =
			<?php printf('MIN[%3.3fg, %sg] = %sg',
				round($_data->num('fv') * $_data->num('s1'), $PRECISION),
				$_data->fmt('bse_1n_sx1'),
				$_data->fmt('sr1')
			); ?>
		</li>
		<?php } else { ?>
		<li>
			<span>Equation (2&ndash;4):</span>
			<?php echo $LABELS['ss_mod'] ?></sub> = F<sub>a</sub><?php echo $LABELS['ss_raw'] ?></sub> =
			<?php printf("%s x %s g = %3.3f g",
				$_data->fmt('fa'), $_data->fmt('ss'),
				round($_data->num('fa') * $_data->num('ss'), $PRECISION)); ?>
		</li>
		<li>
			<span>Equation (2&ndash;5):</span>
			<?php echo $LABELS['s1_mod'] ?></sub> = F<sub>v</sub><?php echo $LABELS['s1_raw'] ?></sub> =
			<?php printf("%s x %s g = %3.3f g",
				$_data->fmt('fv'), $_data->fmt('s1'),
				round($_data->num('fv') * $_data->num('s1'), $PRECISION)); ?>
		</li>
		<?php } ?>
	</ul>

	<h2>
		Section 2.4.1.7.1 &mdash; General Horizontal Response Spectrum
	</h2>
	<div class="mapwrapper">
		<span class="imagecaption">Figure 2-1. General Horizontal Response Spectrum</span>
		<?php outputSpectrumChart(
			$spectra['horizontal'],
			580,
			360,
			true,
			array(
				'subscript' => 'X',
				'sds_label' => 'S_{Xs} / B_1',
				'y_intercept' => '0.4S_{Xs}',
				'sd1_label' => 'S_{X1} / B_1'
			)
		); ?>
	</div>

<?php last_page() ?>

	<h2>
		Section 2.4.1.7.2 &mdash; General Vertical Response Spectrum
	</h2>
	<div class="mapwrapper">
		<span class="imagecaption">
			The General Vertical Response Spectrum is
			determined by multiplying the General Horizontal Response Spectrum
			by &#8532;.
		</span>
		<?php outputSpectrumChart(
			$spectra['vertical'],
			580,
			360,
			true,
			array(
				'subscript' => 'Xv',
				'sds_label' => '2S_{Xs} / 3B_1',
				'y_intercept' => '0.8S_{Xs} / 3',
				'sd1_label' => '2S_{X1} / 3B_1'
			)
		); ?>
	</div>
