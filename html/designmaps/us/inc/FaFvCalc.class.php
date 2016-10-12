<?php

// Dependencies
include_once 'constants.inc.php';
$STYLESHEETS = 'css/fafv.css';

/**
 * This class is used to compute Fa/Fv values based on Ss/S1 and a site
 * classification. In addition, one can use this class to display an XHTML
 * table.
 *
 * @author Eric Martinez
 * 
 * -=* CHANGE LOG *=-
 * 01/08/09 -- EMM: Original implementation.
 * 
 */
class FaFvCalc {

	//--------------------------------------------------------------------------
	// Static Class Constants
	//--------------------------------------------------------------------------

	// Array defining the Ss bin levels used for computing Fa.
	                  // Bin Level: 0      1    2     3     4
	private static $ss_bin_levels = array(0.25, 0.50, 0.75, 1.00, 1.25);
	// The Fa values corresponding to the bin level and site class.
	private static $fa_vals = array(
			// Bin Level:  0      1      2      3      4
			0 => array('0.8', '0.8', '0.8', '0.8', '0.8'),
			1 => array('1.0', '1.0', '1.0', '1.0', '1.0'),
			2 => array('1.2', '1.2', '1.1', '1.0', '1.0'),
			3 => array('1.6', '1.4', '1.2', '1.1', '1.0'),
			4 => array('2.5', '1.7', '1.2', '0.9', '0.9')
		);
	
	// Array defining the S1 bin levels used for computing Fv.
	                  // Bin Level:  0    1    2    3    4
	private static $s1_bin_levels = array( 0.1, 0.2, 0.3, 0.4, 0.5);
	// The Fv values corresponding to the bin level and site class.
	private static $fv_vals = array(
			// Bin Level:  0      1      2      3      4
			0 => array('0.8', '0.8', '0.8', '0.8', '0.8'),
			1 => array('1.0', '1.0', '1.0', '1.0', '1.0'),
			2 => array('1.7', '1.6', '1.5', '1.4', '1.3'),
			3 => array('2.4', '2.0', '1.8', '1.6', '1.5'),
			4 => array('3.5', '3.2', '2.8', '2.4', '2.4')
		);

	private static $pga_bin_levels = array(0.1, 0.2, 0.3, 0.4, 0.5);
	private static $fpga_vals = array(
			// Bin Level:  0      1      2      3      4 
			0 => array('0.8', '0.8', '0.8', '0.8', '0.8'),
			1 => array('1.0', '1.0', '1.0', '1.0', '1.0'),
			2 => array('1.2', '1.2', '1.1', '1.0', '1.0'),
			3 => array('1.6', '1.4', '1.2', '1.1', '1.0'),
			4 => array('2.5', '1.7', '1.2', '0.9', '0.9')
		);

	private static $site_classes = array('A', 'B', 'C', 'D', 'E');

	//--------------------------------------------------------------------------
	// Constructors / Initialization Methods
	//--------------------------------------------------------------------------

	/**
	 * Placeholder initialization stub. Maybe we will do something later.
	 */
	public function __construct() {
		// Do nothing for now.
	}

	//--------------------------------------------------------------------------
	// Public Functions
	//--------------------------------------------------------------------------

	/**
	 * Computes the Fa value for the given $_ss and $_siteclass.
	 *
	 * @param _ss        (Double) The short period (0.2 sec) ground motion value
	 *                             for which we want to compute the Fa.
	 * @param _siteclass (String) A single uppercase character A-E identifying
	 *                             site classification.
	 *
	 * @return The Fa value for the given $_ss and $_siteclass.
	 */
	public function getFa($_ss, $_siteclass) {
		$fa = '';
		$bounds = $this->getFaBounds($_ss);
		$lowerbound = $bounds[0]; $upperbound = $bounds[1];

		if ($lowerbound == $upperbound) {
			$y_vals = FaFvCalc::$fa_vals[$_siteclass];
			$fa = $y_vals[$lowerbound];
		} else {
			$x0 = FaFvCalc::$ss_bin_levels[$lowerbound];
			$x1 = FaFvCalc::$ss_bin_levels[$upperbound];
			$y_vals = FaFvCalc::$fa_vals[$_siteclass];
			$y0 = $y_vals[$lowerbound];
			$y1 = $y_vals[$upperbound];
			$fa = $y0 + (( ($_ss - $x0) / ($x1 - $x0) ) * ($y1 - $y0));
		}

		return $fa;
	}

