<?php
namespace MyPlugin;

use MyPlugin\Bugsnag;

class Template {

	public function load()
	{
		$overwrite = locate_template(Helper::get('templates') . $filename);

		if ($overwrite != null) {
			$template = $overwrite;
		} else {
			$template = __DIR__ . '/resources/templates/' . $filename;
		}

		try {
			include_once $template;
		} catch (\Exception $e) {
			// 	BugSnag::notifyException($e, null, null);
		}

		return null;
	}
}
