<?php
	$APP_WEB_DIR = (isset($_SERVER['APP_WEB_DIR'])) ?
		$_SERVER['APP_WEB_DIR'] : $_SERVER['REDIRECT_APP_WEB_DIR'];
	
	include_once $APP_WEB_DIR . '/inc/constants.inc.php';
	include_once $APP_WEB_DIR . '/inc/designcategory.inc.php';
?>
	<h2>Article 3.4.1 &mdash; Design Spectra Based on General Procedure</h2>

	<p>
		Note: Maps in the 2009 AASHTO Specifications are provided by AASHTO for Site Class B.
		Adjustments for other Site Classes are made, as needed, in Article 3.4.2.3.
	</p>

	<?php
		$a_s = $_data->num('fpga') * $_data->num('pga');
	?>

	<ul class="equations">
        <li>
            <span>From <?php map_link('pga') ?></span>
            PGA = <?php print $_data->fmt('pga'); ?> g
        </li>
		<li>
			<span>From <?php map_link('s_s') ?></span>
			S<sub>S</sub> = <?php print dataFormat($_data->ss); ?> g
		</li>
		<li>
			<span>From <?php map_link('s_1') ?></span>
			S<sub>1</sub> = <?php print dataFormat($_data->s1); ?> g
		</li>
	</ul>

<?php new_page() ?>

	<h2>Article 3.4.2.1 &mdash; Site Class Definitions</h2>
	<p>
		The authority having jurisdiction (not the USGS), site-specific
		geotechnical data, and/or the default has classified the site as
		<?php print $SITE_CLASS_ARR[$_siteclass]; ?>, based on the site soil 
		properties in accordance with Article 3.4.2.
	</p>
	<span class="imagecaption">Table 3.4.2.1&ndash;1 Site Class Definitions</span>

	<?php include_once 'inc/dcodes/siteclass/usgs-2002.inc.php'; ?>

	<?php
		$fafvcalc = new FaFvCalc();
		$_fa      = $fafvcalc->getFa($_data->num('ss'), $_siteclass);
		$_fv      = $fafvcalc->getFv($_data->num('s1'), $_siteclass);
		$_fpga    = $fafvcalc->getFpga($_data->num('pga'), $_siteclass);
	?>

<?php new_page() ?>

	<h2>Article 3.4.2.3 &mdash; Site Coefficients</h2>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFpgaTableMarkup($_data->num('pga'), $_siteclass, array(
			'title' => 'Mapped Peak Ground Acceleration',
			'caption' => 'Table 3.4.2.3-1 (for F<sub>pga</sub>)&mdash;Values of ' .
				'F<sub>pga</sub> as a Function of Site Class ' .
				'and Mapped Peak Ground Acceleration Coefficient',
			'see_also' => 'See AASHTO Article 3.4.3',
		));
	?>
	</div>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFaTableMarkup($_data->num('ss'), $_siteclass, array(
			'title' => 'Spectral Response ' .
				'Acceleration Parameter at Short Periods',
			'caption' => 'Table 3.4.2.3-1 (for F<sub>a</sub>)&mdash;Values of ' .
				'F<sub>a</sub> as a Function of Site Class ' .
				'and Mapped Short-Period Spectral Acceleration Coefficient',
			'see_also' => 'See AASHTO Article 3.4.3',
		));
	?>
	</div>

<?php new_page() ?>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFvTableMarkup($_data->num('s1'), $_siteclass, array(
			'title' => 'Mapped Spectral Response Acceleration Coefficient at ' .
				'1-sec Periods',
			'caption' => 'Table 3.4.2.3-2&mdash;Values of F<sub>v</sub> as a Function ' .
				'of Site Class and Mapped 1-sec Period Spectral Acceleration ' .
				'Coefficient',
			'see_also' => 'See AASHTO Article 3.4.3',
		));
	?>
	</div>

	<ul class="equations">
		<li>
			<span>Equation (3.4.1-1):</span>
			A<sub>S</sub> = F<sub>PGA</sub> PGA =
			<?php echo $_data->fmt('fpga'), ' x ', $_data->fmt('pga'), ' = ', dataFormat($a_s); ?> g
		</li>
		<li>
			<span>Equation (3.4.1-2):</span>
			S<sub>DS</sub> = F<sub>a</sub> S<sub>S</sub> =
			<?php echo $_data->fmt('fa'), ' x ', $_data->fmt('ss'), ' = ', $_data->fmt('sds'); ?> g
		</li>
		<li>
			<span>Equation (3.4.1-3):</span>
			S<sub>D1</sub> = F<sub>v</sub> S<sub>1</sub> =
			<?php echo $_data->fmt('fv'), ' x ', $_data->fmt('s1'), ' = ', $_data->fmt('sd1'); ?> g
		</li>
	</ul>
	
	<div class="mapwrapper">
		<span class="imagecaption">
			Figure 3.4.1-1: Design Response Spectrum
		</span>
		<?php outputSpectrumChart(
			$_data->spectrum[0],
			550,
			360,
			true,
			array('y_intercept' => 'A_S')
		); ?>
	</div>

<?php new_page() ?>

	<h2>Article 3.5 - Selection of Seismic Design Category (SDC)</h2>
	<div class="mapwrapper" style="margin: 16px 0">
		<span class="imagecaption">
			Table 3.5-1&mdash;Partitions for Seismic Design Categories A, B, C, 
			and D
		</span>
		<?php outputDCTable(
			$_riskcategory,
			'sd1-aashto', $_data->num('sd1'), 
			$_data->designcategory
		); ?>
	</div>
	<ul class="equations">
		<li class="summary">
			Seismic Design Category &equiv; &ldquo;the design
			category in accordance with Table 3.5-1&rdquo;
			= <?php print $_data->designcategory ?>
		</li>
	</ul>
