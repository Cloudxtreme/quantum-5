<?php

return array(
	'login' => array(
		'uri'    => '/login.html',
		'params' => array(
			'controller' => 'auth',
			'action'     => 'login'
		)
	),
	'logout' => array(
		'uri'    => '/logout.html',
		'params' => array(
			'controller' => 'auth',
			'action'     => 'logout'
		)
	),
	'sign-up-activation-success' => array(
		'uri'    => '/sign-up/successful-activation.html',
		'params' => array(
			'controller' => 'auth',
			'action'     => 'signUpActivationSuccess'
		)
	),
	'sign-up-activation' => array(
		'uri'    => '#^/activation-of-sign-up/(?P<code>[0-9A-Za-z]+).html$#',
		'params' => array(
			'controller' => 'auth',
			'action'     => 'signUpActivation'
		)
	),
	'sign-up' => array(
		'uri'    => '/sign-up.html',
		'params' => array(
			'controller' => 'auth',
			'action'     => 'signUp'
		)
	),
	'index-about' => array(
		'uri'    => '/about.html',
		'params' => array(
			'controller' => 'index',
			'action'     => 'about'
		)
	),
	'index-view-all-video-tags' => array(
		'uri'    => '/view-all-video-tags.html',
		'params' => array(
			'controller' => 'index',
			'action'     => 'viewAllVideoTags'
		)
	),
	'index' => array(
		'uri'    => '/',
		'params' => array(
			'controller' => 'index'
		)
	),
	'forgot_password' => array(
		'uri'    => '/forgot_password.html',
		'params' => array(
			'controller' => 'auth',
			'action'     => 'forgotPassword'
		)
	),
	'forgot_password-check' => array(
		'uri'    => '#^/forgot_password-check/(?P<code>[0-9A-Za-z]+).html$#',
		'params' => array(
			'controller' => 'auth',
			'action'     => 'forgotPasswordCheck'
		)
	),
	'forgot_password-sended' => array(
		'uri'    => '/forgot_password-sended.html',
		'params' => array(
			'controller' => 'auth',
			'action'     => 'forgotPasswordSended'
		)
	),
	'404' => array(
		'uri'    => '/404.html',
		'params' => array(
			'controller' => 'index',
			'action'     => '404'
		)
	),
);