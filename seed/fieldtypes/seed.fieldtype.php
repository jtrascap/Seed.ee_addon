<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Seed_fieldtype extends Seed_model
{
	public $title = 'default';
	
	public $settings = array(
		array(
			'name' => 'amount_off',
			'short_name' => 'amount_off',
			'note' => 'amount_off_note',
			'type' => 'text'
		)
	);


	public function initialize($plugin_settings = array())
	{
		if (is_array($plugin_settings))
		{
			$this->plugin_settings = $plugin_settings;
		}
		
		$this->type = get_class($this);
		
		return $this;
	}

}
