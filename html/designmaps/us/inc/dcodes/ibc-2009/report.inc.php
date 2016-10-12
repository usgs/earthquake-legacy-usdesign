<?php
	$APP_WEB_DIR = (isset($_SERVER['APP_WEB_DIR'])) ?
		$_SERVER['APP_WEB_DIR'] : $_SERVER['REDIRECT_APP_WEB_DIR'];

	include_once $APP_WEB_DIR . '/inc/constants.inc.php';
	include_once $APP_WEB_DIR . '/inc/designcategory.inc.php';
?>
	<h2>Section 1613.5.1 &mdash; Mapped acceleration parameters</h2>

	<p>
		Note: Maps in the 2006 and 2009 International Building Code are provided for Site Class B.
		Adjustments for other Site Classes are made, as needed, in Section 1613.5.3.
	</p>

	<ul class="equations">
		<li>
			<span>
				From <?php map_link('s_s') ?>
			</span>
			S<sub>S</sub> = <?php echo $_data->fmt('ss') ?> g
		</li>
		<li>
			<span>
				From <?php map_link('s_1') ?>
			</span>
			S<sub>1</sub> = <?php echo $_data->fmt('s1') ?> g
		</li>
	</ul>


	<h2>Section 1613.5.2 &mdash; Site class definitions</h2>
	<?php include_once 'inc/dcodes/siteclass/usgs-2002.inc.php'; ?>

<?php new_page() ?>

	<?php
		$fafvcalc = new FaFvCalc();
		$_fa      = $fafvcalc->getFa($_data->num('ss'), $_siteclass);
		$_fv      = $fafvcalc->getFv($_data->num('s1'), $_siteclass);
		$_fpga    = $fafvcalc->getFpga($_data->num('pga'), $_siteclass);
	?>

	<h2>
		Section 1613.5.3 &mdash; Site coefficients and adjusted maximum
		considered earthquake spectral response acceleration parameters
	</h2>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFaTableMarkup($_data->num('ss'), $_siteclass, array(
			'title' => 'Mapped Spectral Response Acceleration at Short Period',
			'caption' => 'TABLE 1613.5.3(1)<br />VALUES OF SITE COEFFICIENT F<sub>a</sub>',
		));
	?>
	</div>

	<div class="mapwrapper" style="margin:16px 0;">
	<?php
		print $fafvcalc->getFvTableMarkup($_data->num('s1'), $_siteclass, array(
			'title' => 'Mapped Spectral Response Acceleration at 1&ndash;s Period',
			'caption' => 'TABLE 1613.5.3(2)<br />VALUES OF SITE COEFFICIENT F<sub>v</sub>',
		));
	?>
	</div>

<?php new_page() ?>

	<?php
		// This is temporary and should be offloaded to backend processing.
		$_smsr = dataFormat($_fa * $_data->num('ss'));
		$_sm1r = dataFormat($_fv * $_data->num('s1'));
	?>

	<p>In the equations below, the equation number corresponding to the 2006 edition is listed first, and that corresponding to the 2009 edition is listed second.</p>

	<ul class="equations">
		<li>
			<span>Equation (16-37; 16-36):</span>
			S<sub>MS</sub> = F<sub>a</sub>S<sub>S</sub> = 
			<?php printf("%s x %s = %s g", 
			             $_data->fmt('fa'),
			             $_data->fmt('ss'),
						 $_smsr); ?>
		</li>
		<li>
			<span>Equation (16-38; 16-37):</span>
			S<sub>M1</sub> = F<sub>v</sub>S<sub>1</sub> = 
			<?php printf("%s x %s = %s g", 
			             $_data->fmt('fv'),
			             $_data->fmt('s1'),
						 $_sm1r); ?>
		</li>
	</ul>

	<h2>Section 1613.5.4 &mdash; Design spectral response acceleration parameters</h2>
	<ul class="equations">
		<li>
			<span>Equation (16-39; 16-38):</span>
			S<sub>DS</sub> = &#8532; S<sub>MS</sub> =
			<?php echo "&#8532; x $_smsr = ", $_data->fmt('sds'), ' g'; ?>
		</li>
		<li>
			<span>Equation (16-40; 16-39):</span>
			S<sub>D1</sub> = &#8532; S<sub>M1</sub> =
			<?php echo "&#8532; x $_sm1r = ", $_data->fmt('sd1'), ' g'; ?>
		</li>
	</ul>

<?php last_page() ?>

	<h2>Section 1613.5.6 &mdash; Determination of seismic design category</h2>
	<div class="mapwrapper" style="margin: 16px 0">
		<span class="imagecaption">
			TABLE 1613.5.6(1)<br>SEISMIC DESIGN CATEGORY BASED ON SHORT-PERIOD
			RESPONSE ACCELERATION
		</span>
		<?php outputDCTable(
			$_riskcategory,
			'sds', $_data->num('sds'), 
			$_data->designcategory
		); ?>
	</div>
	<div class="mapwrapper" style="margin: 16px 0">
		<span class="imagecaption">
			TABLE 1613.5.6(2)<br>SEISMIC DESIGN CATEGORY BASED ON 1-SECOND PERIOD
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
	is <strong>E</strong> for buildings in Occupancy Categories I, II, and III, and
	<strong>F</strong> for those in Occupancy Category IV, irrespective of the above.
	</p>
	<ul class="equations">
		<li class="summary">
			Seismic Design Category &equiv; &ldquo;the more severe design
			category in accordance with Table 1613.5.6(1) or 1613.5.6(2)&rdquo;
			= <?php print $_data->designcategory ?>
		</li>
	</ul>
	<p>
	Note: See Section 1613.5.6.1 for alternative approaches to calculating Seismic
	Design Category.
	</p>
