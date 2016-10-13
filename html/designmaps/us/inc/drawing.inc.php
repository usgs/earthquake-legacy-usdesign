<?php
namespace drawing {
	function center($inner, $outer) {
		return $outer / 2 - $inner / 2;
	}

	/**
	 * Converts a CSS color string (RRGGBB in Hex) to an array of the componenet
	 * elements in decimal format.
	 *
	 * @param A six character string identifying the RRGGBB color.
	 * @return An array containing the Red, Green, and Blue components in
	 * decimal format (in that order).
	 */
	function cssToRGB($rrggbb) {
		return array_map(
			'hexdec',
			array_slice(
				explode(' ', chunk_split($rrggbb, 2, ' ')),
				0,
				-1
			)
		);
	}

	function _getPattern($pattern) {
		$pattern_def = $GLOBALS['PATTERNS'][$pattern];

		if (is_string($pattern_def)) {
			// Likely a numeric array, shorthand for color pattern
			$pattern_def = array('color' => $pattern_def);
		}

		return $pattern_def;
	}

	/**
	 * Applies a named set of options to an existing Cairo context.
	 *
	 * If a global $PATTERNS array exists, with the following contents:
	 * array('spam') => array('line_width' => 1);
	 *
	 * then drawing\applyPattern($cr, 'spam') is the same as 
	 * $cr->setLineWidth(1) .
	 *
	 * Recognized keys:
	 *  - color: Sets the source color from an #RRGGBBAA string, where AA is 
	 *    optional.
	 *  - line_width
	 *  - line_dash
	 *  - font_family
	 *  - font_slant
	 *  - font_weight (Setting the slant or weight requires a family.)
	 *  - font_size
	 */
	function applyPattern($cr, $pattern) {
		if (!is_array($pattern)) {
			$pattern = _getPattern($pattern);
		}

		if (isset($pattern['color'])) {
			list($r, $g, $b, $a) = cssToRGB($pattern['color']) + array(3 => 255);

			$cr->setSourceRGBA($r / 255, $g / 255, $b / 255, $a / 255);
		}

		if (isset($pattern['line_width'])) {
			$cr->setLineWidth($pattern['line_width']);
		}

		if (isset($pattern['line_dash'])) {
			$cr->setDash($pattern['line_dash']);
		}
		
		if (isset($pattern['font_family'])) {
			$cr->selectFontFace(
				$pattern['font_family'],
				(isset($pattern['font_slant'])
					? $pattern['font_slant']
					: CAIRO_FONT_SLANT_NORMAL),
				(isset($pattern['font_weight'])
					? $pattern['font_weight']
					: CAIRO_FONT_WEIGHT_NORMAL)
			);
		}

		if (isset($pattern['font_size'])) {
			$cr->setFontSize($pattern['font_size']);
		}
	}

	/**
	 * Lays out a set of graphical elements in a fixed space. Each element in
	 * the passed array should either be a number, or 'flex'.
	 *
	 * This function will then try to assign a 'start', 'end', and 'length' to
	 * each element, based on their order and the length of the other elements.
	 * Any space not taken up by fixed-size elements will be evenly split between
	 * elements that have their length as 'flex'.
	 *
	 * While this function is normally used with a fixed size, if $total_size is
	 * variable, like in layoutElements($total_size, array(...)); it will be set
	 * to the total of the passed sizes. Note that this cannot work with 'flex'
	 * sizes.
	 *
	 * NOTE: The keys and order of the passed array are preserved, so passing an
	 * associative array is encouraged:
	 *
	 * $layout = layoutElements(500, array('foo' => 200, 'bar' => 'flex'));
	 * // $layout['bar']['length'] will be 300
	 *
	 * @param $total_size The size available to lay out elements in. If unset,
	 *                    will use as much space as needed.
	 * @param $sizes An array of dimensions, either fixed numbers or 'flex'.
	 */
	function layoutElements(&$total_size, $sizes) {
		$num_flex = 0;

		if (is_null($total_size)) {
			$total_size = 0;
			foreach ($sizes as $size) {
				if ($size === 'flex') {
					trigger_error(
						'Cannot put "flex"-sized elements in a dynamic container',
						E_USER_ERROR
					);
				} else {
					$total_size += $size;
				}
			}

			$flex_size = 0;
		} else {
			$available_space = $total_size;
			foreach ($sizes as $size) {
				if ($size === 'flex') {
					$num_flex += 1;
				} else {
					$available_space -= $size;
					if ($available_space < 0) {
						trigger_error(
							'Ran out of space while laying out elements',
							E_USER_ERROR
						);
					}
				}
			}

			$flex_size = $available_space / $num_flex;
		}

		$pos = 0;
		$elements = array();

		foreach ($sizes as $key => $size) {
			if ($size === 'flex') $size = $flex_size;

			$elements[$key] = array(
				'start' => $pos,
				'end' => $pos + $size,
				'length' => $size,
			);

			$pos += $size;
		}

		return $elements;
	}
}

