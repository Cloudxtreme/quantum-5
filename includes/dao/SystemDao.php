<?php

/**
 * A rendszerrel szukseges oszataly.
 *
 * @package SystemDao
 * @author Gabooo
 *
 */
class SystemDao
{
	/**
	 * Visszadja a static forditasokat
	 *
	 * @param string $lang   Nyelv.
	 *
	 * @return array
	 */
	public static function getTranslateValues($lang)
	{
		$return = array();

		$db = DbFactory::connect();

		$query = '
			SELECT
				id,
				' . $lang . '
			FROM
				translate_value
		';

		$rows = $db->query($query)->fetchAll();

		foreach ($rows as $value) {
			$return[$value['id']] = $value[$lang];
		}

		return $return;
	}

	/**
	 * Visszaadja az alapertelmezett rendszer beallitasokat.
	 *
	 * @return array
	 */
	public static function getSystemValues()
	{
		$db = DbFactory::connect();

		$query = '
			SELECT
				*
			FROM
				system
		';

		return $db->query($query)->fetchRow();
	}
}