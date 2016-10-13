<?php
	require_once 'inc/appconfig.inc.php';
  $TITLE = 'Batch Requests';
  $HEAD = '
    <link rel="stylesheet" href="css/dialog.20141009.css"/>
    <style>
    .six pre {
      background-color:#EEEEEE;
      border:1px dashed #DDDDDD;
      font-family:Courier, Monaco, monospace;
      padding:1em;
      text-align:left;
    }
    #frmbatch ol {list-style:none;margin:0;padding:0;}
    #frmbatch ol li { margin:0 0 10px; }
    #frmbatch label {display:block;color:#333;font-weight:bold;}
    #frmbatch em {display:block;font-weight:normal;font-style:normal;}
    #frmbatch #reset {
      margin:3px 0 0;padding:0;float:right;border:0;
      background-color:#FFFFFF;color:#000099;cursor:pointer;
      font-family:Arial, sans-serif;cursor:pointer;
      text-decoration:underline;
    }
    small.ajax {
      color: #888;
      display: block;
      font-size: .8em;
      margin-top: .2em;
    }
    p.ajax {
      word-wrap: break-word;
    }
  </style>
  ';
  $FOOT = '
    <script src="js/dialog.20141009.js"></script>
  ';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/template/template.inc.php';
?>

<div class="six column">
	<h2>About Batch Mode</h2>
	<p>
		For convenience users can compute design values for
		multiple locations at once. This option requires that the user create a
		<a href="#format">batch file</a> and submit it via the Batch File Processor to the right of this text.
        After the data are processed, the user will be presented with the option of
		viewing raw <a href="#xml">Extensible Markup Language (XML) file</a> output or downloading a
		<a href="#csv">comma-separated values (CSV) file</a>.
	</p>
	<p>
		Please note that the batch format output files will not contain graphs or other images
		that are available from the single-point counterpart of this tool.
	</p>

	<h2 id="format">Batch File Format</h2>
	<p>
		The user must create the batch file for uploading ahead of time. This file is
		a simple CSV file with the following format:
	</p>
	<pre>
latitude,longitude,siteclass,riskcategory
latitude,longitude,siteclass,riskcategory
latitude,longitude,siteclass,riskcategory
    .        .         .          .
    .        .         .          .
    .        .         .          .
