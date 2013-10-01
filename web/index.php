<?php
// Futasido inditasa.
define('SCRIPT_START', microtime(true));

// Alapertelemezett include mappas.
set_include_path(dirname(__DIR__));

// Session inditasa.
session_start();

// Bootolas.
include('../includes/bootstrap.php');