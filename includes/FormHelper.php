<?php

/**
 * Form helper.
 *
 * @package Quantum
 * @author Gabor Klausz
 */
class FormHelper
{
	/**
	 * Multiselect generator.
	 *
	 * @param string $className        Class name.
	 * @param array  $selectElements   Select element.
	 *
	 * @return string
	 */
	public static function addMultiSelect($label, $className, $selectElements)
	{
		$html = '
			<div class="control-group">
				<label class="control-label" for="name">' . $label .  '</label>
				<div class="controls multiselect">
					<select size="10" style="width: 200px;" name="' . $className . '-f[]" multiple="multiple" id="' . $className . '-f" class="span4" data-action="add" data="' . $className . '">';

	    if (!empty($selectElements)) {
			foreach ($selectElements as $key => $value) {
				$html .= '<option value"' . $key . '">' . $value . '</option>';
			}
	    }

		$html .= '</select>
					<button class="btn ' . $className . '" type="button" data="' . $className . '" data-action="add">>></button>
					<button class="btn ' . $className . '" type="button" data="' . $className . '" data-action="remove"><<</button>
					<select size="10" style="width: 200px;" name="' . $className . '-t[]" multiple="multiple" id="' . $className . '-t" class="span4" data-action="remove" data="' . $className . '"></select>
					<select size="10" style="overflow: hidden; visibility: hidden; width: 1px; height: 0;" name="' . $className . '[]" multiple="multiple">';

		if (!empty($selectElements)) {
			foreach ($selectElements as $key => $value) {
				$html .= '<option value"' . $key . '">' . $value . '</option>';
			}
		}

		$html .= '</select>
				</div>
			</div>
		';

		return $html;
	}

	/**
	 * File uploader.
	 *
	 * @param string $key                File key.
	 * @param string $directory          Direstory.
	 * @param bool   $isImage            Is image.
	 * @param bool   $imageCompression   Image compress.
	 * @param bool   $waterMark          If FALSE then need watermark.
	 *
	 * @return string   Get the filen name.
	 */
	public static function fileUploader($key, $directory = 'userfiles', $isImage, $imageCompression = false, $waterMark = false)
	{
		$fileName   = '';
		$extension  = '';
		$uploadFile = '';

		if (!empty($_FILES[$key]['name'])) {
			$directory = 'static/' . $directory . '/';

			if (!is_dir($directory)) {
				mkdir($directory, 0775);
			}

			$uplodedFileName        = explode('.', $_FILES['image']['name']);
			$uplodedFileNameReverse = array_reverse($uplodedFileName);
			$extension              = $uplodedFileNameReverse[0];
			$fileName               = substr(md5($_FILES[$key]['name'] . microtime()), 0, 8) . '.' . $extension;
			$uploadFile             = $directory . $fileName;

			try {
				if(is_uploaded_file($_FILES[$key]['tmp_name'])){
					if(!move_uploaded_file($_FILES[$key]['tmp_name'], $uploadFile)){
						print "File upload error.";
					}
				}

				if ($isImage) {
					// Compress.
					if ($imageCompression) {
					}

					// Watermark.
					if ($waterMark) {
					}
				}
			}
			catch (Exeption $e) {
				print_r($e);
			}
		}

		return $uploadFile;
	}
}