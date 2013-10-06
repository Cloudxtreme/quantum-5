<?php

/**
 * System business logic.
 *
 * @package Quantum
 * @subpackage Business logic
 * @author Gabor Klausz
 */
class SystemBo
{
	/**
	 * Returns the system settings.
	 *
	 * @return array
	 */
	public static function getSystemSettings()
	{
		return SystemDao::getSystemValues();
	}
}