latitude,longitude,siteclass,riskcategory
	</pre>
	<p>
		Here latitude and longitude values should be in decimal degree
		format. Negative values denote western longitudes. The
		&ldquo;siteclass&rdquo; value is an integer between 0 and 4 where:
	</p>
	<table cellpadding="0" cellspacing="0" border="0"
		class="tabular six column">
		<thead>
			<tr>
				<th scope="col">Integer Value</th>
				<th scope="col">Meaning</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>0</td><td>Site Class A &ndash; &ldquo;Hard Rock&rdquo;</td>
			</tr>
			<tr>
				<td>1</td><td>Site Class B &ndash; &ldquo;Rock&rdquo;</td>
			</tr>
			<tr>
				<td>2</td>
				<td>
					Site Class C &ndash; &ldquo;Very Dense Soil and Soft
					Rock&rdquo;
				</td>
			</tr>
			<tr>
				<td>3</td><td>Site Class D &ndash; &ldquo;Stiff Soil&rdquo;</td>
			</tr>
			<tr>
				<td>4</td>
				<td>Site Class E &ndash; &ldquo;Soft Clay Soil&rdquo;</td>
			</tr>
		</tbody>
	</table>
	<p>
    	The &ldquo;riskcategory&rdquo; (or &ldquo;occupancy category&rdquo; in some
        code versions) value is an <i>optional</i> integer between -1 and 3 where:
    </p>
	<table cellpadding="0" cellspacing="0" border="0"
		class="tabular six column">
		<thead>
			<tr>
				<th scope="col">Integer Value</th>
				<th scope="col">Meaning</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>-1</td><td>N/A</td>
			</tr>
			<tr>
				<td>0</td><td>I - Low Hazard</td>
			</tr>
			<tr>
				<td>1</td><td>II - Other</td>
			</tr>
			<tr>
				<td>2</td><td>III - Substantial Hazard</td>
			</tr>
			<tr>
				<td>3</td><td>IV - Essential</td>
			</tr>
		</tbody>
	</table>

	<p>
		Please note that batch processing too many locations at
		once may cause the application to malfunction. There is a hard limit
		of no more than 50 locations per batch file request. However, depending
		upon the complexity of the location coordinates (degree to which data interpolation
		is required) the USGS suggests that <strong>no more than about 10</strong>
		locations be submitted in a single request.
	</p>

	<p>
		Users may wish to process more than 10 locations at once and the USGS is
		working to improve system performance to allow this. However, at this
		time please be aware of this limitation. If the applicaton displays
		an error message or a results page with no data, then the quantity of inputted
        locations was too great. This is most likely to occur during peak usage
		times (business hours) since the request will be competing with many
		others at once. Repeatedly submitting the same request will unfortunately
        only further compound the problem.
	</p>

	<h2 id="xml">XML File Format</h2>
	<p>
		Users can download batch processing results in XML file format.  XML is an
        open format that is readily parsed by most programming languages
        for subsequent processing. Most browsers can render this format for
        quick viewing, and third-party tools are also available for viewing XML
        files. The format of the XML output file is as follows:
	</p>
	<pre><?php print htmlspecialchars('
<batch_output status="1">
    <location index="1">
        <status>0</status>
        <latitude>VALUE</latitude>
        <longitude>VALUE</longitude>
        <siteclass>VALUE</siteclass>
        <riskcategory>VALUE</riskcategory>
        <designcategory>VALUE</designcategory>
        <ss>VALUE</ss>
        <s1>VALUE</s1>
        <pga>VALUE</pga>
        <srs>VALUE</srs>
        <sr1>VALUE</sr1>
        <pgam>VALUE</pgam>
        <sds>VALUE</sds>
        <sd1>VALUE</sd1>
        <ssuh>VALUE</ssuh>
        <s1uh>VALUE</s1uh>
        <pgauh>VALUE</pgauh>
        <ssd>VALUE</ssd>
        <s1d>VALUE</s1d>
        <pgadet>VALUE</pgadet>
        <crs>VALUE</crs>
        <cr1>VALUE</cr1>
        <fa>VALUE</fa>
        <fv>VALUE</fv>
        <fpga>VALUE</fpga>
        <tl>VALUE</tl>
        <spectrum index="0">
            <name>Design Spectrum Sa Vs T</name>
            <min x="VALUE" y="VALUE" />
            <max x="VALUE" y="VALUE" />
            <point index="0" x="VALUE" y="VALUE" />
            <point index="1" x="VALUE" y="VALUE" />
            <point index="2" x="VALUE" y="VALUE" />
            <point index="3" x="VALUE" y="VALUE" />
            <point index="4" x="VALUE" y="VALUE" />
            <point index="5" x="VALUE" y="VALUE" />
            <point index="6" x="VALUE" y="VALUE" />
            <point index="7" x="VALUE" y="VALUE" />
            <point index="8" x="VALUE" y="VALUE" />
            <point index="9" x="VALUE" y="VALUE" />
            <point index="10" x="VALUE" y="VALUE" />
            <point index="11" x="VALUE" y="VALUE" />
            <point index="12" x="VALUE" y="VALUE" />
            <point index="13" x="VALUE" y="VALUE" />
            <point index="14" x="VALUE" y="VALUE" />
            <point index="15" x="VALUE" y="VALUE" />
            <point index="16" x="VALUE" y="VALUE" />
            <point index="17" x="VALUE" y="VALUE" />
            <point index="18" x="VALUE" y="VALUE" />
            <point index="19" x="VALUE" y="VALUE" />
            <point index="20" x="VALUE" y="VALUE" />
        </spectrum>
        <spectrum index="1">
            <name>MCE_R Spectrum Sa Vs T</name>
            <min x="VALUE" y="VALUE" />
            <max x="VALUE" y="VALUE" />
            <point index="0" x="VALUE" y="VALUE" />
            <point index="1" x="VALUE" y="VALUE" />
            <point index="2" x="VALUE" y="VALUE" />
            <point index="3" x="VALUE" y="VALUE" />
            <point index="4" x="VALUE" y="VALUE" />
            <point index="5" x="VALUE" y="VALUE" />
            <point index="6" x="VALUE" y="VALUE" />
            <point index="7" x="VALUE" y="VALUE" />
            <point index="8" x="VALUE" y="VALUE" />
            <point index="9" x="VALUE" y="VALUE" />
            <point index="10" x="VALUE" y="VALUE" />
            <point index="11" x="VALUE" y="VALUE" />
            <point index="12" x="VALUE" y="VALUE" />
            <point index="13" x="VALUE" y="VALUE" />
            <point index="14" x="VALUE" y="VALUE" />
            <point index="15" x="VALUE" y="VALUE" />
            <point index="16" x="VALUE" y="VALUE" />
            <point index="17" x="VALUE" y="VALUE" />
            <point index="18" x="VALUE" y="VALUE" />
            <point index="19" x="VALUE" y="VALUE" />
            <point index="20" x="VALUE" y="VALUE" />
        </spectrum>
    </location>
</batch_output>');?></pre>
	<p>
		In the XML output file the &ldquo;status&rdquo; attribute of the &ldquo;
        batch_output&rdquo; tag displays the number of successfully processed
        locations from the submitted batch file. Next, there will be a &ldquo;
        location&rdquo; tag for each of the locations in the batch file. This
        will be followed by the &ldquo;siteclass&rdquo; and &ldquo;riskcategory
        &rdquo; inputs provided by the user for the location. Given the
        user-provided &ldquo;siteclass&rdquo; and &ldquo;riskcategory&rdquo;
        inputs, the application provides seismic design parameter values from
        the user-selected design code reference document for each user-specified
        location. Finally, a series of points used to define the design spectra
        is presented. Note the spectra may have anywhere from 17 - 20 points,
        depending upon the discretization required to fully plot the spectra data.
	</p>

	<h2 id="csv">CSV File Format</h2>
	<p>
		The comma-separated values (CSV) output file format is an alternative
		way of viewing the results of a batch request. This file format can be
		natively opened by most spreadsheet applications (like Microsoft
		Excel&trade;). This file format has a single comma-separated header line
		indicating the column values contained in the file. Then there will be a
		subsequent line corresponding to each of the locations in the batch
		input file.
	</p>
	<p>
		This file format does not contain spectra data; however, the spectra can
        be computed manually using the data contained in the file. Given the
        limitations this may present for users, the USGS will work toward including
        these values in the CSV output in a future release.
	</p>
</div>

<div class="four column">
	<h2>Batch File Processor</h2>
	<form method="post" action="inc/batchminer.inc.php" id="frmbatch"
			enctype="multipart/form-data">
		<ol>
			<li id="filewrapper">
				<label for="batchfile">
					Select a file to upload from your computer
				</label>
				<input type="file" size="30" name="batchfile"
					id="batchfile" value="" required />
			</li>
			<li>
				<label for="designCode">and a design code edition:</label>
				<select id="designCode" name="designCode" required>
					<?php include_once 'inc/design-code-selector.inc.php' ?>
				</select>
			</li>
			<li hidden>
				<label>
					Hazard Level:
				</label>
				<select name="designCodeVariant" id="designCodeVariant">
				</select>
			</li>
			<li>
				<input type="hidden" name="vanilla" id="vanilla" value="true"/>
				<input type="submit" name="submit" id="submit"
					value="Submit Batch File Request" />
				<input type="reset" name="reset" id="reset"
					value="Clear Form and Results"/>
			</li>
		</ol>
	</form>
	<iframe id="iframe_dropzone" name="iframe_dropzone" src="#"
		style="display:none;visibility:hidden;width:1px;height:1px;">
	</iframe>
</div>

<script type="text/javascript">/* <![CDATA[ */
var ERROR_TIMEOUT = null;
$(document).ready(function(_event) {
	window.IO = new Dialog();
	$('#frmbatch').submit(function(_evt) {
		if ($('#designCode').val() == "") {
			window.IO.alert(
				'Please select a design code before submitting your data.',
				{'title': 'Error'}
			);
			return false;
		}
		window.IO.showLoading(
			'Please be patient while we process your request.');
		// Wait 1 minute then assume an error occurred.
		ERROR_TIMEOUT = setTimeout('showErrors();', 60000);
	});
	$('#reset').click(function(_evt) {
		$('.ajax').remove();
	});
	$('#frmbatch').attr('target', 'iframe_dropzone');
	$('#vanilla').val('false');
	$('#designCode').change(function() {
		var $varsel = $('#designCodeVariant');

		if ($('#designCode').val() == 'asce_41-2013') {
			$varsel.empty();

			var variants = ['BSE-2N', 'BSE-1N', 'BSE-2E', 'BSE-1E', 'Custom'];

			$(variants).each(function(i, val) {
				$varsel.append('<option value="' + i + '">' + val + '</option>');
			});

			$varsel.closest('li').show();
		} else {
			$varsel.closest('li').hide();
		}
	}).change();
});

showBatchResults = function(_fileid, _filename, _gentime, _dcode_name, _variant) {
	clearTimeout(ERROR_TIMEOUT);
	$(
		'<h2 class="ajax">Results For Batch Request</h2>' +
		'<small class="ajax">Generated from ' + _filename + ' at ' +
		(new Date(_gentime)).toLocaleTimeString() + '</small>' +
		'<small class="ajax">Design code: ' + _dcode_name +
		(_variant ? (', ' + _variant) : '') + '</small>' +
		'<ul class="ajax">' +
			'<li><a href="output/' + _fileid + '.xml" target="_blank"' +
				'>Download XML Format</a></li>' +
			'<li><a href="inc/xml2csv.php?fileid=' + _fileid +
				'" target="_blank">Download CSV Format</a></li>' +
		'</ul>'
	).hide().insertAfter('.four form').fadeIn();
	window.IO.closeDialog();
};

showBatchError = function(_error) {
	clearTimeout(ERROR_TIMEOUT);
	$(
		'<h2 class="ajax">Results For Batch Request</h2>' +
		'<p class="ajax"><strong>Failed:</strong> ' + _error + '</p>'
	).hide().insertAfter('.four form').fadeIn();
	window.IO.closeDialog();
};

showErrors = function() {
	window.IO.closeDialog();
	window.IO.alert('We are sorry. It seems your batch file is either too ' +
		'large or our server is too busy to process your request. Please ' +
		'try again later or try fewer locations in your batch file.',
		{title:'Batch Error'}
	);
};


/* ]]> */</script>
