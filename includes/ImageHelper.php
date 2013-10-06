<?php

/**
 * Image helper.
 *
 * @package Quantum
 * @author Gabor Klausz
 */
class ImageHelper
{
	/**
	 * Image generator.
	 *
	 * @param string $imageFile     Image name.
	 * @param int    $maxSize       Max size.
	 * @param string $newFileName   New generated file name.
	 * @param int    $qualitat      Quality.
	 * @param string $waterMark     Watermark.
	 *
	 * @return array   Image resolution.
	 */
	public static function imageGenerator($imageFile, $maxSize, $newFileName, $qualitat, $waterMark = '')
	{
		if (!file_exists($imageFile))
			return (false);

		// Size setter.
		list($width, $height, $type) = getimagesize($imageFile);
		$larger  = ($width > $height) ? $width : $height;
		$smaller = ($width > $height) ? $height : $width;

		if ($larger <= $maxSize) {
			$newLarger  = $larger;
			$newSmaller = $smaller;
		}
		else {
			$multiplication = $maxSize / $larger;
			$newLarger      = $maxSize;
			$newSmaller     = $smaller * $multiplication;
		}

		$newWidth  = ($width > $height) ? $newLarger : $newSmaller;
		$newHeight = ($width > $height) ? $newSmaller : $newLarger;

		switch ($type) {
			case 1:
				$kep = imagecreatefromgif($imageFile);
				break;

			case 2:
				$kep = imagecreatefromjpeg($imageFile);
				break;

			case 3:
				$kep = imagecreatefrompng($imageFile);
				break;
		}

		$ujkep = imagecreatetruecolor($newWidth, $newHeight);

		imagecopyresampled($ujkep, $kep, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

		imagejpeg($ujkep, $newFileName, $qualitat);

		// Contructor and set source image file
		$thumb = new Thumbnail($newFileName);
		// [OPTIONAL] set maximun memory usage, default 32 MB ('32M'). (use '16M' or '32M' for litter images)
		$thumb->memory_limit = '64M';
		// [OPTIONAL] set maximun execution time, default 30 seconds ('30'). (use '60' for big images o slow server)
		$thumb->max_execution_time = 60;

		if ($waterMark != '') {
		    // [OPTIONAL] set watermark source file, only PNG format [RECOMENDED ONLY WITH GD 2]
			$thumb->img_watermark = 'static/images/watermak/' . $waterMark;
		}

		// [OPTIONAL] set watermark vertical position, TOP | CENTER | BOTTOM
		$thumb->img_watermark_Valing = 'CENTER';
		// [OPTIONAL] set watermark horizonatal position, LEFT | CENTER | RIGHT
		$thumb->img_watermark_Haling = 'CENTER';
		$thumb->process();
		$newImage = $thumb->dump();

		imagejpeg($newImage, $newFileName, $qualitat);

		return array($newWidth, $newHeight);
	}
}