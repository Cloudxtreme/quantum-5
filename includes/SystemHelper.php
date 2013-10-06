<?php

/**
 * System helper.
 *
 * @package Quantum
 * @author Gabor Klausz
 */
class SystemHelper
{
	/**
	 * Returns the default system settings.
	 *
	 * return array
	 */
	public static function getSystemValues()
	{
		return SystemDao::getSystemValues();
	}
}