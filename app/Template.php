<?php namespace MyPlugin;

class Template {

	public static function load($filename)
	{
		$overwrite = locate_template(Helper::get('templates') . $filename);

		if ($overwrite != null) {
			$template = $overwrite;
		} else {
			$template = dirname(__DIR__) . '/resources/templates/' . $filename;
		}

		try {
			include_once $template;
		} catch (\Exception $e) {

		}

		return null;
	}
}
