<?php
	function get_state_location_tag($_title, $_lat, $_lng) {
		return 
		'<a class="location" href="application.php?latitude=' . $_lat . 
			'&amp;longitude=' . $_lng . '&amp;title=' . urlencode($_title) .
			'">(' . pretty_lat($_lat) . ', ' . pretty_lng($_lng) . ')' . '</a>';
	}

	function pretty_lat($lat) {
		$extra = '&deg;N';
		if ($lat < 0) { $lat *= -1; $extra = '&deg;S'; }
		return number_format($lat, 3) . $extra;
	}

	function pretty_lng($lng) {
		$extra = '&deg;E';
		if ($lng < 0) { $lng *= -1; $extra = '&deg;W'; }
		return number_format($lng, 3) . $extra;
	}

	$statesfile = 'inc/states.csv';
	$STATES = file($statesfile);
	array_shift($STATES); // Get rid of header row

	function get_state_table_row($_state) {
		$name = get_state_name($_state);
		$abbr = get_state_abbr($name);

		return '
		<tr id="state_' . $abbr . '">
				<td class="region">' . $name . '</td>
				<td class="minss"> ' . 
					get_state_minss($_state) . 'g' .
					get_state_location_tag( 
							$name . ' Minimum Ss',
							get_state_minss_lat($_state),
							get_state_minss_lng($_state)
						) . '
				</td>
				<td class="mins1"> ' .
					get_state_mins1($_state) . 'g' .
					get_state_location_tag( 
							$name . ' Minimum S1',
							get_state_mins1_lat($_state),
							get_state_mins1_lng($_state)
						) . '
				</td>
				<td class="maxss"> ' .
					get_state_maxss($_state) . 'g' .
					get_state_location_tag( 
							$name . ' Maximum Ss',
							get_state_maxss_lat($_state),
							get_state_maxss_lng($_state)
						) . '
				</td>
				<td class="maxs1"> ' .
					get_state_maxs1($_state) . 'g' .
					get_state_location_tag( 
							$name . ' Maximum S1',
							get_state_maxs1_lat($_state),
							get_state_maxs1_lng($_state)
						) . '
				</td>
			</tr>
		';
	}

	function get_state_name($_state) { return get_state_arg($_state, 0); }

	function get_state_minss($_state) { 
		return number_format(get_state_arg($_state, 1), 3); }
	function get_state_minss_lat($_state) { return get_state_arg($_state, 2); }
	function get_state_minss_lng($_state) { return get_state_arg($_state, 3); }

	function get_state_maxss($_state) { 
		return number_format(get_state_arg($_state, 4), 3); }
	function get_state_maxss_lat($_state) { return get_state_arg($_state, 5); }
	function get_state_maxss_lng($_state) { return get_state_arg($_state, 6); }

	function get_state_mins1($_state) { 
		return number_format(get_state_arg($_state, 7), 3); }
	function get_state_mins1_lat($_state) { return get_state_arg($_state, 8); }
	function get_state_mins1_lng($_state) { return get_state_arg($_state, 9); }

	function get_state_maxs1($_state) { 
		return number_format(get_state_arg($_state, 10), 3); }
	function get_state_maxs1_lat($_state) { return get_state_arg($_state, 11); }
	function get_state_maxs1_lng($_state) { return get_state_arg($_state, 12); }

	function get_state_minpga($_state) { 
		return number_format(get_state_arg($_state, 13), 3); }
	function get_state_minpga_lat($_state) { return get_state_arg($_state, 14); }
	function get_state_minpga_lng($_state) { return get_state_arg($_state, 15); }

	function get_state_maxpga($_state) { 
		return number_format(get_state_arg($_state, 16), 3); }
	function get_state_maxpga_lat($_state) { return get_state_arg($_state, 17); } 
	function get_state_maxpga_lng($_state) { return get_state_arg($_state, 18); }

	function get_state_arg($_state, $_idx) {
		$state_arr = explode(',' , str_replace('"', '', $_state));
		return $state_arr[$_idx];
	}

	function get_state_abbr($_name) {
		$KNOWN_STATES = array(
			'Alabama' => 'AL',
			'Alaska' => 'AK',
			'Arizona' => 'AZ',
			'Arkansas' => 'AR',
			'California' => 'CA',
			'Colorado' => 'CO',
			'Connecticut' => 'CT',
			'Delaware' => 'DE',
			'District of Columbia' => 'DC',
			'Florida' => 'FL',
			'Georgia' => 'GA',
			'Hawaii' => 'HI',
			'Idaho' => 'ID',
			'Illinois' => 'IL',
			'Indiana' => 'IN',
			'Iowa' => 'IA',
			'Kansas' => 'KS',
			'Kentucky' => 'KY',
			'Louisiana' => 'LA',
			'Maine' => 'ME',
			'Maryland' => 'MD',
			'Massachusetts' => 'MA',
			'Michigan' => 'MI',
			'Minnesota' => 'MN',
			'Mississippi' => 'MS',
			'Missouri' => 'MO',
			'Montana' => 'MT',
			'Nebraska' => 'NE',
			'Nevada' => 'NV',
			'New Hampshire' => 'NH',
			'New Jersey' => 'NJ',
			'New Mexico' => 'NM',
			'New York' => 'NY',
			'North Carolina' => 'NC',
			'North Dakota' => 'ND',
			'Ohio' => 'OH',
			'Oklahoma' => 'OK',
			'Oregon' => 'OR',
			'Pennsylvania' => 'PA',
			'Rhode Island' => 'RI',
			'South Carolina' => 'SC',
			'South Dakota' => 'SD',
			'Tennessee' => 'TN',
			'Texas' => 'TX',
			'Utah' => 'UT',
			'Vermont' => 'VT',
			'Virginia' => 'VA',
			'Washington' => 'WA',
			'West Virginia' => 'WV',
			'Wisconsin' => 'WI',
			'Wyoming' => 'WY'
		);
		return $KNOWN_STATES[$_name];
	}
?>
