<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Template {

	public static $default_type = 'php';

	/**
	 * 
	 */
	public static function factory(View $view, $type = NULL)
	{
		if ($type === NULL)
		{
			$type = Template::$default_type;
		}

		$class = 'Template_'.ucfirst($type);

		return new $class($view);
	}
	/**
	 * 
	 */
	public function __construct(View $view)
	{
		$this->view = $view;
	}
}