namespace drawing\text {
	class _Element {
		public $transforms = array();
		public $content = '';
		public $style;
		public $layout = array();

		function __construct($content, $transforms = array()) {
			$this->content = $content;
			$this->transforms = $transforms;
			$this->style = new _ElementStyle();
		}

		public static function createMultiple($elements, $transforms = array()) {
			$result = array();
			foreach ($elements as $element) {
				if (is_array($element)) {
					$result = array_merge($result, _Element::createMultiple($element, $transforms));
				} else {
					if ($element instanceof _Element) {
						$element->transforms = array_merge($element->transforms, $transforms);
					} else {
						$element = new _Element($element, $transforms);
					}

					$result[] = $element;
				}
			}

			return $result;
		}
	}

	class _ElementStyle {
		public $color;

		public $y_offset;
		public $scale;

		public $font_family;
		public $font_size;
		public $font_slant;
		public $font_weight;
	}

	/**
	 * A layout tag that makes the contents bold.
	 */
	function bold() {
		return _element::createMultiple(func_get_args(), array(function (&$elem_style) {
			$elem_style->font_weight = CAIRO_FONT_WEIGHT_BOLD;
		}));
	}

	/**
	 * A layout tag that scales the contents by $factor.
	 */
	function scale($factor) {
		return _element::createMultiple(func_get_args(), array(function (&$elem_style) use ($factor) {
			$elem_style->scale = $factor;
		}));
	}

	/**
	 * A layout tag that formats the contents like a subscript.
	 */
	function sub() {
		return _element::createMultiple(func_get_args(), array(function (&$elem_style) {
			$elem_style->y_offset = .20;
			$elem_style->scale = .66;
		}));
	}

