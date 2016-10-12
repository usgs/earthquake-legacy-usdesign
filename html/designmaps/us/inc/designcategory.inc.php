<?php
	include 'constants.inc.php';
	include_once 'reports.inc.php';

	global $DC_THRESHOLDS, $DC_COLUMNS, $DC_VALUES;

	$DC_THRESHOLDS = array(
		'sds' => array('0.167', '0.33', '0.50'),
		'sd1' => array('0.067', '0.133', '0.20'),
		'sds-nehrp-2003' => array('0.167', '0.33', '0.50'),
		'sd1-nehrp-2003' => array('0.067', '0.133', '0.20'),
		'sd1-aashto' => array('0.15', '0.30', '0.50'),
	);

	$DC_COLUMNS = array(
		'sds' => array(
			'I or II' => array(0, 1),
			'III' => array(2),
			'IV' => array(3),
		),
		'sd1' => array(
			'I or II' => array(0, 1),
			'III' => array(2),
			'IV' => array(3),
		),
		'sds-nehrp-2003' => array(
			'I' => array(0),
			'II' => array(1),
			'III' => array(2),
		),
		'sd1-nehrp-2003' => array(
			'I' => array(0),
			'II' => array(1),
			'III' => array(2),
		),
		'sd1-aashto' => array(
			'I, II, III or IV' => array(0, 1, 2, 3),
		),
	);

	$DC_VALUES = array(
		'sds' => array(
			array('A', 'B', 'C', 'D'),
			array('A', 'B', 'C', 'D'),
			array('A', 'B', 'C', 'D'),
			array('A', 'C', 'D', 'D'),
		),
		'sd1' => array(
			array('A', 'B', 'C', 'D'),
			array('A', 'B', 'C', 'D'),
			array('A', 'B', 'C', 'D'),
			array('A', 'C', 'D', 'D'),
		),
		'sds-nehrp-2003' => array(
			array('A', 'B', 'C', 'D'),
			array('A', 'B', 'C', 'D'),
			array('A', 'C', 'D', 'D'),
		),
		'sd1-nehrp-2003' => array(
			array('A', 'B', 'C', 'D'),
			array('A', 'B', 'C', 'D'),
			array('A', 'C', 'D', 'D'),
		),
		'sd1-aashto' => array(
			array('A', 'B', 'C', 'D'),
		),
	);

	function outputDCTable($risk_category, $source, $source_value, $true_design_category) {
		global $DC_THRESHOLDS, $DC_COLUMNS, $DC_VALUES, $SHORT_RISK_CATEGORIES, 
			$LABELS;

		$thresholds = $DC_THRESHOLDS[$source];
		$columns = $DC_COLUMNS[$source];
		$source_values = $DC_VALUES[$source];
		$values = $source_values[$risk_category == -1 ? 0 : min($risk_category, count($source_values))];
		preg_match('/^(.)([^-]+)/', $source, $matches);
		$pretty_source = strtoupper($matches[1]) . '<sub>' .
		                 strtoupper($matches[2]) . '</sub>';

		$bin = 0;

		foreach ($thresholds as $threshold) {
			if ($source_value < $threshold) break;

			$bin++;
		}

		$designcategory = $values[$bin];

		if ($designcategory == $true_design_category) {
			print '<table class="designcategory">';
		} else {
			print '<table class="designcategory overridden">';
		}

		echo '<thead><tr>';

		if (count($columns) > 1) {
			echo '<th rowspan="2">VALUE OF ', $pretty_source, '</th>';
			echo '<th colspan="', count($columns), '">',
				strtoupper($LABELS['rc']), '</th></tr>';

			echo '<tr>';

			foreach (array_keys($columns) as $column) {
				echo '<th class="riskcategory">', $column, '</th>';
			}
		} else {
			echo '<th>VALUE OF ', $pretty_source, '</th>';
			echo '<th>SDC</th>';
		}
			
		echo '</tr></thead><tbody>';

		for ($i = 0; $i <= count($thresholds); $i++) {
			echo '<tr><th scope="row">';

			if ($i == 0) {
				echo $pretty_source, ' &lt; ', $thresholds[$i], 'g';
			} else if ($i == count($thresholds)) {
				echo $thresholds[$i - 1], 'g &le; ', $pretty_source;
			} else {
				echo $thresholds[$i - 1], 'g &le; ', $pretty_source,
				     ' &lt; ', $thresholds[$i], 'g';
			}

			foreach ($columns as $column => $values) {
				if ($i == $bin && ($risk_category == -1 || array_search($risk_category, $values) !== false)) {
					echo '<td class="selected">';
				} else {
					echo '<td>';
				}

				echo $source_values[$values[0]][$i], '</td>';
			}

			echo '</tr>';
		}

		echo '</table><span class="imagecaption" style="font-weight: bold">For ';

		if ($risk_category != -1) {
			echo $LABELS['rc'], ' = ', $SHORT_RISK_CATEGORIES[$risk_category], 
				' and ';
		}

		?>
			<?php echo $pretty_source, ' = ', dataFormat($source_value) ?> g,
			Seismic Design Category = <?php print $designcategory ?> 
		</span>
		<?php
	}
?>
