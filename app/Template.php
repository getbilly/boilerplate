<?php namespace Billy\Framework;

class Template {

	public function load()
	{
		$overwrite = locate_template(Helper::get('templates') . $filename);

		if ($overwrite != null) {
			$template = $overwrite;
		} else {
			$template = __DIR__ . '/resources/templates/' . $filename;
		}

		include_once $template;
		return null;
	}
}
