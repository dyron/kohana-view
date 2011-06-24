<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_View_Filter_Var extends FilterIterator {
	public function accept()
	{
		return (strpos(parent::current(), 'var_') === 0) ;
	}
}