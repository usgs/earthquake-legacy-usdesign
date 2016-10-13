<?php

// this needs to be in head until all content has been updated to not reference these functions in the body
// <script type="text/javascript" src="/template/js/master.js"></script>

/**
 * foot.inc.php
 * content that is included at the bottom of the page
 *
 * @author jmfee
 * @version 1.0 2008/01/24
 */

// Process $SCRIPTS HERE
?>

<?php

if (isset($FOOT)) {
	echo $FOOT;
}

?>
