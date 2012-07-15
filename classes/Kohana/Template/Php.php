<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Template_Php implements Kohana_Template_File {

	/**
	 * Captures the output that is generated when a view is included.
	 * The view data will be extracted to make local variables.
	 *
	 *     $output = $this->capture($file);
	 *
	 * @param   string  filename
	 * @return  string
	 */
	protected function capture($kohana_view_filename)
	{
/*		if ( ! in_array('kohana.view', stream_get_wrappers()))
		{
			stream_wrapper_register('kohana.view', 'View_Stream_Wrapper');
		}

		// Import the view variables to local namespace
		foreach (get_object_vars($this->view) as $variable_name => $value) 
		{
			if (strpos($variable_name, 'var_') === 0)
			{
				$var_name = str_replace('var_', '', $variable_name);
				if (! isset($$var_name))
				{
					$$var_name = $value;
				}
			}
		}

		// Import the functions starting with var_
		foreach (get_class_methods($this->view) as $method_name) 
		{
			if (strpos($method_name, 'var_') === 0)
			{
				$var_name = str_replace('var_', '', $method_name);
				if (! isset($$var_name))
				{
					$$var_name = $this->$method_name();
				}
			}
		}
*/$view = $this->view;
		// Capture the view output
		ob_start();

		try
		{
			include $kohana_view_filename;
			//include 'kohana.view://'.$kohana_view_filename;
		}
		catch (Exception $e)
		{
			// Delete the output buffer
			ob_end_clean();

			// Re-throw the exception
			throw $e;
		}

		// Get the captured output and close the buffer
		return ob_get_clean();
	}


	// Template filename
	protected $_file;

	public function __construct(View $view)
	{
		$this->view = $view;

		if ($this->_file === NULL)
		{
			$file = strtolower(get_class($view));

			if ($file === 'view')
			{
				$file = $view->view;
			}
			else
			{
				$file = strtr(substr($file, 5), '_', '/');
			}

			$this->set_filename($file);
		}
	}

	/**
	 * Sets the view filename.
	 *
	 *     $view->set_filename($file);
	 *
	 * @param   string  $file   view filename
	 * @return  View
	 * @throws  View_Exception
	 */
	public function set_filename($file)
	{
		if (($path = Kohana::find_file('views', $file)) === FALSE)
		{
			throw new View_Exception('The requested view :file could not be found', array(
				':file' => $file,
			));
		}

		// Store the file path locally
		$this->_file = $path;

		return $this;
	}

	/**
	 * 
	 */
	public function __toString()
	{
		try
		{
			return $this->render();
		}
		catch (Exception $e)
		{
			/**
			 * Display the exception message.
			 *
			 * We use this method here because it's impossible to throw and
			 * exception from __toString().
			 */
			$error_response = Kohana_exception::_handler($e);

			return $error_response->body();
		}
	}

	public function render($file = NULL)
	{
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}

		if (empty($this->_file))
		{
			throw new View_Exception('You must set the file to use within your view before rendering');
		}

		// Combine local and global data and capture the output
		return $this->capture($this->_file);
	}
}
