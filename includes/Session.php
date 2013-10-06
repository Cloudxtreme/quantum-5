<?php

/**
 * Session handler.
 *
 * @package Quantum
 * @author Gabor Klausz
 */
class SessionHandler
{
	/**
	 * Session setter.
	 *
	 * @param string $key     Key.
	 * @param mix    $value   Value.
	 *
	 * @return void
	 */
	public static function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * Session getter.
	 *
	 * @param string $key       Key.
	 * @param mix    $default   Default value.
	 *
	 * @return void
	 */
	public static function get($key, $default = null)
	{
		return !empty($_SESSION[$key]) ? $_SESSION[$key] : $default;
	}

	/**
	 * Destroy session.
	 *
	 * @return void
	 */
	public static function destroy()
	{
		session_destroy();
	}
}