<?php
/**
 * [JF] - 2011-02-24
 *
 * Include guard for this file.
 * This file is autoprepended by php config, but may be re-included when
 * multiple virtual hosts are running on the same system.
 * Anything inside this file should go inside this if statement.
 */
if (! defined('__FUNCTIONS_INC_PHP__')) {
	define('__FUNCTIONS_INC_PHP__', 1);


	//in case url was rewritten, breadcrumb and others rely on php_self
	if(isset($_SERVER['PATH_TRANSLATED'])) {
			$_SERVER['PHP_SELF'] = str_replace("/var/www/html", "", $_SERVER['PATH_TRANSLATED']);
	}
	$_SERVER['PHP_SELF'] = str_replace("//", "/", $_SERVER['PHP_SELF']);

	// [JF] - found in ehpmaster working copy
	// doesn't seem like a bad idea, so propagating from devel
	umask(0002);


	/**
	 * Create the url string necessary to call the pack.ajax.php page with the 
	 * files specified in $script
	 * @param $script Comma separated list of Javascript packages to include OR
	 *	  $script can be a PHP array of the packages to include
	 */
	function fetchScripts($script) {
		$scripts = array();
	
		if( is_array( $script ) ) {
			$files = $script;
		} else {
			$files = explode( ',', $script );
		}
	
		$time = 0;
	
		for($x=0; $x<count($files); $x++) {
			$script = $files[$x];
	
			$rootdir = '/var/www/html';
			if (isset($_SERVER['DOCUMENT_ROOT']) && $_SERVER['DOCUMENT_ROOT'] != '') {
				$rootdir = $_SERVER['DOCUMENT_ROOT'];
			}
	
			$classdir = '/template/js/classes';
			$script_fs = packageToUrl($script, $rootdir);
	
			// We have to treat wildcards special
			if( substr($script_fs, -1) == '*' ) {
				fetch_dir( $script, $files );
			} else {
				$file = $rootdir . $script_fs;
	
				if (file_exists($file) && is_file($file)) {
					$timestamp = filemtime($file);
					if( $timestamp > $time ) { $time = $timestamp; }
					array_push($scripts, str_replace($rootdir, '', $file));
				}
			}
	
		}
	
		$scripts = array_unique($scripts);
		$imploded = implode('&amp;files[]=', $scripts);
		if($imploded != '') {
			$url =  '/template/js/classes/pack.ajax.php?mode=pack&amp;files[]=' . $imploded;
			$url = $url . '&amp;t=' . ($time);
			return $url;
		} else {
			return '';
		}
	}
	
	
	function packageToUrl($package, $root) {
	
		$package = str_replace('//', '/', $package);
		$package = str_replace('/./', '/', $package);
	
		// Don't parse URLs
		if (substr($package, 0, 4) == 'http') { return $package; }
		// Don't parse file system paths
		if (strpos($package, '/') !== false) { return $package; }
	
		$url = '/template/js/classes/';
		$path = str_replace(".", "/", $package);
	
		while (strpos($path, '/') !== false &&
				! file_exists(dirname($root . $url . $path))) {
			$path = dirname($path) . '.' . basename($path);
		}
	
		return $url . $path . '.js';
	}

	function fetch_dir( $script, &$files ) {
		$script_fs = str_replace('.', '/', $script);
		$dir_name = dirname($script_fs);
		if( $dir = opendir($dir_name) ) {
			$package_name = substr($script, 0, -1); 
			while( false !== ($file = readdir($dir))) {
				$file = trim($file);
		
				if(is_file( $dir_name . '/' .$file ) &&  substr( $file, -3 ) == '.js' ) {
					
					//$package_dir = $rootdir . $classdir . '/' . $file . '.js';
					$class_name = substr( $package_name . $file, 0, -3 );
					$class_name = str_replace('/', '.', $class_name);	
			//		print "pushing" . $class_name . '<br />';
					//print "fetching pushing script " . $class_name . '<br />';
					array_push($files, $class_name);
				}	
				else if( is_dir( $dir_name . '/' . $file) && $file != '.' && $file != '..' && $file != '' && substr($file, 0, 1) != '.' ) {
					$class_name = $dir_name . '.' . $file .'.*';
					fetch_dir(  $class_name, $files);
					//print "fetching fetching dir " . $class_name . '<br />';
				}
			}
		}
	}


   /**
    * Returns the url to include the google maps script in a webpage.
    * This can be used like: <script type="text/javascript" src="<?php print getGoogleMapScript(); ?>"></script>
    * It would be nice if the functions were made available in the template header at the top of each page
    * such that one could add the script like: $SCRIPTS = getGoogleMapScript();
	*
	* CHANGE LOG
	* 01/31/08 -- EMM: This function now returns the local path to our google
	* maps script. The retured script dynamically checks the user's requested
	* host domain and writes the proper script tag to include google maps.
	* Developers need not use the "GOOGLE_MAPS" keyword nor even call this
	* method any longer to safely include google maps. Rather they can simply
	* add the "/template/js/gmaps.min.js" to their $SCRIPTS variable.
	* 
    */

	function getGoogleKey() {
		include_once $_SERVER['DOCUMENT_ROOT'] . '/template/widgets/gmaps/GMap2.class.php';
		return GMap2::getGoogleKey();
	}

	function getGoogleMapScript() {
		return "/template/js/gmaps.min.js";
	}

	function set_expiration_header() {
		return; //handled by apache now
	}

	/** Creates a popup link to the page with the title.  $page can be any valid href value. **/
	function poplink($page, $title, $w="500", $h="500") {
		$linktag = sprintf("<a onclick=\"var new_window = window.open('%s','win1','width=%s,height=%s,scrollbars,resizable'); new_window.focus(); return false;\" ", $page, $w, $h);
		$linktag .= "onmouseout=\"window.status=''; return true;\" ";
		$linktag .= sprintf("href=\"%s\" target=\"_blank\">%s</a>", $page, $title);
		return $linktag;
	}

	/**
	 * 02/12/2008 -- EMM: Added support for new servers 1-4, master, backup
	 */
	function server_uri() {
		$server = `hostname`;
		$r = "http://earthquake.usgs.gov";

		if (strpos($server, 'ehpdv') !== false) {
			$r = "http://ehpd-earthquake.cr.usgs.gov";
		} else if (strpos($server, 'ehpst') !== false) {
			$r = 'http://ehps-earthquake.cr.usgs.gov';
		} else if (strpos($server, 'master') !== false) {
			$r = "http://ehpm-earthquake.wr.usgs.gov";
		} else if (strpos($server, 'backup') !== false) {
			$r = "http://ehpb-earthquake.cr.usgs.gov";
		} else if (strpos($server, 'ehp1') !== false) {
			$r = "http://ehp1-earthquake.cr.usgs.gov";
		} else if (strpos($server, 'ehp2') !== false) {
			$r = "http://ehp2-earthquake.wr.usgs.gov";
		} else if (strpos($server, 'ehp3') !== false) {
			$r = "http://ehp3-earthquake.wr.usgs.gov";
		} else if (strpos($server, 'ehp4') !== false) {
			$r = "http://ehp4-earthquake.cr.usgs.gov";
		}

		return($r);
	}

	function param($name, $default = "") {
		$r = '';

		if(isset($_GET["$name"])) { $r = $_GET["$name"]; }
		if(isset($_POST["$name"])) { $r = $_POST["$name"]; }

		if ($r == '') { $r = $default; }
		return($r);
	}

	function safe_param($name, $allowed = "/.*/", $default = "") {
		$value = param($name);
    if ($value != '' && preg_match($allowed, $value)) { // test for empty string for $value so that '0' will pass thru 
			return $value;
		} else {
			return $default;
		}
	}

	function mysql_param($name, $type = "string", $default = "") {
		$r = '';

		/**
		 * 02/12/2008 -- EMM:
		 * The 'param' function takes a default parameter as wel, why not use that?
		 *
		 * $value = param($name, $default);
		 *
		 * Then we can eliminate the 'if' after this assignment. Just a suggestion.
		 */
		$value = param($name);
		if ($value == "") {
			$value = $default;
		}

		if ($type == "int") {
			$r = intval($value);
		} else if ($type == "float") {
			$r = floatval($value);
		} else if ($type == "html") {
			$r = "'" . mysql_real_escape_string(strip_tags($value, "<p><a><strong><em><ul><li><ol><br>")) . "'";
		} else {
			$r = "'" . mysql_real_escape_string(strip_tags($value)) . "'";
		}

		return ($r);
	}	

	function sqlString ($value, $type = "text", $definedValue = "", $notDefinedValue = "") {
		if (get_magic_quotes_gpc()) stripslashes($value);
		if ($type != 'html' && $type != 'xml') $value = strip_tags($value);
		$value = mysql_real_escape_string($value);

		switch ($type) {
			case "text":
				$value = ($value != "") ? "'" . $value . "'" : "NULL";
				break;
			case "long":
			case "int":
				$value = ($value != "") ? intval($value) : "NULL";
				break;
			case "double":
				$value = ($value != "") ? "'" . doubleval($value) . "'" : "NULL";
				break;
			case "date":
				$value = ($value != "") ? "'" . $value . "'" : "NULL";
				break;
			case "float":
				$value = ($value != "") ? floatval($value) : "NULL";
				break;
			case "defined":
				$value = ($value != "") ? $definedValue : $notDefinedValue;
				break;
			case "xml":
				$value = ($value != "") ? "'" . $value . "'" : "NULL";
				break;
			case "html":
				//1. strip disallowed HTML tags
				$value = ($value != "") ? "'" . strip_tags($value, "<a><strong><em><ul><ol><li>") . "'" : "NULL";
	
				//2. remove all attributes except href
				$value = eregi_replace ("<a[^>]+href *= *([^ ]+)[^>]*>", "<a href=\\1>", $value);
				$value = eregi_replace ("<(strong|em|ul|ol|li)[^>]*>", "<\\1>", $value);
				break;
		}
		return $value;
	}

	function fileUpdateTime($file) {
		$mod_time = filemtime($file);
		$mod_str = "";
	
		if($mod_time) {
			$mod_str = gmdate("D M d H:i:s \U\T\C", $mod_time);
		}
	
		return($mod_str);
	}
	
	function simpleFileSize($file) {
		$size_types = array(
			"B", "kB", "Mb", "Gb"
		);
	
		$size_index = 0;
	
		$file_size = filesize($file);
		
		while($file_size > 1024) {
			$file_size = (int) ($file_size / 1024);
			$size_index++;
		}
		
		return($file_size . ' ' . $size_types[$size_index]);
	}

/*** SIDE NAV WRAPPER FUNCTIONS ***/
	function side_nav_header() {
		$r = "<ul>";
			
		return($r);
	}
	
	function side_nav_footer() {
		$r = "</ul>\n";
			
		return($r);
	}




/*** SIDE NAV ITEM FUNCTIONS ***/
	function side_nav_item($href, $text) {
		return(side_nav_item_list($href, $text, array()));
	}

	function navItem($href, $text) {
		return side_nav_item($href, $text);
	}	
	
	function side_nav_item_list($href, $text, $array_highlights) {
		$highlight = false;
	
		//highlight if at or beneath link
		array_push($array_highlights, $href);
		$highlight = _side_nav_highlight($array_highlights);
		
		return(side_nav_item_bool($href, $text, $highlight));
	}
	
	function side_nav_item_bool($href, $text, $highlight) {
		return(
			_side_nav_item(
				$href, 
				$text, 
				"", 
				$highlight
			)
		);
	}




/*** SIDE NAV GROUP FUNCTIONS ***/
	function side_nav_toggle_group($href, $text, $children) {
		return(side_nav_toggle_group_list($href, $text, $children, array()));
	}
	
	function side_nav_toggle_group_list($href, $text, $children, $array_highlights) {
		$extra_classes = '';
		if(strlen($children) > 0 && strstr($children, "<strong>")) {
			$highlight = true;
			$extra_classes = 'childselected';
		} else {
			array_push($array_highlights, $href);
			$highlight = _side_nav_highlight($array_highlights);
		}
		return(side_nav_toggle_group_bool($href, $text, $children, $highlight, $extra_classes));
	}
	
	function side_nav_toggle_group_bool($href, $text, $children, $highlight, $extra_classes='') {
		$r = "";

		if($highlight) {
			//if there is a child highlight, suppress the group highlight...
			if(strlen($children) > 0 && strstr($children, "<strong>")) {
				$extra_classes = "childselected";
			}

			$r = side_nav_group_bool($href, $text, $children, $highlight, $extra_classes);
		} else {
			$r = side_nav_item_bool($href, $text, false);
		}
		
		return($r);
	}
	
	
	function side_nav_easytoggle_group($href, $text, $children, $togglegroup) {
		return(side_nav_easytoggle_group_list($href, $text, $children, array(), $togglegroup));
	}
	
	function side_nav_easytoggle_group_list($href, $text, $children, $array_highlights, $togglegroup) {
		//check children for highlights first
		if(strlen($children) > 0 && strstr($children, "<strong>")) {
			$highlight = true;
		} else {
			//highlight if at or beneath link
			array_push($array_highlights, $href);
			$highlight = _side_nav_highlight($array_highlights);
		}
		
		return(side_nav_easytoggle_group_bool($href, $text, $children, $highlight, $togglegroup));
	}
	
	function side_nav_easytoggle_group_bool($href, $text, $children, $highlight, $togglegroup) {
		$r = "";
		
		$hrefid = str_replace("#", "", $href);
		
		$r = _side_nav_item(
			$href,
			$text,
			"<div id=\"$hrefid\"><ul>" . $children . "</ul></div>",
			$highlight,
						$togglegroup
		);
		
		return($r);
	}
	
	
	
	function side_nav_group($href, $text, $children) {
		return(side_nav_group_list($href, $text, $children, array()));
	}

	function navGroup($text, $children) {
		return side_nav_group('', $text, $children);
	}
	
	function side_nav_group_list($href, $text, $children, $array_highlights) {
		$highlight = false;

		$extra_classes = '';
		//check children for highlights first
		if(strlen($children) > 0 && strstr($children, "<strong>")) {
			$highlight = true;
			$extra_classes = "childselected";
		} else {
			//highlight if at or beneath link
			array_push($array_highlights, $href);
			$highlight = _side_nav_highlight($array_highlights);
		}
		
		return(side_nav_group_bool($href, $text, $children, $highlight, $extra_classes));
	}
	
	function side_nav_group_bool($href, $text, $children, $highlight, $extra_classes='') {
		$classes = $extra_classes;
		if ($classes != '') {
			$classes .= ' ';
		}
		$classes .= 'group';
		return(
			_side_nav_item(
				$href, 
				$text, 
				"<ul>" . $children . "</ul>", 
				$highlight,
				$classes
			)
		);
	}




/*** SIDE NAV BASE FUNCTIONS ***/
	function _side_nav_highlight($array_highlights) {
		$highlight = false;
		
		foreach($array_highlights as $test) {
			$test = str_replace("/", "\/", $test);
			$test = str_replace(".", "\.", $test);
			$test = str_replace("?", "\?", $test);
			
			if(preg_match("/^" . $test . "/", $_SERVER['PHP_SELF'], $matches)
				|| preg_match("/^" . $test . "/", $_SERVER['REQUEST_URI'], $matches)
			) {
				$highlight = true;
				break;
			}
		}
		
		return($highlight);
	}
	
	function _side_nav_item($href, $text, $other_text, $highlight, $item_class='') {
		$r = "";
		$prefix = "";
		$suffix = "";
		$target = "";
		$class = "";
		
		if($item_class != "") {
			$class=" class=\"$item_class\"";
		}

		if($highlight) {
			$prefix = "<strong>";
			$suffix = "</strong>";
		}
		
		//open offsite links in new window
		if(strstr($href, "http://")) {
			$target = " target=\"_blank\"";
		}

		//don't display index.php for onsite links
		if (!strstr($href, "http://") && strstr($href, "index.php")) {
			$href = str_replace("index.php", "", $href);
		}

		$r = "<li>${prefix}";

		if ($href != "") {
			$r .= "<a href=\"${href}\"${target}${class}>";
		} else {
			$r .= "<span${class}>";
		}
		
		$r .= $text;
		
		if ($href != "") {
			$r .= "</a>";
		} else {
			$r .= "</span>";
		}

		$r .= "${suffix}${other_text}</li>";
		//$r = "<li>${prefix}<a href=\"${href}\"${target}${class}>${text}</a>${suffix}$other_text</li>";
	
		return($r);
	}


	function feed_updated($feed) {
		$updated = "<p><em>Updated: ". fileUpdateTime($feed);
		if (simpleFileSize($feed) != '0 B') {
			$updated .= " (" . simpleFileSize($feed) . ")";
		}
		$updated .= "</em></p>\n";
		return $updated;
	}


	
	
	/**
	 * Calls findFileInPaths using getRequestPaths() for the $paths parameter.
	 * The result of getRequestPaths is cached to make this more efficient.
	 * @param $file name of file to search.
	 */
	function findNearestFile($file) {
		$paths = getRequestPaths();
	
		/**
		//old path building routine, replaced by getRequestPaths().
		// this contains a "bug", note the double dirname calls...
		$paths = array();
		$path = realpath($_SERVER['SCRIPT_FILENAME']);
		
		while ($path != '' && $path != '/' && $path != '.') {
			$path = dirname($path);
			array_push($paths, dirname($path));
		}
		**/
		
		return findFileInPaths($file, $paths, true);
	}
	
	
	
	/**
	 * Builds a list of directories in the web space that contain the requested file.
	 * 
	 * This method memoizes its results, so only the first call is expensive.
	 *
	 * Crawls up SCRIPT_FILENAME until a directory named 'htdocs' is found.
	 * If that htdocs is not the document root, the request path is tested until
	 *   an existing directory in document root is found, and crawling continues up
	 *   to actual document root.
	 */
	function getRequestPaths() {
		static $paths = null;
		
		if ($paths == null) {
			$paths = array();
			$path = realpath($_SERVER['SCRIPT_FILENAME']);
		
			while ($path != '' && $path != '/' && $path != '.') {
				$path = dirname($path);
				array_push($paths, $path);
				
				if (basename($path) == 'htdocs' || $path == '/var/www/data') {
					//stop looking, unless this was an aliased htdocs
					if (realpath($path) == realpath($_SERVER['DOCUMENT_ROOT'])) {
						break;
					} else {
						//find mount point in real htdocs and continue
						$fakepath = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'];
						while (!file_exists($fakepath)) {
							$fakepath = dirname($fakepath);
						}
						$path = $fakepath . '/fakefile';
					}
				}
			}
		}
	
		return $paths;
	}
	
	
	/**
	 * Scans the list of paths for a file named file.
	 * @param $file name of file to find.
	 * @param $paths array of directories to search.
	 * @param $first whether to 
	 *		 true - return first matching file
	 *		 false - return all matching files
	 */
	function findFileInPaths($file, $paths, $first=true) {
		$files = array();
		
		if (file_exists($file)) { 
			$filepath = realpath($file);
			array_push($files, $filepath); 
			if ($first) { 
				return $filepath; 
			}
		}
		
		if (!is_array($paths)) { $paths = array(); }
		foreach ($paths as $path) {
			$filepath = realpath($path . '/' . $file);
			if (file_exists ($filepath)) {
				if ($first) { return $filepath; }
				if (!in_array($filepath, $files)) {
					array_push($files, $filepath);
				}
			}
		}
	
		if ($first) {
			return '';
		}	
		return $files;
	}
	
	/**
	 * Similar to findFileInPaths($file, $paths, true), but if
	 * $after is provided, the path after $after is returned.
	 *
	 * For most uses, it is probably easier to iterate over the array 
	 * returned by findFileInPaths($file, $paths, false).
	 */
	function findFirstFileInPaths($file, $paths, $after=null) {
		if ($after == null) {
			$file = findFileInPaths($file, $paths, true);
		} else {
			$after = realpath($after);
			$files = findFileInPaths($file, $paths, false);
			$current = "";
			while (sizeof($files) > 0) {
				$current = array_shift($files);
				if ($current == $after) {
					break;
				}
			}
			
			if (sizeof($files) > 0) {
				$file = $files[0];
			} else {
				$file = '';
			}
		}
		return $file;
	}
	
	
	




// FLASH FUNCTIONS (formerly functions.animation.inc.php)

	function getStrsBetween($s,$s1,$s2=false,$offset=0) {
		/*====================================================================
		Function to scan a string for items encapsulated within a pair of tags
	
		getStrsBetween(string, tag1, <tag2>, <offset>
	
		If no second tag is specified, then match between identical tags
	
		Returns an array indexed with the encapsulated text, which is in turn
		a sub-array, containing the position of each item.
	
		Notes:
		strpos($needle,$haystack,$offset)
		substr($string,$start,$length)
	
		====================================================================*/
	
		if( $s2 === false ) { $s2 = $s1; }
		$result = array();
		$L1 = strlen($s1);
		$L2 = strlen($s2);
	
		if( $L1==0 || $L2==0 ) {
			return false;
		}
	
		do {
			$pos1 = strpos($s,$s1,$offset);
	
			if( $pos1 !== false ) {
				$pos1 += $L1;
	
				$pos2 = strpos($s,$s2,$pos1);
	
				if( $pos2 !== false ) {
					$key_len = $pos2 - $pos1;
	
					$this_key = substr($s,$pos1,$key_len);
	
					if( !array_key_exists($this_key,$result) ) {
						$result[$this_key] = array();
					}
	
					$result[$this_key][] = $pos1;
	
					$offset = $pos2 + $L2;
				} else {
					$pos1 = false;
				}
			}
		} while($pos1 !== false );
	
		return $result;
	}
}
