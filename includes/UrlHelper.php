<?php

/**
 * URL helper.
 *
 * @package Quantum
 * @author Gabor Klausz
 */
class UrlHelper
{
	/**
	 * Friendly URL generator.
	 *
	 * @param string $text   URL.
	 *
	 * @return string
	 */
	public static function friendlyUrlGenerator($text)
	{
		$mit = array(
			' ', '  ', ',', '(', ')', '"', '!', '\\', '/', '\'',
			'?', '+', '.'
		);

		$mire = array(
			'-', '-', '-', '', '',  '', '', '-', '-', '',
			'', '-', ''
		);

		return strtolower(str_replace($mit, $mire, trim($text)));
	}

	/**
	 * Returns the first page URL.
	 *
	 * @param string $language   Language.
	 *
	 * @return string
	 */
	public static function getFirstPageUrl($language)
	{
		return ContentDao::getFirstPageUrl($language);
	}

	/**
	 * Returns URL name.
	 *
	 * return array
	 */
	public static function getUrlName()
	{
		$url = explode('.html', $_SERVER['REQUEST_URI']);

		$currentUrl = $url[0];

		if (substr($url[0], 0, 1) == '/') {
			$currentUrl = substr($url[0], 1);
		}

		if (strpos($currentUrl, '/') !== false) {
			$pageCategory = explode('/', $currentUrl);

			if ($pageCategory[0] == 'product') {
				$product = ProductBo::getProductbyCategory($currentUrl);

				$return['frendly_url_hu'] = $pageCategory[0] . '/' . $product['frendly_url_hu'];
				$return['frendly_url_de'] = $pageCategory[0] . '/' . $product['frendly_url_de'];

				return $return;
			}
		}

		return ContentDao::getUrlName($currentUrl, Request::$pageLanguage);
	}

	/**
	 * Returns URL by Routing rule.
	 *
	 * @param string $routingId      Routing key.
	 * @param string $defaultValue   Default value.
	 *
	 * @return string
	 */
	public static function getUrlByRouting($routingId, $defaultValue = '/index.html')
	{
		$url = '';

		if (Config::get('multilanguage')) {
			$url .= '/' . Request::$pageLanguage;
		}

		return $url . (!empty(Request::$routes[$routingId]) ? Request::$routes[$routingId]['uri'] : $defaultValue);
	}

	/**
	 * Returns URL query.
	 *
	 * @return string
	 */
	public static function getQueryString()
	{
		return $_SERVER['QUERY_STRING'];
	}
}