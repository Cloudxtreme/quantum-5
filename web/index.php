<?php
// Start-up time.
define('SCRIPT_START', microtime(true));

// Default include folder.
set_include_path(dirname(__DIR__));

// Session start.
session_start();

// Booting.
include('../includes/bootstrap.php');