<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include('../includes/config.php');

// Page Loader
Request::requireUri();

/**
 * Class loader.
 *
 * @return void
 */
function __autoload($class_name) {
	$classes = include('../cache/classes.php');
	try {
		if (is_file('../' . $classes[$class_name])) {
			include('../' . $classes[$class_name]);
		}
		else {
			echo 'Routing error.';
		}
	}
	catch (Exception $e) {
		echo 'Page not found.';
	}
}
