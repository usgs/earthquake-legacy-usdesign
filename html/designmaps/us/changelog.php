<?php
	$TITLE = 'Recent Changes to U.S. Seismic Design Maps Web Application';
	$STYLESHEETS = 'css/changelog.css';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/template/template.inc.php';
?>

<h2>2014-06-23</h2>

<ul>
	<li>
		Fixed bug where a return period of &ldquo;10&#37; in 50 years&rdquo; was
		erroneously printed on the summary report.
	</li>
</ul>

<h2>2014-06-14</h2>

<ul>
	<li>Fixed bug relating to variants introduced with ASCE 41-13</li>
	<li>
		Fixed issue where pop-up blocker notification was erroneously
		displayed.
	</li>
</ul>

<h2>2013-07-11</h2>

<ul>
	<li>Added new design code for ASCE 41-13</li>
	<li>Fixed bug that made certain design codes unusable in some web
	browsers</li>
</ul>

<h2>2013-04-02</h2>

<ul>
	<li>Batch mode can process more sites at once</li>
	<li>Floor deterministic PGA values at 0.5g, instead of 0.6g, for ASCE 7-10</li>
	<li>JSON API, allowing single-site values to be accessed programmatically</li>
	<li>New location selection interface</li>
	<li>Textual updates to site class tables</li>
	<li>Updated documentation</li>
</ul>

<h2>2012-05-22</h2>

<ul>
    <li>2009/06 IBC, 2009 AASHTO, 2003 NEHRP, and ASCE 7-05 are now included, with seismic 
	design categories</li>
	<li>Usability improvements to main application</li>
	<li>Formatting of Batch Mode output is now tailored to suit each design code</li>
	<li>Improvements to Documentation and FAQs</li>
</ul>

<h2>2011-08-22</h2>

<ul>
	<li>Seismic design categories can be calculated for ASCE 7-10</li>
	<li>Minor fixes to interface and documentation</li>
</ul>

<h2>2011-08-08</h2>

<ul>
	<li>Batch mode supports 2012 IBC and design categories</li>
	<li>More underlying datasets are available for ASCE 7-10 and NEHRP 2009</li>
	<li>Report can be generated for 2012 IBC</li>
	<li>Seismic design categories can be calculated for 2012 IBC</li>
</ul>

<h2>2011-07-01</h2>

<ul>
	<li>All numbers in reports now have three decimal digits of precision</li>
	<li>Better interfaces for viewing related maps and datasets</li>
	<li>More readable callouts on NEHRP 2009 maps</li>
	<li>Risk coefficients on ASCE 7-10 detailed report</li>
	<li>The design code edition can be selected in the batch interface</li>
	<li>Title on charts in summary report is correct for each design code</li>
	<li>Added note to clarify how ground motion parameters are calculated</li>
</ul>
