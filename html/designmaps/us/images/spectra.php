<?php
	include_once '../inc/appconfig.inc.php';
	include_once '../inc/constants.inc.php';
	include_once $APP_WEB_DIR . '/inc/drawing.inc.php';
	use drawing\text;

	/**
	 * This file performs some stripped down functionality similar to the Google
	 * Charts API. We re-implement it here to add some special features like
	 * labeling axes and plotting special lines on the chart to highlight
	 * important parts of the chart. This is NOT a fully functional
	 * implementation of the chart API and is only used to plot spectra. The
	 * reference to the Google API is because the original spectra were plotted
	 * using it and the query string parameters are still consistent with the
	 * API.
	 *
	 * -=* CHANGE LOG *=-
	 * 
	 *
	 * @author  Eric Martinez
	 * @version 0.0.1
	 */

	//--------------------------------------------------------------------------
	// Some Useful Functions
	//--------------------------------------------------------------------------

	/**
	 * Accepts a number and a precision and returns that number rounded down to 
	 * the nearest precision value.
	 *
	 * @param $val The value to floor.
	 * @param $precision The precision to which the value should be floored.
	 *
	 * @return The input value rounded down to the nearest precision.
	 */
	function precisionFloor($val, $precision) {
		if ($val < 0.0) { return -1 * precisionCeil(abs($val), $precision); }
		$idx = intval($val/$precision);
		$rem = doubleval($val - ($precision * $idx));
		if ($rem == 0.0) { return $val; }
		return $precision * $idx;
	}

	/**
	 * Accepts a number and a precision and returns that number rounded up to
	 * the nearest precision value.
	 *
	 * @param $val The value to ceil.
	 * @param $precision The precision to which teh value should be ceiled.
	 *
	 * @return The input value rounded up to the nearest precision.
	 */
	function precisionCeil($val, $precision) {
		if ($val < 0.0) { return -1 * precisionFloor(abs($val), $precision); }
		$idx = intval($val / $precision);
		$rem = doubleval($val - ($precision * $idx));
		if ($rem == 0.0) { return $val; }
		return $precision * ($idx + 1);
	}

	/**
	 * Tries to read in a query parameter, dies if it wasn't sent.
	 */
	function require_param($name) {
		$value = param($name, null);

		if ($value === null) die('Parameter "' . $name . '" was not sent');

		return $value;
	}

	function getPointValue($needle, $xvals, $yvals) {
		foreach ($xvals as $i => $x) {
			if ($needle < $x) break;

			$last_i = $i;
		}

		$x1 = $xvals[$last_i];
		$y1 = $yvals[$last_i];

		if ($last_i == count($xvals) - 1) {
			return $y1;
		} else {
			$x2 = $xvals[$last_i + 1];
			$y2 = $yvals[$last_i + 1];
		}

		if ($y1 == $y2) return $y1;

		return ($y2 - $y1) / ($x2 - $x1) * ($needle - $x1) + $y1;
	}

	//--------------------------------------------------------------------------
	// Get the chart dimensions
	//--------------------------------------------------------------------------
	list($WIDTH, $HEIGHT) = explode('x', param('chs', '500x360'));

	//--------------------------------------------------------------------------
	// Get the chart data
	//--------------------------------------------------------------------------
	list($chart_data_type, $chart_data) = explode(':', require_param('chd'));
	list($chart_xdata, $chart_ydata) = explode('|', $chart_data);
	$XVALS = explode(',', $chart_xdata);
	$YVALS = explode(',', $chart_ydata);

	//--------------------------------------------------------------------------
	// Get the chart min/max values
	//--------------------------------------------------------------------------
	list($chart_xrange, $chart_yrange) = explode('|', require_param('chxr'));
	list(, $XMIN, $XMAX) = explode(',', $chart_xrange);
	list(, , $YMAX) = explode(',', $chart_yrange);

	$YMIN = 0;

	//--------------------------------------------------------------------------
	// Get the chart axis labels
	//--------------------------------------------------------------------------
	$DETAILED = param('detailed', 'false')=='true';

	$tmp = param('clabels', 'Period, T (sec)|Sa (g)');
	if ($DETAILED) {
		$tmp = param('clabels', 
				'Period, T (sec)|Spectral Response Acceleration, Sa (g)');
	}

	list($X_LABEL, $Y_LABEL) = explode('|', $tmp);

	$NAME = param('chtt', '');
	$SUBSCRIPT = param('subscript', 'D');

	$Y_INTERCEPT_LABEL = param('y_intercept', '');

	//--------------------------------------------------------------------------
	// Some other configuration parameters for the chart.
	//--------------------------------------------------------------------------
	$PATTERNS = array(
		'title' => array(
			'font_family' => 'Bitstream Vera Sans',
			'font_size' => 12,
			'font_weight' => CAIRO_FONT_WEIGHT_BOLD,
		),
		'background' => 'FFFFFF',
		'axis-label' => array(
			'font_family' => 'Bitstream Vera Sans',
			'font_weight' => CAIRO_FONT_WEIGHT_BOLD,
			'font_size' => 10,
		),
		'axis-units' => array(
			'color' => '333333',
			'font_family' => 'Bitstream Vera Sans',
			'font_size' => 8,
		),
		'axis-line' => array(
			'color' => '333333',
			'line_width' => 1
		),
		'value-label' => array(
			'color' => '333333',
			'font_family' => 'Bitstream Vera Sans',
			'font_size' => 11,
		),
		'value-line' => array(
			'color' => '666666',
			'line_width' => 1,
			'line_dash' => array(4, 4)
		),
		'line' => array(
			'color' => param('chco', '990000'),
			'line_width' => 1.5,
			'line_dash' => array()
		),
		'highlight' => '666666',
	);

	$TOP_PADDING = 4;
	$RIGHT_PADDING = 12;
	$BOTTOM_PADDING = 8;
	$LEFT_PADDING = 4;

	$INTER_PADDING = 4;
	$LEGEND_PADDING = 0;

	//--------------------------------------------------------------------------
	// Start plotting stuff
	//--------------------------------------------------------------------------
	$surface = cairo_image_surface_create(CAIRO_FORMAT_ARGB32, $WIDTH, $HEIGHT);
	$cr = new CairoContext($surface);

	drawing\applyPattern($cr, 'background');
	$cr->paint();

	// Lay out the chart
	if (isset($NAME) && $NAME != '') {
		if (preg_match('/(.*)_([[:alnum:]]+)(.*)/', $NAME, $matches)) {
			list(, $before, $subscript, $after) = $matches;

			$NAME = array($before, text\sub($subscript), $after);
		}

		$title = text\layout(
			$cr,
			$NAME,
			array('pattern' => 'title')
		);
	}

	$x_axis_label = text\layout(
		$cr,
		$X_LABEL,
		array('pattern' => 'axis-label')
	);

	$y_axis_label = text\layout(
		$cr,
		$Y_LABEL,
		array('pattern' => 'axis-label')
	);

	$x_layout = drawing\layoutElements(
		$WIDTH,
		array(
			$LEFT_PADDING,
			'axis-label' => $y_axis_label['height'],
			$INTER_PADDING * 2,
			'axis-ticks' => ($DETAILED ? 115 : 25),
			'chart' => 'flex',
			$RIGHT_PADDING
		)
	);

	$y_layout = drawing\layoutElements(
		$HEIGHT,
		array(
			$TOP_PADDING,
			'title' => ($NAME ? ($title['height'] + $INTER_PADDING) : 0),
			'chart' => 'flex',
			$INTER_PADDING,
			'axis-ticks' => 10,
			$INTER_PADDING,
			'axis-label' => $x_axis_label['height'],
			$BOTTOM_PADDING
		)
	);

	// Align strokes to center of pixels
	$cr->translate(0.5, 0.5);

	if (isset($title)) {
		$cr->moveTo(
			drawing\center($title['width'], $WIDTH),
			$y_layout['title']['start']
		);
		text\draw($cr, $title);
	}

	drawing\applyPattern($cr, 'axis-line');

	$cr->moveTo($x_layout['chart']['start'], $y_layout['chart']['start']);
	// Draw the vertical Y-Axis line
	$cr->relLineTo(0, $y_layout['chart']['length']);
	// Draw the horizontal X-Axis line
	$cr->relLineTo($x_layout['chart']['length'], 0);
	$cr->stroke();

	drawing\applyPattern($cr, 'axis-label');

	// Label the X-Axis
	$cr->moveTo(
		(
			$x_layout['chart']['start'] + 
			drawing\center(
				$x_axis_label['width'],
				$x_layout['chart']['length']
			)
		),
		$y_layout['axis-label']['start']
	);
	text\draw($cr, $x_axis_label);

	// Label the Y-Axis
	$cr->save();
	$cr->moveTo(
		$x_layout['axis-label']['start'],
		(
			$y_layout['chart']['start'] + 
			drawing\center(
				$y_axis_label['width'],
				$y_layout['chart']['length']
			) +
			$y_axis_label['width']
		)
	);
	$cr->rotate(-M_PI / 2);
	text\draw($cr, $y_axis_label);
	$cr->restore();

	// Calculate scale
	$dxdelta = precisionCeil(($XMAX - $XMIN) / 10, 0.01); // Show 10 axis units
	$dxstart = precisionFloor($XMIN, $dxdelta);
	$dxend   = precisionCeil($XMAX, $dxdelta);
	$dxlength = $dxend - $dxstart;
	$dxscale = $x_layout['chart']['length'] / $dxlength;

	$dydelta = precisionCeil(($YMAX - $YMIN) / 10, 0.01); // Show 10 axis units
	$dystart = precisionFloor($YMIN, $dydelta);
	$dyend   = precisionCeil($YMAX, $dydelta) + $dydelta;
	$dylength = $dyend - $dystart;
	$dyscale = $y_layout['chart']['length'] / $dylength;

	$cr->translate($x_layout['chart']['start'], $y_layout['chart']['end']);

	if (count($XVALS) != count($YVALS)) die('Number of x values did not match number of y
	values');

	if ($DETAILED) {
		// Add the spectra legend to the upper right-ish area.
		if (file_exists("spectraLegend_${SUBSCRIPT}.png")) {
			$legend = CairoImageSurface::createFromPng("spectraLegend_${SUBSCRIPT}.png");
			# We also have to account for the half-pixel offset done earlier
			$cr->setSourceSurface(
				$legend,
				// 06/20/12 -- EMM: The +10 at the end of this is a fudge factor to 
				// get the legend closer to the right edge of the image
				$x_layout['chart']['length'] - $legend->getWidth() - $LEGEND_PADDING - 0.5 + 10,
				-$y_layout['chart']['length'] + $LEGEND_PADDING - 0.5
			);
			$cr->paint();
		}

		$SDS = getPointValue(0.2, $XVALS, $YVALS);
		$SD1 = getPointValue(1.0, $XVALS, $YVALS);
		$TS = $SD1 / $SDS; $T0 = 0.2 * $TS;

		# Plot the line and label for T_0
		$t0_label = text\layout(
			$cr,
			array(
				'T',
				text\sub('0'),
				' = ',
				number_format($T0, $PRECISION)
			),
			array('pattern' => 'value-label')
		);

		$cr->moveTo(
			($T0 - $dxstart) * $dxscale - $t0_label['width'] * (2/3),
			0
		);
		text\draw($cr, $t0_label);

		drawing\applyPattern($cr, 'value-line');
		$cr->moveTo(round(($T0 - $dxstart) * $dxscale), -round($SDS * $dyscale));
		$cr->relLineTo(0, round($SDS * $dyscale));
		$cr->stroke();

		# Plot the line and label for T_S
		$ts_label = text\layout(
			$cr,
			array(
				'T',
				text\sub('S'),
				' = ',
				number_format($TS, $PRECISION)
			),
			array('pattern' => 'value-label')
		);
		$cr->moveTo(($TS - $dxstart) * $dxscale - $ts_label['width'] * (1/3), 0);
		text\draw($cr, $ts_label);

		drawing\applyPattern($cr, 'value-line');
		$ds_startx = round(($TS - $dxstart) * $dxscale);
		$ds_starty = -1 * round($SDS * $dyscale);
		$cr->moveTo($ds_startx, $ds_starty);
		$cr->relLineTo(0, round($SDS * $dyscale));
		$cr->stroke();

		# Plot the S_D1 lines and labels
		$sd1_label = text\layout(
			$cr,
			array(
				text\parse(param('sd1_label', 'S_{' . $SUBSCRIPT . '1}')),
				' = ',
				number_format($SD1, $PRECISION)
			),
			array('pattern' => 'value-label')
		);
		$sd1_top = -($SD1 * $dyscale) - $sd1_label['height'] / 2;
		$sd1_bottom = -($SD1 * $dyscale) + $sd1_label['height'] / 2;
		$cr->moveTo(
			-$sd1_label['width'] - $INTER_PADDING,
			$sd1_top
		);
		text\draw($cr, $sd1_label);

		drawing\applyPattern($cr, 'value-line');
		$cr->moveTo(0, -round($SD1 * $dyscale));
		$cr->relLineTo(round(1 * $dxscale), 0);
		$cr->relLineTo(0, round($SD1 * $dyscale));
		$cr->stroke();

		$t1_label = text\layout($cr, '1.000', array('pattern' => 'value-label'));

		$t1_startx = 1 * $dxscale - $t1_label['width'] / 2;
		$t1_starty = 0;

		// 06/20/12 -- EMM: Ensure this label doesn't overlap with previous label.
		if (abs($t1_startx - $ds_startx) < $t1_label['width']) {
			$t1_startx += 25;
			$t1_starty -= 20;
		}

		$cr->moveTo($t1_startx, $t1_starty);
		text\draw($cr, $t1_label);

		// Plot the y-intercept label, if any
		if ($Y_INTERCEPT_LABEL) {
			$y_intercept = getPointValue(0, $XVALS, $YVALS);
			$y_inter_label = text\layout(
				$cr,
				array(
					text\parse($Y_INTERCEPT_LABEL),
					' = ',
					number_format($y_intercept, $PRECISION)
				),
				array('pattern' => 'value-label')
			);
			$y_inter_top = -($y_intercept * $dyscale) - $y_inter_label['height'] / 2;
			$y_inter_bottom = -($y_intercept * $dyscale) + $y_inter_label['height'] / 2;

			$draw_y_inter_line = false;

			if ($y_inter_bottom >= $sd1_top && $sd1_top >= $y_inter_top) {
				$y_inter_top = $sd1_top - $y_inter_label['height'] - $INTER_PADDING;
				$draw_y_inter_line = true;
			}

			if ($y_inter_top < $sd1_bottom && $sd1_bottom < $y_inter_bottom) {
				$y_inter_top = $sd1_bottom + $INTER_PADDING;
				$draw_y_inter_line = true;
			}

			
			$cr->moveTo(
				-$y_inter_label['width'] - $INTER_PADDING - ((int) $draw_y_inter_line) * $INTER_PADDING,
				$y_inter_top
			);
			text\draw($cr, $y_inter_label);

			if ($draw_y_inter_line) {
				drawing\applyPattern($cr, 'value-line');
				$cr->moveTo(-$INTER_PADDING, round($y_inter_top + $y_inter_label['height'] / 2));
				$cr->lineTo(0, -round($y_intercept * $dyscale));
				$cr->stroke();
			}
		}

		# Plot the S_DS lines and labels
		$sds_label = text\layout(
			$cr,
			array(
				text\parse(param('sds_label', 'S_{' . $SUBSCRIPT . 'S}')),
				' = ',
				number_format($SDS, $PRECISION)
			),
			array('pattern' => 'value-label')
		);

		$sds_top = -($SDS * $dyscale) - $sds_label['height'] / 2;
		$sds_bottom = -($SDS * $dyscale) + $sds_label['height'] / 2;

		// 06/20/12 -- EMM: Make sure this doesn't overlap with previous label
		if (abs($sds_top - $sd1_top) < $sds_label['height']) {
			$sds_top = $sd1_top + $sds_label['height'];
		}

		$cr->moveTo(
			-$sds_label['width'] - $INTER_PADDING,
			$sds_top
		);
		text\draw($cr, $sds_label);

		drawing\applyPattern($cr, 'value-line');
		$cr->moveTo(0, -round($SDS * $dyscale));
		$cr->relLineTo(round(($T0 - $dxstart) * $dxscale), 0);
		$cr->stroke();
	} else {
		// Add x-axis units (Only show units if simple chart)
		for ($dx = 0; $dx < $dxlength; $dx += $dxdelta) {
			drawing\applyPattern($cr, 'axis-units');
			$cr->moveTo(round($dx * $dxscale), -3);
			$cr->relLineTo(0, 6);
			$cr->stroke();

			$tick_label = text\layout(
				$cr, 
				sprintf('%0.2f', $dxstart + $dx),
				array('pattern' => 'axis-units')
			);

			$cr->moveTo(
				$dx * $dxscale - $tick_label['width'] / 2,
				$INTER_PADDING
			);

			text\draw($cr, $tick_label);
		}

		// Add y-axis units (Only show units if simple chart)
		for ($dy = 0; $dy < $dylength; $dy += $dydelta) {
			drawing\applyPattern($cr, 'axis-units');
			$cr->moveTo(-3, -round($dy * $dyscale));
			$cr->relLineTo(6, 0);
			$cr->stroke();

			$tick_label = text\layout(
				$cr, 
				sprintf('%0.2f', $dystart + $dy),
				array('pattern' => 'axis-units')
			);

			$cr->moveTo(
				-$x_layout['axis-ticks']['length'],
				-$dy * $dyscale - $tick_label['height'] / 2
			);

			text\draw($cr, $tick_label);
		}
	}

	// Plot the graph
	$cr->newPath();
	drawing\applyPattern($cr, 'line');

	foreach(array_map(null, $XVALS, $YVALS) as $pair) {
		list($x, $y) = $pair;

		$cr->lineTo(
			($x - $dxstart) * $dxscale,
			-($y - $dystart) * $dyscale
		);
	}

	$cr->stroke();
	
	header('Content-Type: image/png');
	$surface->writeToPng('php://output');
	exit();
?>
