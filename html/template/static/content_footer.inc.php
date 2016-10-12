<div id="content_footer">

<?php
	
	/* Sharing links BEGIN */ 

  if ($SHARE) {
	
	$url = urlencode(sprintf('http://%s%s', $_SERVER['SERVER_NAME'], $_SERVER['REQUEST_URI']));
  $title = urlencode(strip_tags($TITLE));

  // NOTE: the URL parameter must be the last parameter listed for each service so javascript function in master.js can append hash tags to the URL
  $services = array(
    'facebook'     => array('Facebook'      => sprintf('http://www.facebook.com/sharer.php?t=%s&amp;u=%s', $title, $url)),
    'twitter'      => array('Twitter'       => sprintf('http://twitter.com/share?text=%s%s&amp;url=%s', $title, urlencode(' (via @usgs)'), $url)),
    'googlemarks'  => array('Google'        => sprintf('https://www.google.com/bookmarks/mark?op=add&amp;title=%s&amp;annotation=&amp;nui=1&amp;service=bookmarks&amp;bkmk=%s', $title, $url)),
    'email'        => array('Email'         => sprintf('mailto:?subject=%s&amp;body=%s', rawurlencode(urldecode($title)), $url))
  );
  $sharelinks = '';
  foreach ($services as $id => $service) {
    foreach ($service as $name => $href) {
      $sharelinks .= sprintf('    <li class="%s"><a class="offsite" rel="nofollow" href="%s" title="Share on %s">%s</a></li>', $id, $href, $name, $name);
    }
  }

?>

<div id="share">
  <p>Share this page:</p>
  <ul>
<?php print $sharelinks; ?>
  </ul>
</div>

<?php 

	} 
	
	/* Sharing links END */ 

?>
</div>