	/**
	 * Computes the Fv value for the given $_s1 and $_siteclass.
	 *
	 * @param _s1        (Double) The 1.0 second ground motion value for which
	 *                             we want to compute the Fv.
	 * @param _siteclass (String) A single uppercase character A-E identifying
	 *                             site classification.
	 *
	 * @return The Fv value for the given $_s1 and $_siteclass.
	 */
	public function getFv($_s1, $_siteclass) {
		$fv = '';
		$bounds = $this->getFvBounds($_s1);
		$lowerbound = $bounds[0]; $upperbound = $bounds[1];

		if ($lowerbound == $upperbound) {
			$y_vals = FaFvCalc::$fv_vals[$_siteclass];
			$fv = $y_vals[$lowerbound];
		} else {
			$x0 = FaFvCalc::$s1_bin_levels[$lowerbound];
			$x1 = FaFvCalc::$s1_bin_levels[$upperbound];
			$y_vals = FaFvCalc::$fv_vals[$_siteclass];
			$y0 = $y_vals[$lowerbound];
			$y1 = $y_vals[$upperbound];
			$fv = $y0 + (( ($_s1 - $x0) / ($x1 - $x0) ) * ($y1 - $y0));
		}
		
		return $fv;
	}

	/**
	 * Computes the Fpga value for the given $_pga and $_siteclass.
	 *
	 * @param _pga        (Double) The peak ground motion value for which
	 *                             we want to compute the Fpga.
	 * @param _siteclass (String) A single uppercase character A-E identifying
	 *                             site classification.
	 *
	 * @return The Fpga value for the given $_pga and $_siteclass.
	 */
	public function getFpga($_pga, $_siteclass) {
		$fpga = '';
		$bounds = $this->getFpgaBounds($_pga);
		$lowerbound = $bounds[0]; $upperbound = $bounds[1];

		if ($lowerbound == $upperbound) {
			$y_vals = FaFvCalc::$fpga_vals[$_siteclass];
			$fpga = $y_vals[$lowerbound];
		} else {
			$x0 = FaFvCalc::$pga_bin_levels[$lowerbound];
			$x1 = FaFvCalc::$pga_bin_levels[$upperbound];
			$y_vals = FaFvCalc::$fpga_vals[$_siteclass];
			$y0 = $y_vals[$lowerbound];
			$y1 = $y_vals[$upperbound];
			$fpga = $y0 + (( ($_pga - $x0) / ($x1 - $x0) ) * ($y1 - $y0));
		}
		
		return $fpga;
	}



	/**
	 * Generates the XHTML markup suitable for displaying the Fa site class table
	 * with the appropriate bin level identified (via an HTML class attribute)
	 * to indicate the Fa value to use.
	 *
	 * @param _ss        (Double) The short period (0.2 second) ground motion
	 *                             value for which to generate an appropriately 
	 *                             identifying site class table for Fa values.
	 * @param _siteclass (String) A single uppercase character A-E identifying
	 *                             site classification.
	 * @param _title     (String) The title of this table. Different building
	 *                             codes use slightly different notation.
	 *
	 * @return XHTML markup for the table.
	 */
	public function getFaTableMarkup($_ss, $_siteclass, $_display_opts) {
		return $this->getTableMarkup($_ss, $_siteclass, $_display_opts, array(
			'bin_levels' => FaFvCalc::$ss_bin_levels,
			'cell_values' => FaFvCalc::$fa_vals,
			'bounds' => $this->getFaBounds($_ss),
			'value' => $this->getFa($_ss, $_siteclass),
			'type' => 'a',
			'label' => 'S<sub>S</sub>'
		));
	}

	/**
	 * Generates the XHTML markup suitable for displaying the Fv site class table
	 * with the appropriate bin level identified (via an HTML class attribute)
	 * to indicate the Fv value to use.
	 *
	 * @param _ss        (Double) The 1.0 second) ground motion value for which 
	 *                             to generate an appropriately identifying site 
	 *                             class table for Fv values.
	 * @param _siteclass (String) A single uppercase character A-E identifying
	 *                             site classification.
	 * @param _title     (String) The title of this table. Different building
	 *                             codes use slightly different notation.
	 *
	 * @return XHTML markup for the table.
	 */
	public function getFvTableMarkup($_s1, $_siteclass, $_display_opts) {
		return $this->getTableMarkup($_s1, $_siteclass, $_display_opts, array(
			'bin_levels' => FaFvCalc::$s1_bin_levels,
			'cell_values' => FaFvCalc::$fv_vals,
			'bounds' => $this->getFvBounds($_s1),
			'value' => $this->getFv($_s1, $_siteclass),
			'type' => 'v',
			'label' => 'S<sub>1</sub>'
		));
	}

