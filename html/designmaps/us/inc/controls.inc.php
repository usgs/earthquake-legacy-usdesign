<?php include 'constants.inc.php' ?>
<form method="post" action="noscript.inc.php" onsubmit="return false;" 
		id="frm_app">
	<ol>
		<li>
			<label for="designCode">
				Building Code Reference Document
				<em>
					Ask your local building official if you need help selecting
					this.
				</em>
			</label>
			<select name="designCode" id="designCode" required>
				<?php include 'design-code-selector.inc.php' ?>
			</select>
		</li>
		<li>
			<label for="reportTitle">
				Report Title
				<span style="font-size:.9em;color:#666666">(Optional)</span>
				<em>This will appear at the top of the generated report.</em>
			</label>
			<input type="text" name="reportTitle" id="reportTitle"
				value="<?php print param('title', ''); ?>"/>
		</li>
		<li hidden>
			<label>
				Earthquake Hazard Level
				<em>
					The particular analysis procedure to use.
				</em>
			</label>
			<select name="designCodeVariant" id="designCodeVariant">
			</select>
		</li>
		<li hidden>
			<label>
				Probability of Exceedance (in 50 years)
				<em>
					The percent probability of ground motion exceedance in 50 years.
				</em>
			</label>
			<input name="hazardProbability" id="hazardProbability" value="10">
		</li>
		<li>
			<label for="siteclass">
				Site Soil Classification
				<em>
					This is <strong>not</strong> automatically
					selected based on site location.
				</em>
			</label>
			<select name="siteclass" id="siteclass" required>
				<option value="-1">Please Select&hellip;</option>
				<option value="0">
					Site Class A &ndash; &ldquo;Hard Rock&rdquo;
				</option>
				<option value="1">
					Site Class B &ndash; &ldquo;Rock&rdquo;
				</option>
				<option value="2">
					Site Class C &ndash; &ldquo;Very Dense Soil and Soft Rock&rdquo;
				</option>
				<option value="3">
					Site Class D &ndash; &ldquo;Stiff Soil&rdquo; 
					(Default)
				</option>
				<option value="4">
					Site Class E &ndash; &ldquo;Soft Clay Soil&rdquo;
				</option>
			</select>
		</li>
		<li hidden>
			<label for="riskcategory">
				Occupancy/Risk Category
				<em>
					Used to compute the seismic design category.
				</em>
			</label>
			<select name="riskcategory" id="riskcategory" required>
				<option value="-1">Please Select&hellip;</option>
				<option value="0">
					I/II/III
				</option>
				<option value="3">
					IV
				</option>
			</select>
		</li>
		<li>
			<label for="latitude">
				Site Latitude
				<em>Decimal degrees for the site location.</em>
			</label>
			<input type="text" id="latitude" name="latitude" 
					value="<?php print param('latitude', ''); ?>"/>
		</li>
		<li>
			<label for="longitude" id="forLongitude">
				Site Longitude
				<em>Decimal degrees for the site location.</em>
			</label>
			<input type="text" id="longitude" name="longitude"
					value="<?php print param('longitude', ''); ?>"/>
		</li>
		<li>
			<input type="submit" id="submitRequest" class="button" value="Compute Values" />
		</li>
	</ol>
</form>
