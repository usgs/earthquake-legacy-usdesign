<?php
	
	$code = param('code', '404');
	$titles = array(
		'401' => 'Authorization Required',
		'404' => 'Page Not Found'
	);

	$messages = array(
		'401' => '
			<p class="four-zero-one">
				You are not currently authorized to access this web application. 
				In order to gain access to the application, please visit the <a 
				href="signup.php">sign up page</a> where you can supply your 
				email and accept our <a href="signup.php#terms">terms and 
				conditions</a>. Once you have done this you will be allowed to 
				access the web application.

				Your email address is required for signing up. If you entered your
				email address and are still seeing this page, please <a
				href="signup.php">go back</a> and make sure you typed it correctly.
			</p>
		',
		'404' => '
			<p class="four-zero-four">The requested page was not found. Use the 
			links on the left to find what you were looking for. If you still 
			can not find the page you were looking for, please <a 
			href="https://earthquake.usgs.gov/contactus/?to=nluco">contact us for
			assistance</a></p>
		'
	);

	$TITLE = (isset($titles[$code]))?$titles[$code]:'Error';
	$STYLES = '
		.four-zero-one {
			background-color:#FCC;
			border:2px dashed #F33;
			padding: 8px;
		}
		.four-zero-four {
		}
	';
	$CONTACT = 'emartinez';

	include_once $_SERVER['DOCUMENT_ROOT'] . '/template/template.inc.php';

	print (isset($messages[$code]))?$messages[$code]:'Error';
?>
