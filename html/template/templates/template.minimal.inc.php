<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="ie ie8" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en">         <!--<![endif]-->
<head>
<title><?php print strip_tags($TITLE); ?></title>
<?php include TEMPLATE_DIR . '/static/head.inc.php'; ?>
</head>

<body>
<a href="http://www.usgs.gov/"><img src="/template/images/usgs.gif" alt="USGS - science for a changing world" title="U.S. Geological Survey Home Page" /></a>
<h1><?php print $TITLE; ?></h1>

<?php include $SCRIPT_FILENAME; ?>

<?php include TEMPLATE_DIR . '/static/foot.inc.php'; ?>
</body>
</html>