	/**
	 * Generates the XHTML markup suitable for displaying the Fpga site class table
	 * with the appropriate bin level identified (via an HTML class attribute)
	 * to indicate the Fpga value to use.
	 *
	 * @param _pga        (Double) The peak ground motion value for which 
	 *                             to generate an appropriately identifying site
	 *                             class table for Fpga values.
	 * @param _siteclass (String) A single uppercase character A-E identifying
	 *                             site classification.
	 * @param _title     (String) The title of this table. Different building
	 *                             codes use slightly different notation.
	 *
	 * @return XHTML markup for the table.
	 */
	public function getFpgaTableMarkup($_pga, $_siteclass, $_display_opts) {
		return $this->getTableMarkup($_pga, $_siteclass, $_display_opts, array(
			'bin_levels' => FaFvCalc::$pga_bin_levels,
			'cell_values' => FaFvCalc::$fpga_vals,
			'bounds' => $this->getFpgaBounds($_pga),
			'value' => $this->getFpga($_pga, $_siteclass),
			'type' => 'PGA',
			'label' => 'PGA'
		));
	}

	//--------------------------------------------------------------------------
	// Private Functions
	//--------------------------------------------------------------------------

	/**
	 * Gets the Fa bin levels that surround the given $_ss. These lower and upper
	 * bounds provide a basis for the linear interpolation used to compute the Fa
	 * value.
	 *
	 * @param _ss (Double) The short period (0.2 sec) ground motion value for 
	 *                      which we want to compute the Fa.
	 *
	 * @return An array with the first element (index 0) containing the lower
	 *         bound and the second element (index 1) containing the upper bound.
	 */
	private function getFaBounds($_ss) {
		if ($_ss <= 0.25) { $lowerbound = 0; $upperbound = 0; }
		else if (0.25 < $_ss && $_ss < 0.50) { $lowerbound = 0; $upperbound = 1; }
		else if ($_ss == 0.50) { $lowerbound = 1; $upperbound = 1; }
		else if (0.50 < $_ss && $_ss < 0.75) { $lowerbound = 1; $upperbound = 2; }
		else if ($_ss == 0.75) { $lowerbound = 2; $upperbound = 2; }
		else if (0.75 < $_ss && $_ss < 1.00) { $lowerbound = 2; $upperbound = 3; }
		else if ($_ss == 1.00) { $lowerbound = 3; $upperbound = 3; }
		else if (1.00 < $_ss && $_ss < 1.25) { $lowerbound = 3; $upperbound = 4; }
		else if (1.25 <= $_ss) { $lowerbound = 4; $upperbound = 4; }
		
		return array($lowerbound, $upperbound);
	}

	/**
	 * Gets the Fv bin levels that surround the $_s1. These lower and upper
	 * bounds provide a basis for the linear interpolation used to compute the Fv
	 * value.
	 *
	 * @param _s1 (Double) The 1.0 sec ground motion value for which we want to
	 *                      compute the Fv.
	 *
	 * @return An array with the first element (index 0) containing the lower
	 *         bound and the second element (index 1) containing the upper bound.
	 */
	private function getFvBounds($_s1) {
		if ($_s1 <= 0.10) { $lowerbound = 0; $upperbound = 0; }
		else if (0.10 < $_s1 && $_s1 < 0.20) { $lowerbound = 0; $upperbound = 1; }
		else if ($_s1 == 0.20) { $lowerbound = 1; $upperbound = 1; }
		else if (0.20 < $_s1 && $_s1 < 0.30) { $lowerbound = 1; $upperbound = 2; }
		else if ($_s1 == 0.30) { $lowerbound = 2; $upperbound = 2; }
		else if (0.30 < $_s1 && $_s1 < 0.40) { $lowerbound = 2; $upperbound = 3; }
		else if ($_s1 == 0.40) { $lowerbound = 3; $upperbound = 3; }
		else if (0.40 < $_s1 && $_s1 < 0.50) { $lowerbound = 3; $upperbound = 4; }
		else if (0.50 <= $_s1) { $lowerbound = 4; $upperbound = 4; }

		return array($lowerbound, $upperbound);
	}

	/**
	 * Gets the Fpga bin levels that surround the $_pga. These lower and upper
	 * bounds provide a basis for the linear interpolation used to compute the
	 * Fpga value.
	 *
	 * @param _pga (Double) The peak ground motion value for which we want to
	 *                      compute the Fpga.
	 *
	 * @return An array with the first element (index 0) containing the lower
	 *         bound and the second element (index 1) containing the upper bound.
	 */
	public function getFpgaBounds($_pga) {
		if ($_pga <= 0.10) { $lowerbound = 0; $upperbound = 0; }
		else if (0.10 < $_pga && $_pga < 0.20) {$lowerbound = 0;$upperbound = 1;}
		else if ($_pga == 0.20) { $lowerbound = 1; $upperbound = 1; }
		else if (0.20 < $_pga && $_pga < 0.30) {$lowerbound = 1;$upperbound = 2;}
		else if ($_pga == 0.30) { $lowerbound = 2; $upperbound = 2; }
		else if (0.30 < $_pga && $_pga < 0.40) {$lowerbound = 2;$upperbound = 3;}
		else if ($_pga == 0.40) { $lowerbound = 3; $upperbound = 3; }
		else if (0.40 < $_pga && $_pga < 0.50) {$lowerbound = 3;$upperbound = 4;}
		else if (0.50 <= $_pga) { $lowerbound = 4; $upperbound = 4; }

		return array($lowerbound, $upperbound);
	}


