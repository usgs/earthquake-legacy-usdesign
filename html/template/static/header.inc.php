<?php //BEGIN USGS Header Template ?>
<div id="usgsbanner"><div>
	<a href="http://www.usgs.gov/"><img src="/template/images/usgs.jpg" alt="USGS - science for a changing world" title="U.S. Geological Survey Home Page" /></a>
	<ul>
		<li><a href="http://www.usgs.gov/">USGS Home</a></li>
		<li><a href="https://answers.usgs.gov/">Contact USGS</a></li>
	</ul>
</div></div>

<div id="usgstitle"><div>
	<p><a href="/">Earthquake Hazards Program</a></p>
	<ul id="utilities">
	<li class="invisiblelink"><a href="#startcontent" class="invisiblelink">Skip to main content</a></li>
<?php

	print side_nav_item("/index.php", "Home");
	print side_nav_item("/aboutus/", "About Us");
	print side_nav_item("/contactus/", "Contact Us");

?>
	<li id="search">

<?php
/**
	Change the following at your own peril.
	Yes, our search results page is at /search/, but that is only for javascript!
*/
?>
		<form method="get" action="http://www.google.com/search" id="header-search-form" target="_blank" class="search-form">
			<span class="search-wrap">
				<input type="text" name="q" size="10" maxlength="50" title="Search Text" class="search-text" />
				<input type="image" src="/template/images/sbox_button.gif" alt="Search" class="search-button" />
			</span>
			<input type="hidden" name="q" value="site:earthquake.usgs.gov" class="search-siteonly" />
		</form>
	</li>
	</ul>
</div>
</div>

<?php //END USGS Header Template ?>
