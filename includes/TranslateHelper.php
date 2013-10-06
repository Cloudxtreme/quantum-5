<?php

/**
 * Translate helper
 *
 * @package TranslateHelper
 * @author Gabor Klausz
 */
class TranslateHelper
{
    /**
	 * Returns the static values for translate.
	 *
	 * @param string $lang   Language.
	 *
	 * @return array
	 */
	public static function getTranslateValues($lang)
	{
		return SystemDao::getTranslateValues($lang);
	}
}