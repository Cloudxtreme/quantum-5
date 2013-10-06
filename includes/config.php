<?php

/**
 * Config settings
 *
 * @package Quantum
 * @package Config
 */
Config::setArray(array(
	'database.connect.user'     => 'databaseUser',
	'database.connect.password' => 'databasePassword',
	'database.connect.host'     => 'databaseHost',
	'database.connect.charset'  => 'utf8',
	'database.connect.database' => 'database',
	'page.title'                => 'Page Title',
	'enabled.languages'         => array('en', 'de', 'es'),
	'default.language'          => 'en',
	'multilanguage'             => true,

	'admin.pager.curremt.page'  => 1,
	'admin.pager.rows.per.page' => 100,

	'memcache.host'             => 'localhost',
	'memcache.port'             => '11211',
));