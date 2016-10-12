<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="ie ie8" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en">         <!--<![endif]-->
<head>
<title><?php print strip_tags($TITLE); ?></title>
<?php include getTemplateFile('static/head.inc.php'); ?>
</head>

<body<?php if ($BODYCLASS != '') { print ' class="' . $BODYCLASS . '"'; } ?>>

<div id="header"><?php include getTemplateFile('static/header.inc.php'); ?></div>

<hr />
<div id="sitenav"><?php include getTemplateFile('static/sitenav.inc.php'); ?></div>
<hr />

<div id="content"<?php if ($CONTENTCLASS != '') { print ' class="' . $CONTENTCLASS . '"'; } ?>>
  <a id="startcontent"></a>
  <div id="main">
    <h1><?php print $TITLE; ?></h1>
<?php include $SCRIPT_FILENAME; ?>

  </div>
  <div id="navigation">
  <hr />
<?php include getTemplateFile('navigation/navigation.master.inc.php'); ?>
  </div>
</div>

<hr />

<?php include getTemplateFile('static/content_footer.inc.php'); ?>

<hr />

<?php include getTemplateFile('static/footer.inc.php'); ?>
<?php include getTemplateFile('static/foot.inc.php'); ?>

</body>
</html>