	/**
	 * Lays out styled text on screen, to be drawn with draw().
	 *
	 * At its most basic, this can be used to find the extents of a text string:
	 *   $layout = drawing\text\layout(
	 *   	$cr,
	 *   	"Hi there!",
	 *   	array('font_family' => 'Arial')
	 *   );
	 *   echo $layout['width'], 'x', $layout['height'];
	 *
	 * This function can also lay out an array of text objects, and use patterns 
	 * from the $PATTERNS array:
	 *   $layout = drawing\text\layout(
	 *   	$cr,
	 *   	array(
	 *   		'This is ',
	 *   		drawing\text\bold('very'),
	 *   		' important. Always use ',
	 *   		drawing\text\sub('sub', drawing\text\bold('scripts')),
	 *   		' when you can.'
	 *   	),
	 *   	array('pattern' => 'instructions')
	 *   );
	 *
	 * @param $cr A useable $cr context. Will not be changed by the function.
	 * @param $elements A text string or array of text objects.
	 * @param $default_style The base style for the string.
	 * @return An array of information about the text:
	 *  'width' => The width of the text,
	 *  'height' => The height of the text,
	 *  'ascent' => The maximum ascent of the text over the starting baseline,
	 *  'descent' => The maximum ascent of the text over the starting baseline
	 */
	function layout($cr, $elements, $default_style) {
		$builtin_style = array(
			'color' => '000000',
			'y_offset' => 0,
			'scale' => 1.0,
			'font_family' => 'Bitstream Vera Sans',
			'font_size' => 10,
			'font_slant' => CAIRO_FONT_SLANT_NORMAL,
			'font_weight' => CAIRO_FONT_WEIGHT_NORMAL,
		);
		$default_style = array_merge($builtin_style, $default_style);

		if (isset($default_style['pattern'])) {
			$default_style = array_merge(
				$default_style,
				\drawing\_getPattern($default_style['pattern'])
			);
		}

		$_get = function ($element, $property) use ($default_style) {
			return (is_null($element->{$property})
				? $default_style[$property]
				: $element->{$property}
			);
		};

		$elements = _Element::createMultiple(
			is_array($elements)
			? $elements
			: array($elements)
		);

		$cr->save();
		$cr->selectFontFace(
			$default_style['font_family'],
			$default_style['font_slant'],
			$default_style['font_weight']
		);
		$cr->setFontSize($default_style['font_size']);
		$base_extents = $cr->fontExtents();

		$tot_advance = 0;
		$max_ascent = 0;
		$max_descent = 0;

		foreach ($elements as $i => &$element) {
			$style =& $element->style;

			foreach ($element->transforms as $transform) {
				$transform($style);
			}

			$element->layout = array_merge(
				$element->layout,
				array(
					'font_size' => (
						$_get($style, 'font_size') *
						$_get($style, 'scale')
					),
					'color' => $_get($style, 'color'),
					'font_family' => $_get($style, 'font_family'),
					'font_slant' => $_get($style, 'font_slant'),
					'font_weight' => $_get($style, 'font_weight')
				)
			);
					
			$cr->selectFontFace(
				$_get($style, 'font_family'),
				$_get($style, 'font_slant'),
				$_get($style, 'font_weight')
			);
			$cr->setFontSize($element->layout['font_size']);

			$font_extents = $cr->fontExtents();
			$text_extents = $cr->textExtents($element->content);

			$element->layout['bearing'] = $tot_advance;
			$tot_advance += $text_extents['x_advance'];

			$element->layout['y_offset'] = (
				$base_extents['ascent'] *
				$_get($style, 'y_offset')
			);
			$ascent = $font_extents['ascent'] - $element->layout['y_offset'];
			$descent = $font_extents['descent'] + $element->layout['y_offset'];

			if ($ascent > $max_ascent) $max_ascent = $ascent;
			if ($descent > $max_descent) $max_descent = $descent;
		}

		$cr->restore();

		return array(
			'width' => $tot_advance,
			'height' => $max_ascent + $max_descent,
			'ascent' => $max_ascent,
			'descent' => $max_descent,
			'elements' => $elements,
		);
	}

	/**
	 * Draws a layout already created with layout().
	 *
	 * The $cr may have been moved to the correct position with translate() or 
	 * moveTo().
	 */
	function draw($cr, $layout) {
		$cr->save();
		list($x, $y) = $cr->getCurrentPoint();
		$cr->translate($x, $y + $layout['ascent']);
		foreach ($layout['elements'] as $element) {
			$cr->moveTo($element->layout['bearing'], $element->layout['y_offset']);
			\drawing\applyPattern($cr, $element->layout);
			$cr->showText($element->content);
		}
		$cr->restore();
	}

	/**
	 * Moves to a point, lays out text and draws it in one step.
	 *
	 * $loc is a 2-array with the coordinates to draw at. The rest of the 
	 * arguments are the same as layout().
	 */
	function quickDraw($cr, $text, $loc, $pattern) {
		$layout = layout(
			$cr,
			$text,
			array('pattern' => $pattern)
		);

		$cr->moveTo($loc[0], $loc[1]);

		draw($cr, $layout);
	}

	/**
	 * Parses a simple text markup into a layout.
	 */
	function parse($markup) {
		preg_match_all('/_\\{[^}]+\\}|_.|[^_]+/', $markup, $elements);
		$layout = array();

		foreach ($elements[0] as $elem) {
			if (strpos($elem, '_{') === 0) {
				$layout[] = sub(substr($elem, 2, -1));
			} else if ($elem[0] == '_') {
				$layout[] = sub(substr($elem, 1));
			} else {
				$layout[] = $elem;
			}
		}

		return $layout;
	}
}
?>
