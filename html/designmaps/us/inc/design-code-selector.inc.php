<option value="">Please Select&hellip;</option>
<?php
	include_once 'constants.inc.php';
	$bases = array();
	krsort($DCODES);
	foreach ($DCODES as $edition_id => $edition) {
		if (!isset($bases[$edition['basis']])) {
			$bases[$edition['basis']] = array();
		}

		$shortname = $edition['shortname'];
		if ($edition['phase'] == PHASE_DEVEL) $shortname .= ' (dev only)';

		$bases[$edition['basis']][$shortname] = $edition_id;
	}
	
	foreach ($bases as $basis => $editions) {
		print '<optgroup value="undefined" label="Derived from ' . $basis . '">';

		krsort($editions); // desc year order
		foreach ($editions as $shortname => $edition_id) {
			print '<option value="' . $edition_id . '">' .
				$shortname . '</option>';
		}

		print '</optgroup>';
	}
?>
