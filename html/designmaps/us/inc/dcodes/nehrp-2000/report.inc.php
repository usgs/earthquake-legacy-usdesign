<?php
	$APP_WEB_DIR = (isset($_SERVER['APP_WEB_DIR'])) ?
		$_SERVER['APP_WEB_DIR'] : $_SERVER['REDIRECT_APP_WEB_DIR'];
	include_once $APP_WEB_DIR . '/inc/constants.inc.php';

	$coords = proj2xy($_latitude, $_longitude, $_region);
	$xpos = $coords[0]; $ypos = $coords[1];
?>

	<h2>
		Section 11.4.1 &mdash; Mapped Acceleration Parameters and Risk 
		Coefficients
	</h2>

	<div class="mapwrapper">
		<span class="imagecaption">
			Figure 22&ndash;1: Uniform&ndash;Hazard (2&#37; in 50&ndash;Year)
			Ground Motions of 0.2-Second Spectral Response Acceleration (5&#37; 
			of Critical Damping), Site Class B
		</span>
		<?php
			$img_url_ssuh = 'images/imageplotter.php?type=S_SUH&amp;region=' .
					$_region . '&amp;ypos=' . $ypos . '&amp;xpos=' . $xpos .
					'&amp;val=' .  $_ssuhr . '&amp;edition=nehrp-2009' . 
					'&amp;units=g';
		?>
		<img src="<?php print $img_url_ssuh; ?>" 
			alt="SSUH = <?php print $_ssuhr; ?>" />
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
					'&amp;val=' .  $_s1uhr . '&amp;edition=nehrp-2009' . 
					'&amp;units=g';
		?>
		<img src="<?php print $img_url_s1uh; ?>"
				alt="S1UH = <?php print $_s1uhr; ?>" />
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
					'&amp;val=' .  $_crsr;
		?>
		<img src="<?php print $img_url_crs; ?>"
				alt="CRS = <?php print $_crsr; ?>" />
	</div>

	<div class="mapwrapper">
		<span class="imagecaption">
			Figure 22&ndash;4: Risk Coefficient at 1.0-Second Spectral Response 
			Period
		</span>
		<?php
			$img_url_cr1 = 'images/imageplotter.php?type=C_R1&amp;region=' .
					$_region . '&amp;ypos=' . $ypos . '&amp;xpos=' . $xpos .
					'&amp;val=' .  $_cr1r;
		?>
		<img src="<?php print $img_url_cr1; ?>"
				alt="CR1 = <?php print $_cr1r; ?>" />
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
				'&amp;val=' .  $_ssdr . 
				'&amp;units=g';
      ?>
      <img src="<?php print $img_url_ssd; ?>"
            alt="SSD = <?php print $_ssdr; ?>" />
   </div>

   <div class="mapwrapper">
      <span class="imagecaption">
         Figure 22&ndash;6: Deterministic Ground Motions of 1.0-Second Spectral
			Response Acceleration (5&#37; of Critical Damping), Site Class B
      </span>
      <?php
			$img_url_s1d = 'images/imageplotter.php?type=S_1D&amp;region=' .
					$_region . '&amp;ypos=' . $ypos . '&amp;xpos=' . $xpos .
					'&amp;val=' .  $_s1dr . 
					'&amp;units=g';
      ?>
      <img src="<?php print $img_url_s1d; ?>"
            alt="S1D = <?php print $_s1dr; ?>" />
   </div>
	
<?php new_page() ?>

	<h2>Section 11.4.2 &mdash; Site Class</h2>
	<?php include_once 'siteclass.inc.php'; ?>

	<h2>
		Section 11.4.3 &mdash; Site Coefficients, Risk Coefficients and 
		Risk&ndash;Targeted Maximum Considered Earthquake (<abbr title="Maximum
		Considered Earthquake"
		>MCE<sub>R</sub></abbr>) Spectral Response Acceleration Parameters
	</h2>
	<ul class="equations">
		<li>
			<span>Equation (11.4&ndash;1):</span>
			C<sub>RS</sub>S<sub>SUH</sub> = 
			<?php printf("%s x %s = %s g", $_crsr, $_ssuhr, 
				number_format(round($_crs * $_ssuh, $PRECISION), $PREISION)); ?>
		</li>
		<li>
			<span>Equation (11.4&ndash;2):</span>
			S<sub>SD</sub> = <?php print "$_ssdr g"; ?>
		</li>
		<li class="summary">
			S<sub>S</sub> &equiv; &ldquo;Lesser of values from Equations
			(11.4&ndash;1) and (11.4&ndash;2)&rdquo; = <?php print "$_ssr g"; ?>
		</li>
		<li>
			<span>Equation (11.4&ndash;3):</span>
			C<sub>R1</sub>S<sub>1UH</sub> =
			<?php printf("%s x %s = %s g", $_cr1r, $_s1uhr, 
				number_format(round($_cr1 * $_s1uh, $PRECISION), $PRECISION)); ?>
		</li>
		<li>
			<span>Equation (11.4&ndash;4):</span>
			S<sub>1D</sub> = <?php print "$_s1dr g"; ?>
		</li>
		<li class="summary">
			S<sub>1</sub> &equiv; &ldquo;Lesser of values from Equations
			(11.4&ndash;3) and (11.4&ndash;4)&rdquo; = <?php print "$_s1r g"; ?>
		</li>
	</ul>

<?php new_page() ?>

	<div class="mapwrapper" style="margin:1em 0;">
	<?php
		print $fafvcalc->getFaTableMarkup($_ss, $_sc, array(
			'title' => 'Spectral Response Acceleration Parameter at Short Period',
			'caption' => 'Table 11.4&ndash;1: Site Coefficient F<sub>a</sub>',
		));
	?>
	</div>

	<div class="mapwrapper" style="margin:5em 0 1em;">
	<?php
		print $fafvcalc->getFvTableMarkup($_s1, $_sc, array(
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
			<?php printf("%s x %s = %s g", $_far, $_ssr, $_srsr); ?>
		</li>
		<li>
			<span>Equation (11.4&ndash;6):</span>
			S<sub>M1</sub> = F<sub>v</sub>S<sub>1</sub> =
			<?php printf("%s x %s = %s g", $_fvr, $_s1r, $_sr1r); ?>
		</li>
	</ul>

	<h2>Section 11.4.4 &mdash; Design Spectral Acceleration Parameters</h2>
	<ul class="equations">
		<li>
			<span>Equation (11.4&ndash;7):</span>
			S<sub>DS</sub> = &#8532; S<sub>MS</sub> =
			<?php print "&#8532; x $_srsr = $_sdsr g"; ?>
		</li>
		<li>
			<span>Equation (11.4&ndash;8):</span>
			S<sub>D1</sub> = &#8532; S<sub>M1</sub> =
			<?php print "&#8532; x $_sr1r = $_sd1r g"; ?>
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
				urlencode($_tl) . '&amp;units=seconds';
      ?>
      <img src="<?php print $img_url_tl; ?>" alt="TL" />
	</div>

<?php new_page() ?>

   <div class="mapwrapper">
		<span class="imagecaption">
			Figure 11.4&ndash;1: Design Response Spectrum
		</span>
		<img alt="Chart" class="chart" src="<?php 
			print preg_replace('/chtt.*?;/','detailed=true&amp;',
					str_replace(" ", "+", $_images[0])) ?>&amp;chs=550x360" />
	</div>

	<h2>
   		Section 11.4.6 &mdash; <abbr title="Maximum Considered Earthquake"
		>MCE<sub>R</sub></abbr> Response Spectrum
	</h2>
   <div class="mapwrapper">
		<span class="imagecaption">
			The MCE<sub>R</sub> response spectrum is determined by multiplying
			the design response spectrum by 1.5.
		</span>
		<img alt="Chart" class="chart" src="<?php 
			print preg_replace('/chtt.*?;/',
					'detailed=true&amp;',
					str_replace(" ", "+", $_images[1])) ?>&amp;chs=550x360" />
	</div>

<?php last_page() ?>

	<h2>
		Section 11.8.3 &mdash; Additional Geotechnical Investigation Report
		Requirements for Seismic Design Categories D through F
	</h2>
	<ul class="equations">
		<li>
			<span>Mapped PGA</span>
			PGA =
			<?php print "$_pgar g"; ?>
		</li>
		<li>
			<span>Equation (11.8&ndash;1):</span>
			PGA<sub>M</sub> = F<sub>PGA</sub>PGA =
			<?php printf("%s x %s = %s g", $_fpgar, $_pgar, 
				number_format(round($_fpga * $_pga, $PRECISION), $PRECISION)); ?>
		</li>
	</ul>
	<?php
		print $fafvcalc->getFpgaTableMarkup($_pga, $_sc, array(
			'title' => 'Mapped MCE Geometric Mean Peak Ground Acceleration, PGA',
			'caption' => 'Table 11.8&ndash;1: Site Coefficient F<sub>PGA</sub>',
		));
	?>