	/**
	 * This function is called internally to generate either an Fa or Fv site
	 * classification table. The first three parameters are externally set by a
	 * call to one two wrapper functions (getFaTableMarkup or getFvTableMarkup).
	 * The remaining arguments are determined based on which function the
	 * external caller called.
	 *
	 *
	 * @param _sa        (Double) The 1.0 second) ground motion value for which 
	 *                            to generate an appropriately identifying site 
	 *                            class table for Fv values.
	 * @param _siteclass (String) A single uppercase character A-E identifying
	 *                            site classification.
	 * @param _title     (String) The title of this table. Different building
	 *                            codes use slightly different notation.
	 *
	 * -----------------------------------------------------------------------
	 *
	 * @param _caption     (String) A building-code-appropriate label for this
	 *                              table.
	 * @param _bin_levels   (Array) The bin level (column headings) to use for
	 *                              this table.
	 * @param _cell_values  (Array) The values corresponding to each site
	 *                              classification and corresponding bin level.
	 * @param _bounds       (Array) The lower (index 0) and upper (index 1)
	 *                              bounding cell values to flag in the table.
	 * @param _value       (Double) The Fa or Fv value (as appropriate).
	 * @param _type        (String) Either an 'a' or 'v' character.
	 * @param _label       (String) The label for this data type (XHTML)
	 *
	 * -----------------------------------------------------------------------
	 *
	 * @return XHTML markup for the table.
	 */
	private function getTableMarkup($_sa, $_siteclass, $display_opts, $_data_params) {
		global $SHORT_SITE_CLASSES, $PRECISION;
		$lowerbound = $_data_params['bounds'][0]; $upperbound = $_data_params['bounds'][1];
		$seealso = (isset($display_opts['see_also']) ? $display_opts['see_also'] : 'See Section 11.4.7 of ASCE 7');

		$markup = 
			'<span class="imagecaption">'.$display_opts['caption'].'</span>'.
			'<table cellpadding="0" cellspacing="0" border="0" class="fafv">'.
				'<thead>'.
					'<tr class="headerrow">'.
						'<th rowspan="2">Site Class</th>'.
						'<th colspan="5">'.$display_opts['title'].'</th>'.
					'</tr>'.
					'<tr>';

		// Column Headings
		foreach($_data_params['bin_levels'] as $value) {
			if ($value == min($_data_params['bin_levels'])) {
				$operator = '&le;';
			} else if ($value == max($_data_params['bin_levels'])) {
				$operator = '&ge;';
			} else {
				$operator = '=';
			}

			$markup .= 
						'<th scope="col">'.
							$_data_params['label'].' '.$operator.' '.sprintf("%0.2f", $value).
						'</th>';
		}

		$markup .=
					'</tr>'.	
				'</thead>'.
				'<tbody>';

		// Row Data
		foreach($_data_params['cell_values'] as $sc=>$vals) {
			$markup .= '<tr><th scope="row">'.self::$site_classes[$sc].'</th>';
			for ($i = 0; $i < count($vals); ++$i) {
				$c_string = ' class="f'.$_data_params['type'];
				if ($_siteclass == $sc) {
					if ($i == $lowerbound) { $c_string .= ' lowerbound'; }
					if ($i == $upperbound) { $c_string .= ' upperbound'; }
				}
				$c_string .= '"';
				$markup .= sprintf('<td%s>%s</td>', $c_string, $vals[$i]);
			}
			$markup .= '</tr>';
		}

		// This last row is static and just references another section of the
		// building code as a reference.
		$markup .= 
					'<tr><th>F</th><td colspan="5">'.$seealso.'</td></tr>'.
					'<tr><td colspan="6">'.
						'Note: Use straight&ndash;line interpolation for '.
						'intermediate values of '.$_data_params['label'].
					'</td></tr>'.
				'</tbody>'.
			'</table>'.
			'<span class="imagecaption" style="font-weight:bold;">'.
			'For Site Class = '.$SHORT_SITE_CLASSES[$_siteclass].' and '.
				$_data_params['label'].' '.
				'= '. number_format($_sa, $PRECISION) . ' g, ' .  'F<sub>'.$_data_params['type'].'</sub> = '.
				number_format($_data_params['value'], $PRECISION) . '</span>';

		return $markup;
	}

}
