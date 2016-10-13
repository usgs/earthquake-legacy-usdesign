<?php
	/**
	 * This file reads in a contoured base map (probably created by Ken R.), and
	 * marks a location on the map with the location's value for the mapped
	 * parameter (i.e. SSUH, S1UH, CRS, CR1, etc...).
	 *
	 * @see ImageDictionary.EDITION.inc.php
	 * @TODO Improve plotting algorithm.
	 *
	 * @version 0.0.1
	 * @author  Eric Martinez
	 */
	include_once '../inc/appconfig.inc.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/template/static/functions.inc.php';
	include_once $APP_WEB_DIR . '/inc/constants.inc.php';
	include_once $APP_WEB_DIR . '/inc/drawing.inc.php';
	use drawing\text;

	// Image drawing parameters
	$FONT = 'Bitstream Vera Sans';
	$FONT_SIZE = 12;
	$FONT_WEIGHT = CAIRO_FONT_WEIGHT_BOLD;
	$TEXT_PADDING = 4;
	$POINTER_WIDTH = 6;
	$POINTER_LEN = 9;
	
	// Read in the input values
	$val = param('val', false);
	$ypos  = doubleval(param('ypos', false));
	$xpos  = doubleval(param('xpos', false));
	$type = param('type', false);
	$label = param('label', $type);
	$region = param('region', 'US');
	$units = param('units', '');

	$_edition   = param('edition', 'nehrp-2009');
	$_dcode_info = $DCODES[$_edition];
	# Simple check, also protects us against filename-injection attacks
	if (!isset($_dcode_info)) {
		die("Internal error: unknown edition $_edition");
	}

	// If improper usage, die
	if ($val===false || !$ypos || !$xpos || !$type) die;

	// Read image descriptor
	$BASE_MAP     = $APP_DATA_DIR . '/maps/' . $_edition . "/$region-$type.png";

	// Create the base map
	$base_img = CairoImageSurface::createFromPng($BASE_MAP);
	$img = new CairoImageSurface(CAIRO_FORMAT_RGB24, 525, 375);
	$cr = new CairoContext($img);
	$cr->selectFontFace($FONT, CAIRO_FONT_SLANT_NORMAL, $FONT_WEIGHT);
	$cr->setFontSize($FONT_SIZE);

	$base_offset_x = intval(floor(($img->getWidth() - $base_img->getWidth()) / 2));
	$base_offset_y = intval(floor(($img->getHeight() - $base_img->getHeight()) / 2));

	$cr->setSourceRGB(1, 1, 1);
	$cr->paint();

	$cr->setSourceSurface($base_img, $base_offset_x, $base_offset_y);
	$cr->paint();

	$cr->translate($base_offset_x + $xpos + .5, $base_offset_y + $ypos + .5);

	$UNITS        = ($units ? " $units" : '');

	if (strpos($label, '_') !== FALSE) {
		list($base, $subscript) = explode('_', $label);

		$label = array($base, text\sub($subscript));
	}

	$layout = text\layout(
		$cr,
		array(
			$label,
			' = ',
			text\bold($val),
			$UNITS,
		),
		array(
			'font_family' => 'Bitstream Vera Sans',
			'font_size' => 14,
		)
	);

	$x_layout = drawing\layoutElements($callout_width, array(
		$TEXT_PADDING,
		'inside' => $layout['width'],
		$TEXT_PADDING,
	));

	$y_layout = drawing\layoutElements($callout_height, array(
		$TEXT_PADDING,
		'inside' => $layout['ascent'] + $layout['descent'],
		'callout_bottom' => $TEXT_PADDING,
		$POINTER_LEN,
	));

	if ($xpos > $img->getWidth() / 2) {
		$flip = -1;
	} else {
		$flip = 1;
	}
	
	// Draw callout
	$cr->moveTo(0, 0);
	$cr->relLineTo(0, -$callout_height);
	$cr->relLineTo($callout_width * $flip, 0);
	$cr->relLineTo(0, $y_layout['callout_bottom']['end']);
	$cr->relLineTo(-($callout_width - $POINTER_WIDTH) * $flip, 0);
	$cr->closePath();

	$cr->setSourceRGBA(1, 1, 1, .8);
	$cr->fillPreserve();

	$cr->setLineWidth(1);
	$cr->setSourceRGBA(0, 0, .75, .9);
	$cr->stroke();

	// Draw text
	$cr->translate(
		($flip == -1 ? -$callout_width : 0) + $x_layout['inside']['start'],
		-$callout_height + $y_layout['inside']['start']
	);

	text\draw($cr, $layout);

	// We are outputting PNG content
	header('Content-Type: image/png');
	$img->writeToPng('php://output');
?>
