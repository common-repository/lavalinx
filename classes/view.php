<?php

class Lavalinx_View
{
	public $data;
	
	private $_view;
	
	public static function factory($view, $data = array())
	{
		return new Lavalinx_View($view, $data);
	}
	
	public function __construct($view, $data = array())
	{
		$this->data = (object) $data;
		$this->_view = $view;
	}
	
	public function render($return = false)
	{
		extract((array) $this->data);
		if ($return) ob_start();
		include WP_PLUGIN_DIR.'/lavalinx/views/'.$this->_view.'.php';
		if ($return) return ob_get_clean();
	}
}