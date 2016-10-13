<?php
	//--------------------------------------------------------------------------
	// Author     :  Eric Martinez
	// Description:  This file defines some useful functions for use in the 
	//               design tool. This may be included from any file.
	// File       :  appfunctions.inc.php
	//
	// -=* CHANGE LOG *=-
	// 08/26/08 -- EMM: Original implementation.
	//
	//--------------------------------------------------------------------------

	/**
	 * Formats an input latitude value to be a nice human-readable format. Uses 
	 * 5 decimal places of precision (this sets accuracy to about 1 meter for
	 * locations with latitudes in the conterminous U.S.).
	 *
	 * @param lat The latitude value to format.
	 */
	function prettyLat($lat) {
		$extra = '&deg;N';
		if ($lat < 0) { $lat = abs($lat); $extra = '&deg;S'; }
		return round($lat, 5) . $extra;
	}

	/**
	 * Formats an input longitude value to be a nice human-readable format. Uses
	 * 5 decimal places of precision (this sets accuracy to about 1 meter for
	 * locations with latitudes in the conterminous U.S.).
	 *
	 * @param lng The longitude value to format.
	 */
	function prettyLng($lng) {
		$extra = '&deg;E';
		if ($lng < 0) { $lng = abs($lng); $extra = '&deg;W'; }
		return round($lng, 5) . $extra;
	}

	/**
	 * Based on the input latitude and longitude, this function returns the
	 * corresponding region abbreviation. Current known abbreviations are:
	 *
	 * 'US'
	 * 'AK'
	 * 'HI'
	 * 'PR'
	 * 'GU'
	 * 'AS'
	 * 'WORLD'
	 *
	 * Others may be added. We use predetermined bounding boxes to decide on the
	 * region abbreviation to use.
	 */
	function getRegionName($_latitude, $_longitude) {
		if ($_latitude >= 24.6 && $_latitude <= 50.0 &&
				$_longitude >= -125.0 && $_longitude <= -65.0) {
			// Conterminous 48
			return 'US';
		} else if ($_latitude >= 18.0 && $_latitude <= 23.0 &&
				$_longitude >= -161.0 && $_longitude <= -154.0) {
			// Hawaii
			return 'HI';
		} else if ($_latitude >= 49.75 && $_latitude <= 66.2 && 
				$_longitude >= -196.6 && $_longitude <= -127.45) {
			// Alaska
			return 'AK';
		} else if ($_latitude >= 9.0 && $_latitude <= 23.0 &&
				$_longitude >= 139.0 && $_longitude <= 151.0) {
			// Guam
			return 'GU';
		} else if ($_latitude >= -33.0 && $_latitude <= -11.0 &&
				$_longitude >= -195.0 && $_longitude <= -165.0) {
			// American Samoa
			return 'AS';
		} else {
			// Default to World. This may have unknown side-effects.
			return 'WORLD';
		}
	}

	function albers2xy($_latitude, $_longitude, $_params) {
		// Constants
		$e = 0.017453292519943295;
		$e2 = $e * $e;
		$a = 6378137;
		$dpi = $_params['dpi'];
		
		// Images specific parameters
		$scale   = $_params['scale'];
		$x0pixel = $_params['x0pixel'];
		$y0pixel = $_params['y0pixel'];
		$phi0    = $_params['phi0'];
		$phi1    = $_params['phi1'];
		$phi2    = $_params['phi2'];
		$lambda0 = $_params['lambda0'];

		// Convert to radians
		$lonrad = $_longitude * M_PI / 180;
		$latrad = $_latitude * M_PI / 180;
		$phi0rad = $phi0 * M_PI / 180;
		$phi1rad = $phi1 * M_PI / 180;
		$phi2rad = $phi2 * M_PI / 180;
		$lambda0rad = $lambda0 * M_PI / 180;
		
		// Compute projection conversion values
		$m1 = cos($phi1rad)/sqrt((1-$e2*sin($phi1rad)*sin($phi1rad)));
		$m2 = cos($phi2rad)/sqrt((1-$e2*sin($phi2rad)*sin($phi2rad)));

		$q0 = (1-$e2)*(sin($phi0rad)/(1-$e2*sin($phi0rad)*sin($phi0rad)) -
		      (1/(2*$e))*log((1-$e*sin($phi0rad))/(1+$e*sin($phi0rad))));
		$q1 = (1-$e2)*(sin($phi1rad)/(1-$e2*sin($phi1rad)*sin($phi1rad)) -
		      (1/(2*$e))*log((1-$e*sin($phi1rad))/(1+$e*sin($phi1rad))));
		$q2 = (1-$e2)*(sin($phi2rad)/(1-$e2*sin($phi2rad)*sin($phi2rad)) -
		      (1/(2*$e))*log((1-$e*sin($phi2rad))/(1+$e*sin($phi2rad))));

		$n = ($m1*$m1 - $m2*$m2)/($q2-$q1);
		$C = $m1*$m1 + $n*$q1;
		$rho0 = $a*sqrt(($C-$n*$q0))/$n;
		$q = (1-$e2)*(sin($latrad)/(1-$e2*sin($latrad)*sin($latrad)) -
		     (1/(2*$e))*log((1-$e*sin($latrad))/(1+$e*sin($latrad))));
		$rho = $a*sqrt(($C-$n*$q))/$n;
		$theta = $n*($lonrad - $lambda0rad);

		// Do the conversion and adjust for image resolution
		$x      = $rho*sin($theta);
		$y      = $rho0 - $rho*cos($theta);
		$xpixel = intval(round($x/$scale*100*$dpi/2.54 + $x0pixel));
		$ypixel = intval(round($y0pixel - $y/$scale*100*$dpi/2.54));

		return array($xpixel, $ypixel);
	}

	function merc2xy($_latitude, $_longitude, $_region) {
		$e = 0.017453292519943295;
		$a = 6378137;
		//$dpi = 218;
		$dpi = 170;
		
		$lonrad = $_longitude * M_PI / 180;
		$latrad = $_latitude * M_PI / 180;
		
		$scale = 4000000;
		$x0pixel = 245;  // Arbitrary "fudge-factors" to make it work.
		$y0pixel = 195;
		$lambda0 = -71.5;
		$lambda0rad = $lambda0 * M_PI / 180;
		
		$x = $a*($lonrad-$lambda0rad)-612263.87;
		$y = ($a/2) * log( ((1 + sin($latrad)) / (1 - sin($latrad)) * 
		     		pow(((1 - $e * sin($latrad)) / (1 + $e * sin($latrad))), $e)
					)) - 2024227.0;
		
		$xpixel = intval(round($x/$scale*100*$dpi/2.54 + $x0pixel));
		$ypixel = intval(round($y0pixel - $y/$scale*100*$dpi/2.54));

		return array($xpixel, $ypixel);
	}

	function proj2xy($_latitude, $_longitude, $_region) {
		global $ALBERS_PARAMS;
		if ($_region == 'PR') {
			return merc2xy($_latitude, $_longitude, $_region);
		} else {
			return albers2xy($_latitude, $_longitude, $ALBERS_PARAMS[$_region]);
		}
	}

