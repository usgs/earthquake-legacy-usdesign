<?php
	ini_set('error_reporting', E_ALL);
	include_once $APP_WEB_DIR . '/inc/constants.inc.php';

	$coords = proj2xy($_latitude, $_longitude, $_region);
	$xpos = $coords[0]; $ypos = $coords[1];
?>

	<h2>
		Section 11.4.1 &mdash; Mapped Acceleration Parameters and Risk
		Coefficients
	</h2>

	<p>
	Note: Ground motion values contoured on Figures 22-1, 2, 5, &amp; 6 below
	are for the direction of maximum horizontal spectral response acceleration.
	They have been converted from corresponding geometric mean ground motions
	computed by the USGS by applying factors of 1.1 (to obtain S<sub>SUH</sub>
	and S<sub>SD</sub>) and 1.3 (to obtain S<sub>1UH</sub> and S<sub>1D</sub>).
	Maps in the Proposed 2015 NEHRP Provisions are provided for Site Class B.
	Adjustments for other Site Classes are made, as needed, in Section 11.4.3.
	</p>

	<div class="mapwrapper">
		<span class="imagecaption">
			Figure 22&ndash;1: Uniform&ndash;Hazard (2&#37; in 50&ndash;Year)
			Ground Motions of 0.2-Second Spectral Response Acceleration (5&#37;
			of Critical Damping), Site Class B
		</span>
		<?php
			$img_url_ssuh = 'images/imageplotter.php?type=S_SUH&amp;region=' .
					$_region . '&amp;ypos=' . $ypos . '&amp;xpos=' . $xpos .
					'&amp;val=' .  $_data->fmt('ssuh') . '&amp;edition=nehrp-2009' .
					'&amp;units=g';
		?>
		<img src="<?php print $img_url_ssuh; ?>"
			alt="SSUH = <?php print $_data->fmt('ssuh'); ?>" />
	</div>

	<div class="mapwrapper">
		<span class="imagecaption">
			Figure 22&ndash;2: Uniform&ndash;Hazard (2&#37; in 50&ndash;Year)
			Ground Motions of 1.0-Second Spectral Response Acceleration (5&#37;
			of Critical Damping), Site Class B
		</span>
		<?php
			$img_url_s1uh = 'images/imageplotter.php?type=S_1UH&amp;region=' .
					$_region . '&amp;ypos=' . $ypos . '&amp;xpos=' . $xpos .
					'&amp;val=' .  $_data->fmt('s1uh') . '&amp;edition=nehrp-2009' .
					'&amp;units=g';
		?>
		<img src="<?php print $img_url_s1uh; ?>"
				alt="S1UH = <?php print $_data->fmt('s1uh'); ?>" />
	</div>

<?php new_page() ?>

	<div class="mapwrapper">
		<span class="imagecaption">
			Figure 22&ndash;3: Risk Coefficient at 0.2-Second Spectral Response
			Period
		</span>
		<?php
			$img_url_crs = 'images/imageplotter.php?type=C_RS&amp;region=' .
					$_region . '&amp;ypos=' . $ypos . '&amp;xpos=' . $xpos .
					'&amp;val=' .  $_data->fmt('crs');
		?>
		<img src="<?php print $img_url_crs; ?>"
				alt="CRS = <?php print $_data->fmt('crs'); ?>" />
	</div>

	<div class="mapwrapper">
		<span class="imagecaption">
			Figure 22&ndash;4: Risk Coefficient at 1.0-Second Spectral Response
			Period
		</span>
		<?php
			$img_url_cr1 = 'images/imageplotter.php?type=C_R1&amp;region=' .
					$_region . '&amp;ypos=' . $ypos . '&amp;xpos=' . $xpos .
					'&amp;val=' .  $_data->fmt('cr1');
		?>
		<img src="<?php print $img_url_cr1; ?>"
				alt="CR1 = <?php print $_data->fmt('cr1'); ?>" />
	</div>

<?php new_page() ?>

   <div class="mapwrapper">
      <span class="imagecaption">
      	Figure 22&ndash;5: Deterministic Ground Motions of 0.2-Second Spectral
			Response Acceleration (5&#37; of Critical Damping), Site Class B
      </span>
      <?php
         $img_url_ssd = 'images/imageplotter.php?type=S_SD&amp;region=' .
				$_region . '&amp;ypos=' . $ypos . '&amp;xpos=' . $xpos .
				'&amp;val=' .  $_data->fmt('ssd') .
				'&amp;units=g';
      ?>
      <img src="<?php print $img_url_ssd; ?>"
            alt="SSD = <?php print $_data->fmt('ssd'); ?>" />
   </div>

   <div class="mapwrapper">
      <span class="imagecaption">
         Figure 22&ndash;6: Deterministic Ground Motions of 1.0-Second Spectral
			Response Acceleration (5&#37; of Critical Damping), Site Class B
      </span>
      <?php
			$img_url_s1d = 'images/imageplotter.php?type=S_1D&amp;region=' .
					$_region . '&amp;ypos=' . $ypos . '&amp;xpos=' . $xpos .
					'&amp;val=' .  $_data->fmt('s1d') .
					'&amp;units=g';
      ?>
      <img src="<?php print $img_url_s1d; ?>"
            alt="S1D = <?php print $_data->fmt('s1d'); ?>" />
   </div>

<?php new_page() ?>

	<h2>Section 11.4.2 &mdash; Site Class</h2>
	<p>
		The authority having jurisdiction (not the USGS), site-specific
		geotechnical data, and/or the default has classified the site as
		<?php print $SITE_CLASS_ARR[$_siteclass]; ?>, based on the site soil
		properties in accordance with Chapter 20.
	</p>
	<span class="imagecaption">Table 20.3&ndash;1 Site Classification</span>

	<?php include_once 'inc/dcodes/siteclass/usgs-2008.inc.php'; ?>

	<h2>
		Section 11.4.3 &mdash; Site Coefficients, Risk Coefficients, and
		Risk&ndash;Targeted Maximum Considered Earthquake (<abbr title="Maximum
		Considered Earthquake"
		>MCE<sub>R</sub></abbr>) Spectral Response Acceleration Parameters
	</h2>
	<ul class="equations">
		<li>
			<span>Equation (11.4&ndash;1):</span>
			C<sub>RS</sub>S<sub>SUH</sub> =
			<?php printf("%s x %s = %s g", $_data->fmt('crs'), $_data->fmt('ssuh'),
				dataFormat($_data->num('crs') * $_data->num('ssuh'))); ?>
		</li>
		<li>
			<span>Equation (11.4&ndash;2):</span>
			S<sub>SD</sub> = <?php print $_data->fmt('ssd'); ?> g
		</li>
		<li class="summary">
			S<sub>S</sub> &equiv; &ldquo;Lesser of values from Equations
			(11.4&ndash;1) and (11.4&ndash;2)&rdquo; =
			<?php print $_data->fmt('ss'); ?> g
		</li>
		<li>
			<span>Equation (11.4&ndash;3):</span>
			C<sub>R1</sub>S<sub>1UH</sub> =
			<?php printf("%s x %s = %s g", $_data->fmt('cr1'), $_data->fmt('s1uh'),
				dataFormat($_data->num('cr1') * $_data->num('s1uh'))); ?>
		</li>
		<li>
			<span>Equation (11.4&ndash;4):</span>
			S<sub>1D</sub> = <?php print $_data->fmt('s1d'); ?> g
		</li>
		<li class="summary">
			S<sub>1</sub> &equiv; &ldquo;Lesser of values from Equations
			(11.4&ndash;3) and (11.4&ndash;4)&rdquo; =
			<?php print $_data->fmt('s1'); ?> g
		</li>
	</ul>

<?php new_page() ?>

	<div class="mapwrapper" style="margin:1em 0;">
	<?php
		$fafvcalc = new FaFvCalc();
		print $fafvcalc->getFaTableMarkup($_data->num('ss'), $_siteclass, array(
			'title' => 'Spectral Response Acceleration Parameter at Short Period',
			'caption' => 'Table 11.4&ndash;1: Site Coefficient F<sub>a</sub>',
		));
	?>
	</div>

	<div class="mapwrapper" style="margin:5em 0 1em;">
	<?php
		print $fafvcalc->getFvTableMarkup($_data->num('s1'), $_siteclass, array(
			'title' => 'Spectral Response Acceleration Parameter at '. '
				1&ndash;Second Period',
			'caption' => 'Table 11.4&ndash;2: Site Coefficient F<sub>v</sub>',
		));
	?>
	</div>

<?php new_page() ?>

	<ul class="equations">
		<li>
			<span>Equation (11.4&ndash;5):</span>
			S<sub>MS</sub> = F<sub>a</sub>S<sub>S</sub> =
			<?php printf("%s x %s = %s g", $_data->fmt('fa'), $_data->fmt('ss'), $_data->fmt('srs')); ?>
		</li>
		<li>
			<span>Equation (11.4&ndash;6):</span>
			S<sub>M1</sub> = F<sub>v</sub>S<sub>1</sub> =
			<?php printf("%s x %s = %s g", $_data->fmt('fv'), $_data->fmt('s1'), $_data->fmt('sr1')); ?>
		</li>
	</ul>

	<h2>Section 11.4.4 &mdash; Design Spectral Acceleration Parameters</h2>
	<ul class="equations">
		<li>
			<span>Equation (11.4&ndash;7):</span>
			S<sub>DS</sub> = &#8532; S<sub>MS</sub> =
			<?php print "&#8532; x " . $_data->fmt('srs') . ' = ' .  $_data->fmt('sds'); ?> g
		</li>
		<li>
			<span>Equation (11.4&ndash;8):</span>
			S<sub>D1</sub> = &#8532; S<sub>M1</sub> =
			<?php print "&#8532; x " . $_data->fmt('sr1') . ' = ' .  $_data->fmt('sd1'); ?> g
		</li>
	</ul>

	<h2>Section 11.4.5 &mdash; Design Response Spectrum</h2>
	<div class="mapwrapper">
		<span class="imagecaption">
			Figure 22&ndash;7: Long&ndash;period Transition Period, T<sub>L</sub>
			(s)
		</span>
		<?php
		 $t_coords = proj2xy($_latitude, $_longitude, $_region);
		 $t_xpos = $t_coords[0];
		 $t_ypos = $t_coords[1];

         $img_url_tl = 'images/imageplotter.php?type=T_L&amp;region='.$_region.
				'&amp;ypos=' .  $t_ypos . '&amp;xpos=' . $t_xpos . '&amp;val=' .
				urlencode($_data->fmt('tl', 0) ?: 'N/A') . '&amp;units=seconds';
      ?>
      <img src="<?php print $img_url_tl; ?>" alt="TL" />
	</div>

<?php new_page() ?>

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

	<h2>
   		Section 11.4.6 &mdash; <abbr title="Maximum Considered Earthquake"
		>MCE<sub>R</sub></abbr> Response Spectrum
	</h2>
   <div class="mapwrapper">
		<span class="imagecaption">
			The MCE<sub>R</sub> response spectrum is determined by multiplying
			the design response spectrum above by 1.5.
		</span>
		<?php outputSpectrumChart(
			$_data->spectrum[1],
			550,
			360,
			true
		); ?>
	</div>

<?php last_page() ?>

	<h2>
		Section 11.8.3 &mdash; Additional Geotechnical Investigation Report
		Requirements for Seismic Design Categories D through F
	</h2>
	<?php
		print $fafvcalc->getFpgaTableMarkup($_data->num('pga'), $_siteclass, array(
			'title' => 'Mapped MCE Geometric Mean Peak Ground Acceleration, PGA',
			'caption' => 'Table 11.8&ndash;1: Site Coefficient F<sub>PGA</sub>',
		));
	?>
	<ul class="equations">
		<li>
			<span>Mapped PGA</span>
			PGA =
			<?php print $_data->fmt('pga'); ?> g
		</li>
		<li>
			<span>Equation (11.8&ndash;1):</span>
			PGA<sub>M</sub> = F<sub>PGA</sub>PGA =
			<?php printf("%s x %s = %s g", $_data->fmt('fpga'), $_data->fmt('pga'),
				dataFormat($_data->num('fpga') * $_data->num('pga'))); ?>
		</li>
	</ul>
