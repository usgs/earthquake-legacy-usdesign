<?php
	$TITLE = 'Min/Max Design Values for Regions';
	$SCRIPTS = 'js/regions.js';
	$WIDGETS = 'tablesort';
	$STYLESHEETS = 'css/regions.css';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/template/template.inc.php';
?>

<?php
	include_once 'inc/known_regions.php';
	$regionTitle = '50 U.S. States';

	$sortable = new TableSort('regiondata', 5);
	$sortable->setSortType(2, 'designval');
	$sortable->setSortType(3, 'designval');
	$sortable->setSortType(4, 'designval');
	$sortable->setSortType(5, 'designval');
	$sortable->initialize();
?>

<div class="column container contains-ten">
	<h2>Introduction</h2>
	<p style="line-height:1.8">
		The comma-separated values (CSV) files below provide maximum and minimum
		S<sub>S</sub>, S<sub>1</sub>, and PGA values for each of the 50 states
		(plus Washington, D.C.), 3,222 counties, and most* of the 32,236 ZIP codes
		in the U.S. Geographic coordinates of these maxima and minima are also
		provided. <span style="font-style:italic">The values and coordinates are based on the earthquake 
		ground motion maps in, equivalently, the 2009 NEHRP Recommended Seismic 
		Provisions, the 2010 ASCE-7 Standard, and the 2012 International Building
		Code.</span>
	</p>
	<p style="font-size:0.9em">
		* Values are not provided for ZIP codes so small that they do not 
		encompass any of the grid points underlying the data.
	</p>

	<h2>Region Files</h2>
	<ul>
		<li>
			50 U.S. States [
			<a href="#regiondata">View Online</a> |
			<a href="inc/states.csv">Download States CSV</a>
			]
		</li>
		<li>
			3,222 U.S. Counties [ <a href="inc/counties.csv">Download Counties CSV</a> ]
		</li>
		<li>
			32,236 U.S. ZIP Codes [ <a href="inc/zips.csv">Download ZIP Codes CSV</a> ]
		</li>
	</ul>

	<h2><?php print $regionTitle; ?></h2>
	<table class="tabular ten column" id="regiondata" cellpadding="0" 
			cellspacing="0" border="0">
		<thead>
		<tr>
			<th scope="col" class="region">Name</th>
			<th scope="col" class="minss">Min S<sub>S</sub></th>
			<th scope="col" class="mins1">Min S<sub>1</sub></th>
			<th scope="col" class="maxss">Max S<sub>S</sub></th>
			<th scope="col" class="maxs1">Max S<sub>1</sub></th>
		</tr>
		</thead>
		<tbody>
			<?php foreach($STATES as $state) : ?>
				<?php print get_state_table_row($state); ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
